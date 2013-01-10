<?php
    require_once("../private.php");
    if ($_SESSION["status"] != 1) {
        header("Location: /admin/login.php");
        die();
    }
    $db = new db();
    $id = $_SESSION["id"];
    $user = $db->query("SELECT * from `users` where id='{$id}';");
    $user = $user->fetch_assoc();
    include("../templates/header.php");
    $nav[7] = "active";
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
                        <p>Big TODO here; add permissions</p>
                        <table class="table table-hover">
                            <thead>
                                <th>ID</th>
                                <th>Username</th>
                                <th>Update</th>
                            </thead>
                            <tbody>
<?php
$users = array();
$rows = $db->query("SELECT * FROM users;");
while ($row = $rows->fetch_assoc()) {
	array_push($users, $row);
}
$dropdown = <<<DROPDOWN
                                    <div class="btn-group">
                                        <a class="btn btn-primary" href="#"><i class="icon-user"></i>  Edit</a>
                                        <a class="btn btn-primary dropdown-toggle" data-toggle="dropdown" href="#"><span class="caret"></span></a>
                                        <ul class="dropdown-menu">
                                            <li><a href="#"><i class="icon-pencil"></i> Change Password</a></li>
                                            <li><a href="#"><i class="icon-trash"></i> Delete</a></li>
                                            <li><a href="#"><i class="icon-ban-circle"></i> Ban</a></li>
                                            <li class="divider"></li>
                                            <li><a href="#"><i class="i"></i> Make admin</a></li>
                                        </ul>
                                    </div>
DROPDOWN;
foreach ($users as $user_row) {
echo <<<UA
                                <tr>
                                    <td>{$user_row["id"]}</td>
                                    <td>{$user_row["name"]}</td>
                                    <td>
{$dropdown}
                                    </td>
                                </tr>
UA;
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

