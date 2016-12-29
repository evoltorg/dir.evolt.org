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
<strong class="current">View Link</strong>
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

$sql = "SELECT * FROM Links WHERE LinkID=$LinkID";
$results = $db->select($sql);

if(!empty($results)) {

	$sql2 = "SELECT * FROM LinkCats WHERE LinkID=$LinkID";
	$results2 = $db->select($sql2);

//	print "<pre>";
//	print_r($results2);
//	print "</pre>";

	while ( list ( $key,$val ) = each ($results)) {

		$LinkID		= stripslashes($val["LinkID"]);
		$Url		= stripslashes($val["Url"]);
		$LinkName	= stripslashes($val["LinkName"]);
		$Description	= stripslashes($val["Description"]);
		$SubmitName	= stripslashes($val["SubmitName"]);
		$SubmitEmail	= stripslashes($val["SubmitEmail"]);
		$SubmitDate	= stripslashes($val["SubmitDate"]);
		$Approved	= stripslashes($val["Approved"]);
		$Important	= stripslashes($val["Important"]);

		while ( list ( $key2,$val2 ) = each ($results2)) {
			$ID = stripslashes($val2["CatID"]);
			$CatName = $db->get_CatNames($ID);
			$LinkCats[] = "<a href=\"index.php?viewCat=$ID\">$CatName</a>";
		}
		$AllCats = implode(" and ",$LinkCats);

		$FormattedDate	= date("d/m/y",$SubmitDate);
		if ($Approved) $ApprovedCode = "<span style=\"color:green;font-weight:bold;\">Y</span>";
		else $ApprovedCode = "<span style=\"color:red;font-weight:bold;\">N</span>";

		if ($Important) $ImportantCode = "<span style=\"color:green;font-weight:bold;\">Y</span>";
		else $ImportantCode = "<span style=\"color:red;font-weight:bold;\">N</span>";

		print <<< EOF
<p>
<a href="links.php">Back</a> to Links Admin
</p>

<table border="0" cellspacing="0" cellpadding="0" class="admin">
 <tr class="odd">
  <td>Link Name:</td>
  <td>$LinkName</td>
 </tr>
 <tr class="even">
  <td>Categories:</td>
  <td>$AllCats</td>
 </tr>
 <tr class="odd">
  <td>URL:</td>
  <td><a href="$Url">$Url</a></td>
 </tr>
 <tr class="even">
  <td>Description:</td>
  <td>$Description</td>
 </tr>
 <tr class="odd">
  <td>Submitted by:</td>
  <td>$SubmitName &lt;<a href="mailto:$SubmitEmail">$SubmitEmail</a>&gt;</td>
 </tr>
 <tr class="even">
  <td>Submit Date:</td>
  <td>$FormattedDate</td>
 </tr>
 <tr class="odd">
  <td>Approved:</td>
  <td>$ApprovedCode</td>
 </tr>
 <tr class="even">
  <td>Important:</td>
  <td>$ImportantCode</td>
 </tr>
</table>

<p>
<a href="editlinks.php?LinkID=$LinkID">Edit</a> this entry<br>
<a href="deletelinks.php?LinkID=$LinkID">Delete</a> this entry<br>
</p>
EOF;



	}



}
else {
	print "That link could not be found!";
}
?>

</body>
</html>
