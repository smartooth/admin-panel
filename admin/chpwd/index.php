<?php
    require_once("../private.php");
    if ($_SESSION["status"] != 1) {
        header("Location: /admin/login.php");
        die();
    }
    $message = "";
    $db = new db();
    $id = $_SESSION["id"];
    $user = $db->query("SELECT * from `users` where id='{$id}';");
    $user = $user->fetch_assoc();

    if (isset($_POST["orgpass"]) && isset($_POST["newpass1"]) && isset($_POST["newpass"])) {
        if ($_POST["newpass1"] == $_POST["newpass"]) {
            if (UserFunctions::Change($user["name"], $_POST["orgpass"], $_POST["pass"])) {
                $message = <<<MESSAGE
                        <div class="alert alert-success">
                            <button type="button" class="close" data-dismiss="alert">&times;</button>
                            Password Changed Sucessfully.
                        </div>
MESSAGE;
            } else {
                $message = <<<MESSAGE
                        <div class="alert alert-error">
                            <button type="button" class="close" data-dismiss="alert">&times;</button>
                            Incorrect password.
                        </div>
MESSAGE;
            }
        } else {
            $message = <<<MESSAGE
                        <div class="alert alert-error">
                            <button type="button" class="close" data-dismiss="alert">&times;</button>
                            Passwords do not match.
                        </div>
MESSAGE;
        }

    }
    include("../templates/header.php");

    $nav[4] = "active";

?>
    <body>
<?php include("../templates/navbar.php"); ?>
        <div class="container-fluid">
            <div class="row-fluid">
                <div class="span2 hidden-phone">
<?php include("../templates/sidebar.php"); ?>
                </div><!--/span-->
                <div class="span10">
                    <div class="hero-unit">
                        <p>Change your password here.</p>
<?= $message ?>
                        <form method="POST">
                            <div class="input-prepend">
                                <span class="add-on"><i class="icon-key"></i></span>
                                <input type="password" placeholder="Current Password" name="orgpass" required>
                            </div>
                            <div class="input-prepend">
                                <span class="add-on"><i class="icon-key"></i></span>
                                <input type="password" placeholder="New Password" name="newpass1" required>
                            </div>
                            <div class="input-prepend">
                                <span class="add-on"><i class="icon-key"></i></span>
                                <input type="password" placeholder="New Password Again" name="newpass" required>
                            </div>
                            <br>
                            <input type="submit" class="btn btn-primary" value="Change Password">
                        </form>
                    </div>
                </div>
            </div>
        <hr>
        </div>
    </body>
</html>

