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
<strong class="current">Category Admin</strong>
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

if ($Delete == "Delete" && !empty($CatID)) {
	// need to write delete function
	$sql = "DELETE FROM Categories WHERE CatID=$CatID";
	$isdeleted = $db->delete($sql);

	if ($isdeleted) {
		print <<<EOF
		<div class="message" align="center">
		<strong>
		Category Deleted.
		</strong>
		</div>
EOF;
	}
	else {
		print <<<EOF
		<div class="message" align="center">
		<strong>
		There was a problem deleting that category.
		</strong>
		</div>
EOF;
	}
}

if ($addcategory == "Add Category") {
	$CatID = $HTTP_POST_VARS["CatID"];
	$CatName = $HTTP_POST_VARS["CatName"];
	$CatParent = $HTTP_POST_VARS["CatParent"];
	$CatDesc = $HTTP_POST_VARS["CatDesc"];
	$CatApproved = $HTTP_POST_VARS["CatApproved"];
	$CatSubmitName = $HTTP_POST_VARS["CatSubmitName"];
	$CatSubmitEmail = $HTTP_POST_VARS["CatSubmitEmail"];
	$CatSubmitDate = $HTTP_POST_VARS["CatSubmitDate"];

	if(!empty($CatApproved)) $CatApproved = 1;
	else $CatApproved = 0;

	if(empty($CatName)) { return false; }
	if(empty($CatSubmitName)) { $CatSubmitName = "Anonymous"; }
	if(empty($CatSubmitEmail)) { $CatSubmitEmail = "Not given"; }

	$sql = "INSERT INTO Categories ";
	$sql .= "(CatName,CatParent,CatDesc,CatApproved,CatSubmitName,CatSubmitEmail,CatSubmitDate) ";
	$sql .= "values ";
	$sql .= "('$CatName','$CatParent','$CatDesc','$CatApproved','$CatSubmitName','$CatSubmitEmail',$CatSubmitDate) ";

	$results = $db->insert($sql);
	if ($results) {
	print <<<EOF
		<div class="message" align="center">
		<strong>
		New Category added.
		</strong>
		</div>
EOF;
	}
	else {
		print <<<EOF
		<div class="message" align="center">
		<strong>
		There was a problem adding that category.
		</strong>
		</div>
EOF;
	}
}

if ($editlink == "Update Link") {
	// try to update link here

	$CatID = $HTTP_POST_VARS["CatID"];
	$CatName = $HTTP_POST_VARS["CatName"];
	$CatParent = $HTTP_POST_VARS["CatParent"];
	$CatDesc = $HTTP_POST_VARS["CatDesc"];
	$CatApproved = $HTTP_POST_VARS["CatApproved"];
	$CatSubmitName = $HTTP_POST_VARS["CatSubmitName"];
	$CatSubmitEmail = $HTTP_POST_VARS["CatSubmitEmail"];
	$CatSubmitDate = $HTTP_POST_VARS["CatSubmitDate"];

	if(!empty($CatApproved)) $CatApproved = 1;
	else $CatApproved = 0;

	if(empty($CatName)) { return false; }
	if(empty($CatSubmitName)) { $CatSubmitName = "Anonymous"; }
	if(empty($CatSubmitEmail)) { $CatSubmitEmail = "Not given"; }

	$sql = "UPDATE Categories SET ";
	$sql .= "CatID='$CatID',CatName='$CatName',CatApproved='$CatApproved',CatDesc='$CatDesc',CatSubmitName='$CatSubmitName',CatSubmitEmail='$CatSubmitEmail',CatSubmitDate=$CatSubmitDate ";
	$sql .= "WHERE CatID=$CatID";
	$updated = $db->update($sql);

	if ($updated) {
		print <<<EOF
		<div class="message" align="center">
		<strong>
		Category updated.
		</strong>
		</div>
EOF;
	}
	else {
		print <<<EOF
		<div class="message" align="center">
		<strong>
		There was a problem editing that category.
		</strong>
		</div>
EOF;
	}
}

?>

<p>
<a href="newcategories.php">Create</a> a new category<br>
<a href="<?=$PHP_SELF?>">Reload</a> this page
</p>
<?

$max_per_page = 20;

$total = count($db->select("SELECT * FROM Categories"));

if (empty($record) || $record < 1) $record = 1;

$offset = $record - 1;
$sql = "SELECT * FROM Categories 
	ORDER BY CatSubmitDate DESC 
	LIMIT $offset,$max_per_page";

$results = $db->select($sql);

$records_this_page = count($results);

$this_page_first = $record;
$this_page_last = $record + $records_this_page - 1;

$next_page_first = $this_page_last + 1;
$prev_page_first = $this_page_first - $max_per_page;

if(!empty($results)) {

	print "<p>\n";
	print "<strong>Categories:</strong> (showing $this_page_first-$this_page_last of $total)\n";
	print "</p>\n";

	if ($records_this_page < $total) {
		print "<p>\n";
		// print previous category if required
		if ($this_page_first > 1) print "<strong><a href=\"$PHP_SELF?record=$prev_page_first\">previous</a></strong> ";
	
		// print numbered categories to remaining pages
		for ($i=1;$i<=ceil($total/$max_per_page);$i++) {
			if ($record > ($i-1)*$max_per_page && $record <= $i*$max_per_page) {
				print "<strong>$i</strong> ";
			}
			else {
				$next = ($i-1)*$max_per_page+1;
				print "<a href=\"$PHP_SELF?record=$next\">$i</a> ";
			}
		}
	
		// print next category if required
		if ($this_page_last < $total) print "<strong><a href=\"$PHP_SELF?record=$next_page_first\">next</a></strong>";
		print "</p>\n";
	}

	print "<table class=\"admin\" border=\"0\" cellspacing=\"0\" cellpadding=\"3\">\n";
	print " <tr>\n";
	print "  <th align=\"left\">Name</th>\n";
	print "  <th align=\"left\">Parent</th>\n";
	print "  <th align=\"left\">Date</th>\n";
	print "  <th colspan=\"3\">Options</th>\n";
	print " </tr>\n";

	$isodd = TRUE;
	while ( list ( $key,$val ) = each ($results)) {

		$CatID		= stripslashes($val["CatID"]);
		$CatName	= stripslashes($val["CatName"]);
		$CatParent	= stripslashes($val["CatParent"]);
		$CatDesc	= stripslashes($val["CatDesc"]);
		$CatSubmitDate	= stripslashes($val["CatSubmitDate"]);

		$FormattedDate	= date("d/m/y",$CatSubmitDate);

		if ($isodd) {
			$rowclass = "odd";
			$isodd = FALSE;
		}
		else {
			$rowclass = "even";
			$isodd = TRUE;
		}

		$CatParentName = $db->get_CatNames($CatParent);

		print " <tr class=\"$rowclass\">\n";
		print "  <td><a href=\"viewcategories.php?CatID=$CatID\">$CatName</a></td>\n";
		print "  <td><a href=\"index.php?viewCat=$CatParent\">$CatParentName</a></td>\n";
		print "  <td>$FormattedDate</td>\n";
		print "  <td><a href=\"index.php?viewCat=$CatID\">visit</a></td>\n";
		print "  <td><a href=\"editcategories.php?CatID=$CatID\">edit</a></td>\n";
		print "  <td><a href=\"deletecategories.php?CatID=$CatID\">delete</a></td>\n";
		print " </tr>\n";
	}

	print "</table>\n";

	if ($records_this_page < $total) {
		print "<p>\n";
		// print previous link if required
		if ($this_page_first > 1) print "<strong><a href=\"$PHP_SELF?record=$prev_page_first\">previous</a></strong> ";
	
		// print numbered links to remaining pages
		for ($i=1;$i<=ceil($total/$max_per_page);$i++) {
			if ($record > ($i-1)*$max_per_page && $record <= $i*$max_per_page) {
				print "<strong>$i</strong> ";
			}
			else {
				$next = ($i-1)*$max_per_page+1;
				print "<a href=\"$PHP_SELF?record=$next\">$i</a> ";
			}
		}
	
		// print next link if required
		if ($this_page_last < $total) print "<strong><a href=\"$PHP_SELF?record=$next_page_first\">next</a></strong>";
		print "</p>\n";
	}

}
else {
	print "No matches!";
}
?>

</body>
</html>
