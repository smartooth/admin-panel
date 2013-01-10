<?php
    require_once("../private.php");
    $sitestr = ""; $extras = "";
    if (isset($_GET["x"])) { // lazy assumptions for now. TODO

    $extras .= <<<EXTRA
                <div class="alert alert-error">
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                    Username or Password incorrect.
                </div>
EXTRA;
    }

    if ((@$_POST["username"]) && (@$_POST["password"])) {

        $user = urldecode($_POST["username"]);
        $pass = urldecode($_POST["password"]);
        switch (UserFunctions::login($user, $pass)) {
            case 1:
                header("Location: /admin");
                break;
            case 2:
                header("Location: /banned.html");
                break;
            case 3:
                header("Location: /admin/login?x=1");
                break;
            case 4:
                header("Location: /admin");
                die();
                break;
            case 5:
                header("Location: /admin/login?x=2");
                die();
                break;
            default:
                die("Login returned a malfunctioned result.");
                break;
        }

    }



    if (@$user["status"] == 1) {
        if (DEBUG != True) {
            header("Location: /admin");
            die();
        }
    }
    include("../templates/header.php");
?>

	<style type="text/css">
      .form-signin {
        max-width: 300px;
        padding: 19px 29px 29px;
        margin: 0 auto 20px;
        background-color: #fff;
        border: 1px solid #e5e5e5;
        -webkit-border-radius: 5px;
           -moz-border-radius: 5px;
                border-radius: 5px;
        -webkit-box-shadow: 0 1px 2px rgba(0,0,0,.05);
           -moz-box-shadow: 0 1px 2px rgba(0,0,0,.05);
                box-shadow: 0 1px 2px rgba(0,0,0,.05);
      }
    </style>

    <body>
        <hr>
        <div class="container">
            <form class="form-signin" method="POST">
                <h2 class="form-signin-heading">Love Despite Changelog</h2>
<?= $extras ?>
                <div class="input-prepend">
                    <span class="add-on"><i class="icon-user"></i></span>
                    <input type="text" class="input-block-level" placeholder="Username" name="username">
                </div>
                <div class="input-prepend">
                    <span class="add-on"><i class="icon-key"></i></span>
                    <input type="password" class="input-block-level" placeholder="Password" name="password">
                </div>
                <br>
                <button class="btn btn-large btn-primary" type="submit">Sign in</button>
            </form>
        </div> 
        <hr>
    </body>
</html>
