<?php
    require_once("../private.php");
    if ((!$user) || ($user["status"] != 1)) {
        header("Location: /admin/login");
        die();
    }
    $new_user = False;
    if (isset($_POST["user"]) && isset($_POST["pass"])) {
        UserFunctions::create($_POST["user"], $_POST["pass"]);
        $new_user = True;
    }
    include("../templates/header.php");
    $nav[8] = "active";
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
                        <p>Add a new user here.</p>
<?php
    if ($new_user) {
        $new_username = htmlspecialchars($_POST["user"]);
        echo <<<USER
                        <div class="alert alert-success">
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                        User {$new_username} added successfully.
                        </div>
USER;
    // Oh, how I hate you HEREDOC.
    }
?>
                        <form method="POST">
                            <div class="input-prepend">
                                <span class="add-on"><i class="icon-user"></i></span>
                                <input type="text" placeholder="Username" name="user">
                            </div>
                            <div class="input-prepend">
                                <span class="add-on"><i class="icon-key"></i></span>
                                <input type="password" placeholder="Password" name="pass">
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

