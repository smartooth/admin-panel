<?php
    require_once("private.php");
    if ((!$user) || ($user["status"] != 1)) {
        header("Location: /admin/login");
        die();
    }
    if (isset($_POST["target"])) {
        switch ($_POST["target"]) {
            case "new":
                $post_changes = $_POST["comment"];
                if ($post_changes == "") {
                    break;
                }
                $post_priv = isset($_POST["private"]) ? 1 : 0;
                $post_major = isset($_POST["major"]) ? 1 : 0;
                $post_type = $_POST["type"];
                $post_author = $_POST["author"];
                $post_category = $_POST["category"];


                $db = new db();
                $query = $db->query("SHOW TABLE STATUS LIKE 'changelog'");
                $post_id = $query->fetch_array()["Auto_increment"];
                $db->close();

                if ($post_author != $user["id"]) {
                    break;
                }
                if ($post_priv == 0) {
                    twitter_announce($post_changes, $post_id);
                }
                Changelog::add_change(
                                    $post_changes,
                                    $post_author,
                                    $post_priv,
                                    $post_major,
                                    $post_type,
                                    $post_category);
                break;
            case "edit":
                $post_id = isset($_POST["id"]) ? $_POST["id"] : False;
                $post_changes = isset($_POST["comment"]) ? $_POST["comment"] : False;
                if (!$post_changes) {
                    break;
                }
                $post_priv = isset($_POST["private"]) ? $_POST["private"] : 0;
                $post_major = isset($_POST["major"]) ? $_POST["major"] : 0;
                $post_type = isset($_POST["type"]) ? $_POST["type"] : 0;
                $post_category = $_POST["category"];

                if ($post_id) {
                    Changelog::edit_change(
                                        $post_id,
                                        $post_changes,
                                        $post_priv,
                                        $post_major,
                                        $post_type,
                                        $post_category);
                break;
            case "delete":
                if (isset($_POST["id"])) {
                    Changelog::delete_change($_POST["id"]);
                }
                break;
            default:
                break;
        }
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
                    <div class="well">
                        <h4 style="text-align: center; margin: 0;" class="text-success lowered-opacity" data-toggle="collapse" data-target=".new-commit-collapse">New Commit</h4>
                        <div class="new-commit-collapse collapse">
                            <hr>
                            <form method="POST">
                                <input type="hidden" name="target" value="new">
                                <input type="hidden" name="author" value="<?= $user["id"] ?>">
                                <textarea rows="3" name="comment" class="span12" placeholder="Enter commit here"></textarea>
                                <input type="text" placeholder="Category" name="category">
                                <p>
                                    <input type="checkbox" name="major" value="1"> Major? 
                                    <input type="checkbox" name="private" value="1"> Private?
                                </p>
                                <select name="type">
                                    <option class="text-success" value="0">Add</option>
                                    <option class="text-info" value="1">Fix</option>
                                    <option class="text-error" value="2">Del</option>
                                </select>
                                <br>
                                <input type="submit" class="btn btn-success" value="Submit">
                                <a class="btn" data-toggle="collapse" data-target=".new-commit-collapse">Cancel</a>
                            </form>
                        </div>
                    </div>
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
        $privtext = $row["private"] == 1 ? "<span class=\"muted\">(Private)" : "";
        $comment = htmlspecialchars($row["comment"]);
        $sel = array('','','');
        $sel[$row["type"]] = " selected";

        echo <<<CHANGE
                    <div class="well">
                        <h4 style="margin: 0" class="{$type}">
                            {$row["name"]} {$privtext}
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
                                <input type="hidden" name="target" value="edit">
                                <input type="hidden" name="id" value="{$row["id"]}">
                                <textarea rows="3" name="comment" class="span12" required>{$comment}</textarea>
                                <input type="text" value="{$row["category"]}" name="category">
                                <p>
                                <input type="checkbox" name="major" value="1"{$major}> Major? 
                                <input type="checkbox" name="private" value="1"{$private}> Private?
                                </p>
                                <select>
                                    <option class="text-success" name="type" value="0"{$sel[0]}>Add</option>
                                    <option class="text-info" name="type" value="1"{$sel[1]}>Fix</option>
                                    <option class="text-error" name="type" value="2"{$sel[2]}>Del</option>
                                </select>
                                <br>
                                <input type="submit" class="btn btn-warning" value="Edit">
                                <a class="btn" data-toggle="collapse" data-target=".edit-collapse-{$row["id"]}">Cancel</a>
                            </form>
                        </div>
                        <div class="delete-collapse-{$row["id"]} collapse">
                            <hr>
                            <form method="POST">
                                <input type="hidden" name="target" value="delete">
                                <input type="hidden" name="id" value="{$row["id"]}">
                                <p class="text-warn">Are you sure you want to delete this?</p>
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

