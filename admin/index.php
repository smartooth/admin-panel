<?php
    require_once("private.php");
    if ((!$user) || ($user["status"] != 1)) {
        header("Location: /admin/login");
        die();
    }
    $changelog = new Changelog();
    $changes = $changelog->read_array();
    include("templates/header.php");
    $nav[0] = "active";
?>
    <body>

<?php include("templates/navbar.php"); ?>
        <div class="container-fluid">
            <div class="row-fluid">
                <div class="span2 hidden-phone">
<?php include("templates/sidebar.php"); ?>
                </div>
                <div class="span10">
<?php
    foreach ($changes as $row) {
        switch ($row["type"]) {
        case 0: // add
            $type = "text-success"; break;
        case 1: // fix
            $type = "text-info"; break;
        case 2: // fix
            $type = "text-error"; break;
        default: // the hell?
            $type = "N/A"; break;
        }
        $class_ = $row["major"] == 1 ? "text-warning" : "";
        $class_ = $row["private"] == 1 ? "muted" : $class_;
        echo <<<CHANGE
                    <div class="well">
                        <h4 style="margin: 0" class="{$type}">By {$row["name"]} <span style="float: right"><i class="icon-pencil"></i> &nbsp; <i class="icon-remove-circle"></i></span></h4>
                        <small>{$row["date"]}</small>
                        <p class="{$class_}" style="margin: 0">{$row["comment"]}</p>
                    </div>
CHANGE;
        }
?>
                </div>
            </div>
        <hr>
        </div>
    </body>
</html>

