<?php
include("db.php");

//super-sketch security
$THEPASS = "YOUR-SECRET-KEY-GOES-HERE";
if (!isset($_GET["pass"]) || $_GET["pass"] != $THEPASS) {
	die("Oh, noes... the password not right.");
}
?>

<html>
<head>
<title>focsbox : admin</title>
<script type="text/javascript" src="lib/jquery.js"></script>
<style type="text/css">
#content {
	padding-top: 5px !important;
}

.stutable {
	border-collapse: collapse;
}
.stutable td {
	padding: 2px 4px 2px 4px;
	border-top: 1px solid #cccccc;
	border-bottom: 1px solid #cccccc;
}

.student {
	font-weight: bold;
}
.none {
	color: #ff0000;
}
.some {
	color: #009900;
}

#botherlist {
	width: 400px;
	height: 80px;
}
</style>
<script type="text/javascript" language="javascript">
var emails = <?php echo json_encode(getEmails()); ?>;
//eew, hackish.
var counts = <?php echo json_encode(getHasFilesList()); ?>;
$(document).ready(function() {
	emaillist();
});

function emaillist() {
	var addrtype = $("#addrtype").attr("value");
	var septype = $("#septype").attr("value");
	var list = [];
	
	for (var x = 0; x < emails.length; ++x) {
		if (addrtype == "all") {
			list.push(emails[x]);
		} else if (addrtype == "gt0") {
			if (counts[x] > 0) {
				list.push(emails[x]);
			}
		} else if (addrtype == "eq0") {
			if (counts[x] == 0) {
				list.push(emails[x]);
			}
		} else {
			list.push("ERROR: invalid addrtype " + septype);
		}
	}
	
	var strlst = list.join(septype + " ");
	$("#botherlist").attr("value", strlst);
}
</script>
</head>
<body>

<div id="mainwrap">
	<link href="css/styles.css" rel="stylesheet" type="text/css">
	<img id="focsboxtitle" src="img/logo.png" alt="logotext" />
	
	<img src="img/head.png" alt=""><div id="maininner">
		<div id="content">
			<h2>Student Files</h2>
			
			<table class="stutable" border="0">
			<tr><th>Name</th><th>Files</th><th>Mail</th></tr>
			
			<?php
			for ($idx = 0; $idx < getNamesLen(); ++$idx) {
				$name = getName($idx);
				$fnames = getFilesList($idx);
				echo "<tr>";
				$stuclass = "student " + (count($fnames) == 0) ? "none" : "some";
				echo "<td class='$stuclass'>$name</td>";
				
				echo "<td>";
				if (count($fnames) == 0) {echo "&nbsp;";}
				for ($y = 0; $y < count($fnames); ++$y) {
					$fshort = getFileNameFromPath($fnames[$y]);
					echo "<div>$fshort</div>";
				}
				echo "</td>";
				
				echo "<td>";
				$mailaddr = getEmail($idx);
				echo "<a href='mailto:$mailaddr'>mail</a>";
				echo "</td>";
				
				echo "</tr>\n";
			}
			?>
			
			</table>
			
			<br>
			
			<h2>Email List</h2>
			<div>Creates an email list of those in the class based on how many files have been submitted.</div>
			<div>
				Address to:
				<select id="addrtype" onchange="emaillist()">
					<option value="all">everyone</option>
					<option value="gt0">&gt;0 files</option>
					<option value="eq0" selected="selected">0 files</option>
				</select>
				
				Separator:
				<select id="septype" onchange="emaillist()">
					<option value=",">comma</option>
					<option value=";" selected="selected">semicolon</option>
				</select>
			</div>
			<textarea id="botherlist">
			
			</textarea>
		</div>
	</div>
	<img src="img/foot.png" alt="">
</div>

</body>
</html>