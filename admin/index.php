<?php
    require_once("private.php");
    if (@$_SESSION["status"] != 1) {
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
                    <div class="hero-unit">
                        <table class="table">
                            <thead>
                                <th>Author</th>
                                <th>Type</th>
                                <th>Comment</th>
                                <th>Private</th>
                                <th>Update</th>
                            </thead>
                            <tbody>
<?php
    foreach ($changes as $row) {
        switch ($row["type"]) {
        case 0: // add
            $type = "<img src='/img/add.png'>"; break;
        case 1: // fix
            $type = "<img src='/img/fix.png'>"; break;
        case 2: // fix
            $type = "<img src='/img/del.png'>"; break;
        default: // the hell?
            $type = "N/A"; break;
        }
        $class_ = $row["major"] == 1 ? "error" : "";
        $class_ = $row["private"] == 1 ? "info" : $class_;
        echo <<<CHANGE
        <tr class="{$class_}">
                    <td>{$row["name"]}</td>
                    <td>{$type}</td>
                    <td>{$row["comment"]}</td>
                    <td>{$row["private"]}</td>
                    <td><i class="icon-pencil"></i> <i class="icon-remove-circle"></i></td>
        </tr>
CHANGE;
        }
?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        <hr>
        </div>
    </body>
</html>

