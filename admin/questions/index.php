<?php
require_once("../private.php");
if ((!$user) || ($user["status"] != 1)) {
    header("Location: /admin/login");
    die();
}

$cq = new CharQuestions();
$chars = $cq->get_chars();
$questions = $cq->get_questions();

include("../templates/header.php");
$nav[8] = "active";
?>
    <body>

<?php include("../templates/navbar.php"); ?>
        <div class="container-fluid">
            <div class="row-fluid">
                <div class="span2 hidden-phone">
<?php include("../templates/sidebar.php"); ?>
                </div>
                <div class="span10">
                    <div class="pagination-right">
                        <select id="filter">
                            <option value="0">All (<?= count($questions) ?>)</option>
<?php
    foreach ($chars as $row) {
        $id = $row['id'];
        $name = $row['name'];
        $count = $row['count'];

        echo "<option value=\"{$id}\">{$name} ({$count})</option>";
    }
?>
                        </select>
                    </div>
                    <div id="questions">
<?php
    foreach ($questions as $row) {
        $char = $row['char'];
        $question = nl2br(htmlspecialchars($row['question']), false);
        $name = empty($row['name']) ? "<em>Anonymous</em>" : $row['name'];
        $date = $row['datetime'];
        $ip = $row['ipaddress'];

        $pos = strpos($char, " ");
        if ($pos === false)
            $pos = strlen($char);
        $img = "/img/" . substr($char, 0, $pos) . ".png";

        $class = "char-" . $row['character_id'];

        echo <<<QUESTION
                    <div class="well {$class}" style="padding-top: 10px; padding-bottom: 10px;">
                        <div style="display: inline-block; vertical-align: top;">
                            <img style="float: left;" src="{$img}" />
                        </div>
                        <div style="display: inline-block; margin-left: 5px;">
                            <h4 class="text-error">{$char}</h4>
                            <p>{$question}</p>
                            <hr style="margin: 0 0 5px;" />
                            <small>{$name} &bull; {$date} &bull; {$ip}</small>
                        </div>
                    </div>
QUESTION;
    }
?>
                    </div>
                </div>
            </div>
            <hr />
        </div>
    </body>
    <script type="text/javascript">
    //<!--
    $(function () {
        $('#filter').change(function() {
            var val = $('option:selected', this).val();

            $('#questions .well').each(function() {
                if (val == 0) {
                    $(this).removeClass('hidden');
                } else {
                    if ($(this).hasClass('char-' + val)) {
                        $(this).removeClass('hidden');
                    } else {
                        $(this).addClass('hidden');
                    }
                }
            });
        });
    });
    //-->
    </script>
</html>