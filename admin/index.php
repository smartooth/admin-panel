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
        $major = $row["major"] == 1 ? " checked" : "";
        $private = $row["private"] == 1 ? " checked" : "";
        $class_ = $row["private"] == 1 ? "muted" : $class_;
        $comment = htmlspecialchars($row["comment"]);
        echo <<<CHANGE
                    <div class="well">
                        <h4 style="margin: 0" class="{$type}">
                            By {$row["name"]}
                            <span style="float: right">
                                    <i class="icon-pencil text-info lowered-opacity" data-toggle="collapse" data-target=".edit-collapse-{$row["id"]}"></i>
                                 &nbsp; <i class="icon-remove-circle text-error lowered-opacity" data-toggle="collapse" data-target=".delete-collapse-{$row["id"]}"></i>
                            </span>
                        </h4>
                        <small>{$row["date"]}</small>
                        <p class="{$class_}" style="margin: 0">{$comment}</p>
                        <div class="edit-collapse-{$row["id"]} collapse">
                            <hr>
                            <form method="POST">
                                <input type="hidden" name="id" value="{$row["id"]}">
                                <textarea rows="3" name="comment" class="span12" required>{$comment}</textarea>
                                <input type="checkbox" name="major" value="1"{$major}> Major? 
                                <input type="checkbox" name="private" value="1"{$private}> Private? 
                            </form>
                        </div>
                        <div class="delete-collapse-{$row["id"]} collapse">
                            <hr>
                            <form method="POST">
                                <input type="hidden" name="delete" value="{$row["id"]}">
                                Are you sure you want to delete this?
                                <input class="btn btn-danger" type="submit" value="Yes">
                                <a class="btn" data-toggle="collapse" data-target=".delete-collapse-{$row["id"]}">No</a>
                            </form>
                        </div>
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

