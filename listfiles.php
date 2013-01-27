<?php
require("db.php");

if (!isset($_GET["idx"])) {
	die("[]");
}
$idx = $_GET["idx"];

//get only the filenames themselves
$list = getFilesList($idx);
function getchunk($st) {
	$mtime = filemtime($st);
	$name = getFileNameFromPath($st);
	return array(
		"mtime" => $mtime,
		"name" => $name
	);
}
$list = array_map("getchunk", $list);

echo json_encode($list);
?>