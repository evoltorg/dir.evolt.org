<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<?
global $db;
global $PHP_SELF;
include("/store/host/dir.evolt.org/includes/directoryAdmin.php");
?>
<html lang="en">
<head>
<title>The Web Design Resource List - Links Admin</title>

<style type="text/css" media="all">
<!--
/* hide stylesheet from older browsers */
@import url(../admin.css);
-->
</style>

<!-- link to print stylesheet -->
<link rel="stylesheet" type="text/css" media="print" href="print.css">

</head>
<!-- colours in the body tag, one of the few concessions to older browsers -->
<body bgcolor="#FFFFFF" text="#000000" link="#0033CC" vlink="#0033CC" alink="#99CCFF">

<div class="topband" align="center">
<h1 class="title">The Web Design Resource List</h1>
<form name="search" action="index.php" method="post" class="form">
<input class="textinput" type="text" name="KeyWords" size="30">
<input class="textsubmit" type="submit" name="Search" value=" Search ">
</form>
</div>

<div class="breadcrumbs">
<a href="index.php">Top</a> <strong>&#187;</strong> 
<a href="links.php">Links Admin</a> <strong>&#187;</strong> 
<strong class="current">Add a Link</strong>
</div>

<div class="smalllinks" align="right">
<small>
<a href="index.php" title="Site Admin Home.">admin</a> | 
<a href="links.php" title="Links Admin Home.">links</a> | 
<a href="categories.php" title="Categories Admin.">categories</a>
</small>
</div>

<?

$db = new MySQL;
if(!$db->init()) {
	echo "Well this sucks<BR>\n";
	exit;
}

$currentDate = time();
print <<<EOF
<form action="links.php" method="post">
<table border="0" cellpadding="2" cellspacing="2">
<tr><td align="right"><strong>CatIDs:</strong></td><td><input name="CatIDs" size="40" value=""></td></tr>
<tr><td align="right"><strong>URL:</strong></td><td><input name="Url" size="40" value="http://"></td></tr>
<tr><td align="right"><strong>Title:</strong></td><td><input name="LinkName" size="40"></td></tr>
<tr><td align="right"><strong>Description:</strong></td><td><textarea name="Description" rows="3" cols="40"></textarea></td></tr>
<tr><td align="right"><strong>Your Name:</strong></td><td><input name="SubmitName" size="40" value="Simon Coggins"></td></tr>
<tr><td align="right"><strong>Your Email:</strong></td><td><input name="SubmitEmail" size="40" value="ppxsjc1@nottingham.ac.uk"></td></tr>
<tr><td align="right"><strong>Datestamp:</strong></td><td><input name="Date" size="40" value="$currentDate"></td></tr>
<tr><td align="right"><strong>Approved:</strong></td><td><input type="checkbox" name="Approved" checked></td></tr>
<tr><td align="right"><strong>Important:</strong></td><td><input type="checkbox" name="Important"></td></tr>
<tr><td></td><td><input type="submit" name="addlink" value="Add Link">
<input type="reset" value=" Reset "></td></tr>
</table>
</form>
EOF;

?>

</body>
</html>
