<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<?
global $db;
global $PHP_SELF;
include("/store/host/dir.evolt.org/includes/directoryAdmin.php");
?>
<html lang="en">
<head>
<title>The Web Design Resource List - Category Admin</title>

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
<a href="categories.php">Category Admin</a> <strong>&#187;</strong> 
<strong class="current">Delete Category</strong>
</div>

<div class="smalllinks" align="right">
<small>
<a href="index.php" title="Site Admin Home.">admin</a> | 
<a href="links.php" title="Links Admin.">links</a> | 
<strong class="current">categories</strong>
</small>
</div>

<?

$db = new MySQL;
if(!$db->init()) {
	echo "Well this sucks<BR>\n";
	exit;
}

$sql = "SELECT * FROM Categories WHERE CatID=$CatID";

$results = $db->select($sql);

if(!empty($results)) {

	while ( list ( $key,$val ) = each ($results)) {

		$CatID		= stripslashes($val["CatID"]);
		$CatName	= stripslashes($val["CatName"]);
		$CatParent	= stripslashes($val["CatParent"]);
		$CatDesc	= stripslashes($val["CatDesc"]);
		$CatApproved	= stripslashes($val["CatApproved"]);
		$CatSubmitName	= stripslashes($val["CatSubmitName"]);
		$CatSubmitEmail	= stripslashes($val["CatSubmitEmail"]);
		$CatSubmitDate	= stripslashes($val["CatSubmitDate"]);

		$CatParentName = $db->get_CatNames($CatParent);

		$FormattedDate	= date("d/m/y",$CatSubmitDate);
		if ($CatApproved) $ApprovedCode = "<span style=\"color:green;font-weight:bold;\">Y</span>";
		else $ApprovedCode = "<span style=\"color:red;font-weight:bold;\">N</span>";

		print <<< EOF
<div class="message" align="center">
<strong>
Are you sure you want to delete the following category?
<form action="categories.php" method="post">
<input type="hidden" name="CatID" value="$CatID">
<input type="submit" name="Delete" value="Delete"> &nbsp;
<input type="submit" name="Delete" value="Cancel">
</form>
</strong>
</div>

<br>

<table class="admin" border="0" cellspacing="0" cellpadding="0">
 <tr class="odd">
  <td>Category Name:</td>
  <td><a href="index.php?viewCat=$CatID">$CatName</a></td>
 </tr>
 <tr class="even">
  <td>Category Parent:</td>
  <td><a href="index.php?viewCat=$CatParent">$CatParentName</a></td>
 </tr>
 <tr class="odd">
  <td>Description:</td>
  <td>$CatDesc</td>
 </tr>
 <tr class="even">
  <td>Submitted by:</td>
  <td>$CatSubmitName &lt;<a href="mailto:$CatSubmitEmail">$CatSubmitEmail</a>&gt;</td>
 </tr>
 <tr class="odd">
  <td>Submit Date:</td>
  <td>$FormattedDate</td>
 </tr>
 <tr class="even">
  <td>Approved:</td>
  <td>$ApprovedCode</td>
 </tr>
</table>

<p>
<a href="editcategories.php?CatID=$CatID">Edit</a> this entry<br>
</p>
EOF;


	}



}
else {
	print "That category could not be found!";
}
?>

</body>
</html>
