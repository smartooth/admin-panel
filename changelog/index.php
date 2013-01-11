<?php
    require_once("../admin/private.php");
    $title = "Love Despite Changelog";
    $changes = Changelog::read_array();
    include("../admin/templates/header.php");
?>
    <body> 
        <div class="navbar navbar-inverse navbar-fixed-top">
            <div class="navbar-inner">
                <div class="container-fluid">
                    <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </a>
                    <a class="brand" href="#">Love Despite Changelog<i class="icon-wrench"></i></a>
                    <div class="nav-collapse collapse">
                        <div class="nav visible-phone">
                            <p style="padding: 5px; margin: 2px;" class="nav-header">Legend</p>
                            <p style="padding: 5px; margin: 2px;" class="text-success">Green is an added feature</p>
                            <p style="padding: 5px; margin: 2px;" class="text-info">Blue is a fixed bug</p>
                            <p style="padding: 5px; margin: 2px;" class="text-error">Red is a deleted feature</p>
                            <p style="padding: 5px; margin: 2px;" class="text-warning">Golden comment text is a major update </p>
                            <p style="padding: 5px; margin: 2px;" class="muted">This is a changelog for the <a href="/">Love Despite</a> Project</p>
                        </div>
                        <p class="navbar-text pull-right">
                            <a href="https://github.com/HirotoKun/admin-panel" class="navbar-link">View Source on Github</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>    
        <div class="container-fluid">
            <div class="row-fluid">
                <div class="span2 hidden-phone">
                    <div class="well">
                        <p class="text-success">Green is an added feature</p>
                        <p class="text-info">Blue is a fixed bug</p>
                        <p class="text-error">Red is a deleted feature</p>
                        <p class="text-warning">Golden comment text is a major update</p>
                        <p class="muted">This site is also great on mobile devices.</p>
                        <p class="muted">This is a changelog for the <a href="/">Love Despite</a> Project</p>
                    </div>
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
        if ($row["private"] == 1) { continue; }
        $comment = $row["comment"];
        echo <<<CHANGE
                    <div class="well">
                        <h4 style="margin: 0" class="{$type}">{$row["name"]}, in <span class="muted">Programming</span></h4>
                        <small>{$row["date"]}</small>
                        <p class="{$class_}" style="margin: 0">{$comment}</p>
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