<?php
    require_once("../private.php");
    if ($_SESSION["status"] != 1) {
        header("Location: /admin/login.php");
        die();
    }
    $db = new db();
    $id = $_SESSION["id"];
    $user = $db->query("SELECT * from `users` where id='{$id}';");
    $user = $user->fetch_assoc();
    include("../templates/header.php");
    $nav[5] = "active";
?>
    <body>
<?php include("../templates/navbar.php"); ?>
        <div class="container-fluid">
            <div class="row-fluid">
                <div class="span2 hidden-phone">
<?php include("../templates/sidebar.php"); ?>
                </div>
                <div class="span10">
                    <div class="hero-unit">
                        <p>Change various things about your profile here.</p>
                        <form method="POST">
                            <div class="input-prepend input-append">
                                <span class="add-on"><i class="icon-user"></i></span>
                                <input type="text" value="<?= $user['name'] ?>" name="user">
                                <span class="add-on">Only Administrators can change your username.</span>
                            </div>
                            <div class="input-prepend">
                                <span class="add-on"><i class="icon-key"></i></span>
                                <input type="password" placeholder="Change Password" name="pass">
                            </div>
                            <br>
                            <input type="submit" class="btn btn-primary" value="Add New User">
                        </form>
                    </div>
                </div>
            </div>
        <hr>
        </div>
    </body>
</html>

