<?php
$server = "";
$bruger = "";
$kodeord = "";
$database = "";

mysql_connect("$server","$bruger","$kodeord");
mysql_select_db("$database");

$hide_post = ($_SESSION['hide'] == 1 ? "style='display:none;'" : "");
$display_post = ($_SESSION['hide'] == 1 ? "" : "style='display:none'");

function safe($value) {
	if(!is_numeric($value)) {
		$value = mysql_real_escape_string($value);
		$value = htmlspecialchars($value);
	}
	return $value;
}

function set_date($date) {
    return date('d-m-Y',strtotime($date));
}

?>