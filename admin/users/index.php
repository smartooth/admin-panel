<?php
    require_once("../private.php");
    if ((!$user) || ($user["status"] != 1)) {
        header("Location: /admin/login.php");
        die();
    }
    $db = new db();
    include("../templates/header.php");
    $nav[7] = "active";
    $users = array();
    $twitters = array();
    $rows = $db->query("SELECT * FROM users;");
    while ($row = $rows->fetch_assoc()) {
        array_push($users, $row);
    }
    $twitter_ = $db->query("SELECT * FROM twitter WHERE username='LoveDespite';");
    $twitter_array = $twitter_->fetch_assoc();
    
    // block for $_POST here
    
    if (isset($_POST["target"])) {
        switch ($_POST["target"]) {
            $db = new db();
            case "twitter_edit":
                if ((isset($_POST["token"])) && (isset($_POST["secret"])) {
                    if ($query = $db->prepare("UPDATE twitter SET token=?, secret=? WHERE username='LoveDespite'")) {
                        $query->bind_param("ss", $_POST["token"], $_POST["secret"]);
                        $query->execute();
                        $query->close();
                    }
                    $db->close();
                }
                break;
            case "user_add":
                break;
            case "user_del":
                break;
            case "user_edit":
                break;
            default:
                $db->close();
                break;
        }
    }
    
    
?>
    <body>
<?php include("../templates/navbar.php"); ?>
        <div class="container-fluid">
            <div class="row-fluid">
                <div class="span2 hidden-phone">
<?php include("../templates/sidebar.php"); ?>
                </div>
                <div class="span10">
                    <div class="well">
                        <h3 class="text-info">Twitter 
                        <span style="float: right">
                            <span class="lowered-opacity" data-toggle="collapse" data-target=".twitter-collapse"><i class="icon-twitter"></i> </span>
                            <span class="lowered-opacity" data-toggle="collapse" data-target=".twitter-edit-collapse"><i class="icon-pencil"></i></span>
                        </span>
                        </h4>
                        <div class="twitter-collapse collapse">
                            <hr>
                            <h4 class="text-info">@<?= $twitter_array["username"] ?></h4>
                            <p>Token: <span class="text-warning"><?= $twitter_array["token"] ?></span></p>
                            <p>Secret: <span class="text-warning"><?= $twitter_array["secret"] ?></span></p>
                        </div>
                        <div class="twitter-edit-collapse collapse">
                            <hr>
                            <form method="POST">
                                <input type="hidden" name="target" value="twitter_edit">
                                <input type="text" placeholder="Access Token" name="token">
                                <br>
                                <input type="text" placeholder="Access Secret" name="secret">
                                <input type="submit" class="btn btn-info" value="Change Token Details">
                                <a class="btn" data-toggle="collapse" data-target=".twitter-edit-collapse">Cancel</a>
                            </form>
                        </div>
                        <hr>
                        <h3 class="text-success">Users
                            <span style="float: right"><span class="lowered-opacity" data-toggle="collapse" data-target=".users-add-collapse"><i class="icon-plus"></i> </span>
                            <span class="lowered-opacity" data-toggle="collapse" data-target=".users-view-collapse"><i class="icon-eye-open"></i> </span></span>
                        </h3>
                        <div class="users-add-collapse collapse">
                            <hr>
                            <h4 class="text-success">Add a new user</h4>
                            <form method="POST">
                                <input type="hidden" name="target" value="add_user">
                                <div class="input-prepend">
                                    <span class="add-on"><i class="icon-user"></i></span>
                                    <input type="text" placeholder="Username" name="user">
                                </div>
                                <div class="input-prepend">
                                    <span class="add-on"><i class="icon-key"></i></span>
                                    <input type="password" placeholder="Password" name="pass">
                                </div>
                                <br>
                                <input type="submit" class="btn btn-success" value="Add New User">
                            </form>
                        </div>
                        <div class="users-view-collapse collapse">
<?php
    foreach ($users as $u) {
        //$admin = $u["admin"] == 1 ? "<span class="warning"> <i class="icon-wrench"></i> (Admin)</span>" : "";
        switch ($u["status"]) {
            case 0: // not logged in
                $status = "Not Logged In";
                break;
            case 1: // logged in
                $status = "Logged In";
                break;
            case 2: // banned
                $status = "Banned";
                break;
            default:
                $status = "Broken User (Fix)";
                break;
        }
        echo <<<USER
                            <hr>
                            <h4 class="text-success">{$u["name"]}
                            <span style="float: right" class="lowered-opacity" data-toggle="collapse" data-target=".user-edit-{$u["id"]}"><i class="icon-pencil"></i></span></h4>
                            <p>Status: <span class="text-warning">{$status}</span></p>
                            <div class="user-edit-{$u["id"]} collapse">
                                <hr>
                                <form method="POST">
                                    <input type="hidden" name="target" value="edit_user">
                                    <input type="hidden" value="{$u["id"]}" name="id">
                                    <div class="input-prepend">
                                        <span class="add-on"><i class="icon-user"></i></span>
                                        <input type="text" placeholder="New Username" name="user">
                                    </div>
                                    <div class="input-prepend">
                                        <span class="add-on"><i class="icon-key"></i></span>
                                        <input type="password" placeholder="New Password" name="pass">
                                    </div>
                                    <div class="input-prepend">
                                        <span class="add-on"><i class="icon-key"></i></span>
                                        <input type="password" placeholder="Repeat Password" name="pass_repeat">
                                    </div>
                                    <br>
                                    <input type="submit" class="btn btn-warning" value="Edit">
                                    <a class="btn" data-toggle="collapse" data-target=".user-edit-{$u["id"]}">Cancel</a>
                                </form>
                            </div>
USER;
    }
?>
                        </div>
                    </div>
                </div>
            </div>
        <hr>
        </div>
    </body>
</html>

