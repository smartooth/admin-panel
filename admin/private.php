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

    function get_user_array($username = "") {
        /* Does all of the heavy lifting for getting user stats. returns false if not logged in. */
        $db = new db();
        if ($username == "") {
            if (isset($_SESSION["id"])) {
                $sid = $_SESSION["id"];
                if ($query = $db->prepare("SELECT id, name, status from `users` where id=?")) {
                    $query->bind_param("i", $sid);
                    $query->execute();
                    $query->bind_result($id, $name, $status);
                    $query->fetch();
                    $query->close();
                    $db->close();
                    return array("name" => $name, "status" => $status, "id" => $id);
                } else { return false; }
            } else { return false; }
        } else {
            if ($query = $db->prepare("SELECT id, name, passhash, status from `users` where name=?")) {
                $query->bind_param("s", $username);
                $query->execute();
                $query->bind_result($id, $name, $passhash, $status);
                $query->fetch();
                $query->close();
                $db->close();
                return array("name" => $name, "status" => $status, "id" => $id, "passhash" => $passhash);
            } else { return false; }
        }
        $db->close();
        return false;
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
            $user_login = get_user_array($user);
            if (!$user_login) { 
                die("A not logged in user just tried to change their password. Someone made a boo-boo.");
            } else {
                if (PassHash::compare($oldpass, $user_login["passhash"])) {
                    $tmp = PassHash::hash($newpass);
                    if ($query = $db->query("UPDATE `users` SET `passhash`=? WHERE `name`=?")) {
                        $query->bind_param("ss", $tmp, $user_login["name"]);
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

        public static function login($username, $pass) {
            // TODO: something along the lines of making this transferrable to anyone
            $user = get_user_array();
            $user_login = get_user_array($username);
            if (!$user_login)
                return 3;
            
            if ($user) {
                switch ($user["status"]) {
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
                $db = new db();
                $db->query("UPDATE users SET status=1 where id='{$user_login["id"]}';");
                $db->close();
                /* I got it from a database. Trusting it. :v */
                return 4; // good login. TODO: make this use true/false?
            } else {
                return 5; // bad login (incorrect password)
            }
        }
        public static function logout() {
            session_destroy();
            $db = new db();
            if ($query = $db->prepare("UPDATE users SET status=0 WHERE id=?")) {
                $query->bind_param("i", $_SESSION["id"]);
                $query->execute();
                $query->close();
            } // if this fails, we have a problem. TODO: add logging
            $db->close();
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
        public static function add_change($change, $author, $priv, $major, $type) {
            $db = new db();
            $time = date("Y-m-d H:i:s");
            if ($query = $db->prepare("INSERT INTO `changelog` ( `type`, `comment`, `authorid`, `private`, `major`, `date` ) VALUES ( ?,?,?,?,?,?)")) {
                $query->bind_param("isiiis", $type, $change, $author, $priv, $major, $time);
                $query->execute();
                $query->close();
                $db->close();
                return true;
            } else {
                $db->close();
                return false; // not likely to happen, but /shrug (db() Would have already errored)
            }
        }
        public static function delete_change($id) {
            $db = new db();
            if ($query = $db->prepare("DELETE FROM changelog WHERE id=?")) {
                $query->bind_param("i", $id);
                $query->execute();
                $query->close();
            }
            $db->close();
        }
        public static function edit_change($id, $comment, $private, $major, $type) {
            $db = new db();
            if ($query = $db->prepare("UPDATE changelog SET comment=?, private=?, major=?, type=? WHERE id=?")) {
                $query->bind_param("siiii", $comment, $private, $major, $type, $id);
                $query->execute();
                $query->close();
            }
            $db->close();
        }
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
