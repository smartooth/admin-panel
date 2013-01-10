<?php
    require_once("../private.php");
    UserFunctions::logout();
    header("Location: /admin/login");
?>
