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
        $user = $_POST["username"];
        $pass = $_POST["password"];
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


    if ($user) {
        if ($user["status"] == 1) {
            if (DEBUG != True) {
                header("Location: /admin");
                die();
            }
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
                    <input id="pass-toggle" type="password" class="input-block-level" placeholder="Password" name="password">
                </div>
                <input type="checkbox" name="remember"> Remember Me?
                <hr>
                <button class="btn btn-large btn-primary" type="submit" style="width:40%">Sign in</button>
                <button id="pass-check" class="btn btn-large" style="width:50%">Show Pass</button>
            </form>
        </div> 
        <hr>
    </body>
    <script>
        $("#pass-check").toggle(function() {
            $("#pass-toggle").get(0).type = "text"; // show password
            $(this).html("Hide Pass");
        }, function() {
            $("#pass-toggle").get(0).type = "password"; // hide it
            $(this).html("Show Pass");
        });
    </script>
</html>
