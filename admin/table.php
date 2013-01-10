<?php

class Table {
    public function __construct(array $tablehead, $type = "table table-hover table-condensed") {
	// Initialised with an array of strings as headers
        $this->headers = $tablehead;
        $this->type = $type;
        $this->rows = array();
        $this->result = '';
    }

    public function add_row(array $row) {
        array_push($this->rows, $row);
    }

    public function debug() {
	echo "<pre>Headers:";
	print_r($this->headers);
	echo "Rows:";
	print_r($this->rows);
	echo "</pre>";
    }

    public function emit() {

	// Table Definition
        $this->result .= "<table class='{$this->type}'>";

	// Table Head

	$this->result .= "<thead>";
	foreach ($this->headers as $header) {
            $this->result .= "<th>$header</th>";
	}
	$this->result .= "</thead>";

	// Table Content

	$this->result .= "<tbody>";
	foreach ($this->rows as $row) {
		$trclass = $row["tr_class"];
	        $this->result .= "<tr" . $trclass . ">";
	        foreach ($row["content"] as $td) {
		    $this->result .= "<td>$td</td>";
	        }
		$this->result .= "</tr>";
        }
	$this->result .= "</tbody>";

	// Table End
	$this->result .= "</table>";

	echo $this->result;

    }
}

?>
