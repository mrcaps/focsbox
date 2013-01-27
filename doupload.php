<?php
include("db.php");

//script based off of AJAX file uploader: http://www.phpletter.com/Demo/AjaxFileUpload-Demo/
$error = "";
$msg = "";
$fileElementName = 'uploadfile';
if(!empty($_FILES[$fileElementName]['error'])) {
	switch($_FILES[$fileElementName]['error']) {
		case '1':
			$error = 'The uploaded file exceeds the upload_max_filesize directive in php.ini';
			break;
		case '2':
			$error = 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form';
			break;
		case '3':
			$error = 'The uploaded file was only partially uploaded';
			break;
		case '4':
			$error = 'No file was uploaded.';
			break;
		case '6':
			$error = 'Missing a temporary folder';
			break;
		case '7':
			$error = 'Failed to write file to disk';
			break;
		case '8':
			$error = 'File upload stopped by extension';
			break;
		case '999':
		default:
			$error = 'No error code available';
	}
} 
elseif (empty($_FILES[$fileElementName]['tmp_name']) || $_FILES[$fileElementName]['tmp_name'] == 'none') {
	$error = 'No file was uploaded..';
} 
else {
	if (!isset($_GET["idx"])) {
		$error = "No user specified.";
	}
	else {
		$idx = $_GET["idx"];
		$handl = $_FILES[$fileElementName];
		if (file_exists(getSubFileDir($idx) . "/" . $handl["name"])) {
			$error = "File already exists - please choose another name.";
		}
		elseif (!move_uploaded_file($handl["tmp_name"], getSubFileDir($idx) . "/" . $handl["name"])) {
			$error = "Error moving uploaded file.";
		}
		else {
			$msg .= "File " . $handl['name'] . " uploaded.";
			
			//$msg .= " File Size: " . @filesize($_FILES[$fileElementName]['tmp_name']);
			//for security reason, we force to remove all uploaded file
			//@unlink($_FILES[$fileElementName]);
		}
	}
}		
echo "{";
echo 	"error: '" . $error . "',\n";
echo	"msg: '" . $msg . "'\n";
echo "}";
?>