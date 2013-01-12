<?php
    require_once("../private.php");
    if ((!$user) || ($user["status"] != 1)) {
        header("Location: /admin/login.php");
        die();
    }
    $db = new db();
    include("../templates/header.php");
    $nav[7] = "active";
    $users = array();
    $twitters = array();
    $rows = $db->query("SELECT * FROM users;");
    while ($row = $rows->fetch_assoc()) {
        array_push($users, $row);
    }
    $twitter_ = $db->query("SELECT * FROM twitter");
    while ($row_ = $twitter_->fetch_assoc()) {
        array_push($twitters, $row_);
    }
    $dropdown = <<<DROPDOWN
                                        <div class="btn-group">
                                            <a class="btn btn-warning" href="#"><i class="icon-user"></i>  Edit</a>
                                            <a class="btn btn-warning dropdown-toggle" data-toggle="dropdown" href="#"><span class="caret"></span></a>
                                            <ul class="dropdown-menu">
                                                <li><a><i class="icon-pencil"></i> Change Password</a></li>
                                                <li><a><i class="icon-trash"></i> Delete</a></li>
                                                <li><a><i class="icon-ban-circle"></i> Ban</a></li>
                                                <li class="divider"></li>
                                                <li><a href="#"><i class="i"></i> Make admin</a></li>
                                            </ul>
                                        </div>
DROPDOWN;

?>
    <body>
<?php include("../templates/navbar.php"); ?>
        <div class="container-fluid">
            <div class="row-fluid">
                <div class="span2 hidden-phone">
                <?php include("../templates/sidebar.php"); ?>
                </div>
                <div class="span10">
                    <div class="well">
                        <h3 style="text-align: center;" class="text-error" data-toggle="collapse" data-target=".twitter-collapse">Twitter</h4>
                        <div class="twitter-collapse collapse">
                            <hr>
                            <a href="<?= $twitter->getAuthorizationUrl(); ?>"><h3 style="text-align: center;" class="text-info" data-toggle="collapse" data-target=".twitter-collapse">Add Twitter <i class="icon-twitter"></i></h4></a>
<?php
    foreach ($twitters as $tw) {
        $active = $tw["active"] ? " (Active)" : "";
        echo <<<TW
                            <hr>
                            <h4 class="text-info">@{$tw["username"]}{$active}</h4>
                            <p>Token: <span class="text-warning">{$tw["token"]}</span></p>
                            <p>Secret: <span class="text-warning">{$tw["secret"]}</span></p>
                            
TW;
    }
?>
                        </div>
                        <hr>
                        <h3 style="text-align: center;" class="text-success" data-toggle="collapse" data-target=".users-collapse">Users</h4>
                        <div class="users-collapse collapse">
<?php
    foreach ($users as $u) {
        //$admin = $u["admin"] == 1 ? "<span class="warning"> <i class="icon-wrench"></i> (Admin)</span>" : "";
        switch ($u["status"]) {
            case 0: // not logged in
                $status = "Not Logged In";
                break;
            case 1: // logged in
                $status = "Logged In";
                break;
            case 2: // banned
                $status = "Banned";
                break;
            default:
                $status = "Broken User (Fix)";
                break;
        }
        echo <<<USER
                            <hr>
                            <h4 class="text-success">{$u["name"]}</h4>
                            <p>Status: <span class="text-warning">{$status}</span></p>
                            <form method="POST">
                                <input type="hidden" name="id" value="{$u["id"]}">
                                {$dropdown}
                            </form>
USER;
    }
?>
                        </div>
                    </div>
                </div>
            </div>
        <hr>
        </div>
    </body>
</html>

