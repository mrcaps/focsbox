<?php
$students = array(
	array("Name","email.address@domain.edu")
);

function getnamesmapper($v) {return $v[0];}
function getNames() {
	global $students;	return array_map("getnamesmapper", $students);
}

function getemailsmapper($v) {return $v[1];}
function getEmails() {
	global $students;
	return array_map("getemailsmapper", $students);
}

function getName($idx) {
	global $students;
	return $students[$idx][0];
}
function getNamesLen() {
	global $students;
	return count($students);
}
//return a name that can be used in the filesystem
function getSafeName($idx) {
	global $students;
	$unsafe = $students[$idx][0];
	
	$safe = strtolower($unsafe);
	$safe = ereg_replace("[^a-z]","", $safe);
	
	return $safe;
}
//return the email address for the given student
function getEmail($idx) {
	global $students;
	return $students[$idx][1];
}

function getFilesDir() {
	return "files";
}
function getSubFileDir($idx) {
	return getFilesDir() . "/" . getSafeName($idx); 
}
function getFilesList($idx) {
	$subd = getSubFileDir($idx);
	
	if (!file_exists($subd)) {
		//This is user+group read/write/execute, which does not allow
		//directory listing of everyone's stuff.
		//see http://us.php.net/function.mkdir
		mkdir($subd, 0770);
	}
	
	return glob($subd . "/*");
}

function gethasfilesmapper($v) {return count(getFilesList($v));}
function getHasFilesList() {
	global $students;
	return array_map("gethasfilesmapper", array_keys($students));
}

function getFileNameFromPath($path) {
	$lslsh = strrpos($path,"/");
	return substr($path, $lslsh + 1);
}
?>