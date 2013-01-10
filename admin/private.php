<?php
    session_start();
    require_once("config.php");
    class db extends mysqli {
        public function __construct($a = DB_HOST,
                                    $b = DB_USER,
                                    $c = DB_PASS,
                                    $d = DB_NAME,
                                    $persistent = true) {
            if ($persistent) {
                parent::__construct("p:" . $a, $b, $c, $d);
            } else {
                parent::__construct($a, $b, $c, $d);
            }
        }
    }

    static function get_user_array() {
        /* Does all of the heavy lifting for getting user stats. returns false if not logged in. */
        $db = new db();
        if (isset($_SESSION["id"])) {
            $id = $_SESSION["id"];
            if ($query = $db->prepare("SELECT name, status from `users` where id=?")) {
                $query->bind_param("i", $id);
                $query->execute();
                $query->bind_result($name, $status);
                $query->fetch();
                $query->close();
            }
            $db->close();
            return array("name" => $name, "status" => $status, "id" => $id);
        } else {
            $db->close();
            return false;
        }
    }
    class PassHash {
        public static function rand_str($length) {
            $chars = "0123456789./qwertyuiopasdfghjklzxcvbnmQWERTYUIOPASDFGHJKLZXCVBNM"; //only allowed chars in the blowfish salt.
            $size = strlen($chars);
            $str = "";
            for ($i = 0; $i < $length; $i++)
                $str .= $chars[rand(0, $size - 1)]; // hello zend and C.
            return $str;
        }
        public static function hash($input) {
            return crypt($input, "$2y$13$" . self::rand_str(22));
            // 2y is an exploit fix, and an improvement over 2a. Only available in 5.4.0+
        }
        public static function hash_weak($input) {
            return crypt($input, "$2a$13$" . self::rand_str(22)); }
            // legacy support, TODO?(Hiroto): Add exception handling and fall back to <= 5.3.0
        public static function compare($input, $hash) {
            return (crypt($input, $hash) === $hash);
        }
    }

    class UserFunctions {
        public static function change($user, $oldpass, $newpass) {
            $db = new db();
            if ($query = $db->prepare("SELECT id, passhash FROM `users` WHERE name=?")) { 
                $query->bind_param("s", $user);
                $query->execute();
                $query->bind_result($id, $passhash);
                $query->fetch();
                $query->close();
                if ($id == 0) {
                    die("A not logged in user just tried to change their password. Someone made a boo-boo.");
                } else {
                    if (PassHash::compare($oldpass, $passhash)) {
                        $tmp = PassHash::hash($newpass);
                        if ($query = $db->query("UPDATE `users` SET `passhash`=? WHERE `name`=?")) {
                            $query->bind_param("ss", $tmp, $user);
                            $query->execute();
                            $query->close();
                            $db->close();
                            return true; //success
                        } else {
                            $db->close();
                            return false;
                        }
                    } else {
                        $db->close();
                        return false;//invalid pass
                    }
                }
            }
        }

        public static function login($username, $pass) {
            // TODO: something along the lines of making this transferrable to anyone
            $user = get_user_array();
            $user_login = get_user_array($username);
            if (!$user_login) return 3; // no such user
            if ($user) {
                switch ($user["status"]) { // cheap_error_handling.jpg
                // oh no, he's using session cookies. i'd use something better, but the sniffing vuln is still there. SSL Suggested.
                    case 0:
                        break; // not logged in, but session cookie sent.
                    case 1:
                        return 1; // logged in already.
                        break;
                    case 2:
                        return 2; // banned. how scary.
                        break;
                    default:
                        die("Technically what just happened isn't even possible. \$user[\"status\"] is empty/invalid.");
                } // not logged in, continue
            }

            if (PassHash::compare($pass, $user_login["passhash"])) {
                $_SESSION["id"] = $user_login["id"]; //everything is resolved from ID, session doesn't use excessive storage in this. the db does.
                return 4; // good login. TODO: make this use true/false?
            } else {
                return 5; // bad login (incorrect password)
            }
        }
        public static function logout() {
            session_destroy();
            session_start();
        }
        public static function create($user, $pass) {
            $db = new db();
            $passhash = PassHash::hash($pass);
            if ($x = $db->prepare("INSERT INTO `users` ( `name`, `passhash`, `status` ) VALUES ( ?, ?, 0 );")) {
                $x->bind_param("ss", $user, $passhash);
                $x->execute(); $x->close();
                $db->close();
                return true;
            } // All hail prepared queries
            return false;
        }
    }
    class Changelog {
        public static function add_change($change, $author, $web, $priv, $major, $type) {
            $db = new db();
            $time = date("Y-m-d H:i:s");
            if ($query = $db->prepare("INSERT INTO `changelog` ( `type`, `comment`, `authorid`, `private`, `web`, `major`, `date` ) " .
                "VALUES ( ?,?,?,?,?,?,?)")) {
                $query->bind_param("isiiiis", $type, $change, $author, $priv, $web, $major, $time);
                $query->execute();
                $query->close();
                $db->close();
                return true;
            } else {
                $db->close();
                return false; // not likely to happen, but /shrug (db() Would have already errored)
            }
        }
        public static function changes ( $limit = 200 ) {
            // TODO: Change this to not look so messy.
            $db = new db();
            $query = "SELECT `changelog`.*, `users`.`name` FROM `changelog` JOIN `users` ON `changelog`.`authorid` = `users`.`id` WHERE `private` = 0 ORDER BY `changelog`.`date` DESC LIMIT {$limit};";
            $y = $db->query($query);
            $i = 0; $final = '';
            //echo $y->num_rows;
            for ($i = 0; $i < $y->num_rows; $i++) {
                $x = $y->fetch_assoc();
                if ($x["private"] == 1)
                    continue;
                $final .= "\t";
                $final .= "<div class=\"changelog-text\">";
                $tmp = strtotime($x["date"]); $final .= date("dS M, Y", $tmp) . " ";
                switch ($x["type"]) {
                  case 0: // add
                    $final .= "<img src=\"../img/add.png\">";
                    break;
                  case 1: // fix
                    $final .= "<img src=\"../img/fix.png\">";
                    break;
                  case 2: // del
                    $final .= "<img src=\"../img/del.png\">";
                    break;
                  default:
                    die('Error in $x[\"type\"] in Changelog::changes();');
                    break;
                }
                if ($x["web"] == 1) { $final .= " <img src=\"../img/web.png\">"; }
                if ($x["major"] == 1) { $final .= " <img src=\"../img/major.png\">"; }
                $final .= " [<div class=\"author\">{$x["name"]}</div>] ";
                $final .= $x["comment"] . "</div>\n";
            }
            return $final;
        }
        // ^ messy. ew.
        public static function read_array($limit = 25) {
            $db = new db();
            $query = "SELECT `changelog`.*, `users`.`name` FROM `changelog` JOIN `users` ON `changelog`.`authorid` = `users`.`id` WHERE `private` = 0 ORDER BY `changelog`.`date` DESC LIMIT {$limit};";
            $array = array();
            $res = $db->query($query);
            while($row = $res->fetch_assoc()) { // Input can only be int. It's a waste of space to use a prepared query...
                array_push($array, $row);
            }
            return $array;
        }
    }
    
    $user = get_user_array();
    if (!$user) {
        UserFunctions::logout(); // previously deleted user.
    }
?>
