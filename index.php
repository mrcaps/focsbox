<?php
require("db.php");
?>

<html>
<head>
<title>focsbox</title>
<script type="text/javascript" src="lib/jquery.js"></script>
<script type="text/javascript" src="lib/ajaxfileupload.js"></script>

<link href="css/styles.css" rel="stylesheet" type="text/css">
<script type="text/javascript" language="javascript">
$(document).ready(function() {
	$("#loadbar").fadeOut("medium");

	$("#loadbar")
	.ajaxStart(function() {
		$(this).fadeIn("medium");
	})
	.ajaxStop(function() {
		$(this).fadeOut("medium");
	});
});

//for IE not caching
function randno() {
	return Math.floor(Math.random() * 1e8) + "";
}

function getDateFormat(/**Date*/ d) {
	var st = "";
	st += (d.getMonth() + 1) + "/" + d.getDate() + "/" + d.getFullYear();
	st += " ";
	st += d.toLocaleTimeString();
	return st;
}

function listfiles() {
	$("#messages").hide();
	
	//AJAX-obtain the file list
	$.getJSON(
		"listfiles.php?rndno=" + randno(), 
		{idx:getSelIdx()},
		function(data) {
			//place the files in the list
			$("#filelst").empty();
			$("#filelst").hide();
			
			if (data.length == 0) {
				$("#filelst").append(
					$("<div></div>")
						.addClass("nofiles")
						.text("(no files)")
					);
			}
			
			for (var x = 0; x < data.length; ++x) {
				var datum = data[x];
				
				//file modification time
				var d = new Date();
				d.setTime(parseInt(datum["mtime"])*1000);
				
				//create the listing
				$("#filelst").append(
						$("<div></div>")
							.append(
								$("<img>")
									.attr("src", "img/disk.png")
							)
							.addClass("file")
							.append(
								$("<div></div>")
									.addClass("filename")
									.text(datum["name"])
							)
							.append(
								$("<div></div>")
									.addClass("filemtime")
									.text("uploaded: " + getDateFormat(d))
							)							
					);
			}
			
			$("#filelst").slideDown("medium");
			
			//show the upload area
			$("#uploadarea").show("medium");
		});
}

//get the selected name
function getSelIdx() {
	return $("#nameselect").attr("value");
}

//show a message in the message area
//	if iserror, make it angry.
function showmsg(/**String*/ st, /**bool*/ iserror) {
	var qel = $("#messages");
	qel.text(st);
	if (iserror) {
		qel.addClass("error");
	} else {
		qel.removeClass("error");
	}
	qel.slideDown("medium");
}

//actually perform the upload
function doupload() {
	$.ajaxFileUpload
	(
		{
			url:"doupload.php?idx=" + getSelIdx(),
			secureuri:false,
			fileElementId:'uploadfile',
			dataType: 'json',
			success: function (data, status) {
				if (typeof(data.error) != 'undefined') {
					if(data.error != '') {
						showmsg(data.error, true);
					}
					else {
						showmsg(data.msg, false);
					}
				}
				listfiles();
			},
			error: function (data, status, e) {
				showmsg(e, true);
			}
		}
	)
	
	return false;
}
</script>
</head>
<body>

<div id="mainwrap">
	<img id="focsboximage" src="img/focsbox.png" alt="logo" />
	<img id="focsboxtitle" src="img/logo.png" alt="logotext" />
	<div id="headdesc">
		<div>
		<b>focsbox</b> is a drop box for assignments in <a href="http://ece.olin.edu/focs" target="_blank">FOCS, Spring 2009</a>. Please be gentle.
		</div>
		<hr>
		<div>
		Now accepting submissions for <b>ps6</b>. Please submit your <tt>.py</tt> file(s).
		</div>
	</div>
	
	<!-- These have to be on the same line for IE to be pleased -->
	<img src="img/head.png" alt=""><div id="maininner">
		<div id="content">

<div id="choosename" class="center">
	My name is:&nbsp;
	<select id="nameselect">
	<?php
	foreach (getNames() as $k => $v) {
		echo "<option value='$k'>$v</option>";
	}
	?>
	</select>
	<button style="width:50px" onclick="listfiles()">Go</button>
</div>

<div id="messages" class="linetop" style="display:none; margin-bottom: 5px;">

</div>

<div id="filelst" class="linetop">

</div>

<div id="uploadarea" class="center linetop">
	<form name="form" action="" method="POST" enctype="multipart/form-data">
	Add:&nbsp;<input id="uploadfile" type="file" size="30" name="uploadfile" class="input">
	<button id="buttonUpload" onclick="return doupload();">Upload</button>
	<div id="uploading"></div>
	</form>
</div>

<div style="margin: 0; padding:0; font-size: 50%;">&nbsp;</div>

		</div>
	</div>
	<img src="img/foot.png" alt="">
	<img id="loadbar" src="img/ajaxloader.gif" alt="loading...">
</div>

</body>
</html>