<?php
    require_once("../admin/private.php");
    $changes = Changelog::changes();
?>
<html>
    <head>
        <link rel="stylesheet" href="/css/changelog.css" type="text/css" />
        <title>Love Despite Changelog</title>
        <meta http-equiv="Content-Type: text/html; charset=utf-8" />
        <!-- todo: add jquery, ajax + JSONP requests -->
    </head>
    <body>
        <div class="container">
            <h4>This is the public Changelog for Love Despite. Game updates, as well as site updates will be posted here as they are done. PS: Work in Progress, still.</h4>
<?= $changes ?>
        </div>
    </body>
</html>
