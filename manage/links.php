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
<strong class="current">Links Admin</strong>
</div>

<div class="smalllinks" align="right">
<small>
<a href="index.php" title="Site Admin Home.">admin</a> | 
<strong class="current">links</strong> | 
<a href="categories.php" title="Categories Admin.">categories</a>
</small>
</div>

<?

$db = new MySQL;
if(!$db->init()) {
	echo "Well this sucks<BR>\n";
	exit;
}

if ($Delete == "Delete" && !empty($LinkID)) {
	// need to write delete function
	$sql = "DELETE FROM Links WHERE LinkID=$LinkID";
	$isdeleted = $db->delete($sql);
	$sql2 = "DELETE FROM LinkCats WHERE LinkID=$LinkID";
	$isdeleted2 = $db->delete($sql2);

	if ($isdeleted && $isdeleted2) {
		print <<<EOF
		<div class="message" align="center">
		<strong>
		Link Deleted.
		</strong>
		</div>
EOF;
	}
	else {
		print <<<EOF
		<div class="message" align="center">
		<strong>
		There was a problem deleting that link.
		</strong>
		</div>
EOF;
	}
}

if ($addlink == "Add Link") {
	$CatIDs = $HTTP_POST_VARS["CatIDs"];
	$Url = $HTTP_POST_VARS["Url"];
	$Description = $HTTP_POST_VARS["Description"];
	$LinkName = $HTTP_POST_VARS["LinkName"];
	$SubmitName = $HTTP_POST_VARS["SubmitName"];
	$SubmitEmail = $HTTP_POST_VARS["SubmitEmail"];
	$ExtraCats = $HTTP_POST_VARS["ExtraCats"];
	$SubmitDate = $HTTP_POST_VARS["Date"];
	$Approved = $HTTP_POST_VARS["Approved"];
	$Important = $HTTP_POST_VARS["Important"];

	if(!empty($Approved)) $Approved = 1;
	else $Approved = 0;
	if(!empty($Important)) $Important = 1;
	else $Important = 0;

	if(empty($Url)) { return false; }
	if(empty($LinkName)) { return false; }
	if(empty($SubmitName)) { $SubmitName = "Anonymous"; }
	if(empty($SubmitEmail)) { $SubmitEmail = "Not given"; }
	$CatIDarray = explode(" ",$CatIDs);

	$sql = "INSERT INTO Links ";
	$sql .= "(Url,LinkName,Description,SubmitName,SubmitEmail,SubmitDate,Approved,Important) ";
	$sql .= "values ";
	$sql .= "('$Url','$LinkName','$Description','$SubmitName','$SubmitEmail',$SubmitDate,$Approved,$Important) ";
	$results = $db->insert($sql);

	foreach ($CatIDarray as $thisID) {
		$sql2 = "INSERT INTO LinkCats ";
		$sql2 .= "(LinkID,CatID) ";
		$sql2 .= "values ";
		$sql2 .= "('$results','$thisID') ";
		$results2 = $db->insert($sql2);
	}

	if ($results) {
	print <<<EOF
		<div class="message" align="center">
		<strong>
		New link added.
		</strong>
		</div>
EOF;
	}
	else {
		print <<<EOF
		<div class="message" align="center">
		<strong>
		There was a problem adding that link.
		</strong>
		</div>
EOF;
	}
}

if ($editlink == "Update Link") {
	// try to update link here

	$CatIDs = $HTTP_POST_VARS["CatIDs"];
	$Url = $HTTP_POST_VARS["Url"];
	$Description = $HTTP_POST_VARS["Description"];
	$LinkName = $HTTP_POST_VARS["LinkName"];
	$SubmitName = $HTTP_POST_VARS["SubmitName"];
	$SubmitEmail = $HTTP_POST_VARS["SubmitEmail"];
	$ExtraCats = $HTTP_POST_VARS["ExtraCats"];
	$SubmitDate = $HTTP_POST_VARS["Date"];
	$Approved = $HTTP_POST_VARS["Approved"];
	$Important = $HTTP_POST_VARS["Important"];

	if(!empty($Approved)) $Approved = 1;
	else $Approved = 0;
	if(!empty($Important)) $Important = 1;
	else $Important = 0;

	if(empty($Url)) { return false; }
	if(empty($LinkName)) { return false; }
	if(empty($SubmitName)) { $SubmitName = "Anonymous"; }
	if(empty($SubmitEmail)) { $SubmitEmail = "Not given"; }
	$CatIDarray = explode(" ",$CatIDs);

	$sql = "UPDATE Links SET ";
	$sql .= "Url='$Url',LinkName='$LinkName',Description='$Description',SubmitName='$SubmitName',SubmitEmail='$SubmitEmail',SubmitDate=$SubmitDate,Approved=$Approved,Important=$Important ";
	$sql .= "WHERE LinkID=$LinkID";
	$updated = $db->update($sql);

	// update the LinkCats table by deleted and recreating entries
	$sql2 = "DELETE FROM LinkCats WHERE LinkID=$LinkID";
	$isdeleted2 = $db->delete($sql2);
	foreach ($CatIDarray as $thisID) {
		$sql3 = "INSERT INTO LinkCats ";
		$sql3 .= "(LinkID,CatID) ";
		$sql3 .= "values ";
		$sql3 .= "('$LinkID','$thisID') ";
		$results2 = $db->insert($sql3);
	}

	if ($updated) {
		print <<<EOF
		<div class="message" align="center">
		<strong>
		Link updated.
		</strong>
		</div>
EOF;
	}
	else {
		print <<<EOF
		<div class="message" align="center">
		<strong>
		There was a problem editing that link.
		</strong>
		</div>
EOF;
	}
}

?>

<p>
<a href="newlink.php">Create</a> a new link<br>
<a href="<?=$PHP_SELF?>">Reload</a> this page
</p>
<?

$max_per_page = 20;

$total = count($db->select("SELECT * FROM Links"));

if (empty($record) || $record < 1) $record = 1;

$offset = $record - 1;
$sql = "SELECT * FROM Links 
	ORDER BY SubmitDate DESC 
	LIMIT $offset,$max_per_page";

$results = $db->select($sql);

$records_this_page = count($results);

$this_page_first = $record;
$this_page_last = $record + $records_this_page - 1;

$next_page_first = $this_page_last + 1;
$prev_page_first = $this_page_first - $max_per_page;

if(!empty($results)) {

	print "<p>\n";
	print "<strong>Links:</strong> (showing $this_page_first-$this_page_last of $total)\n";
	print "</p>\n";

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

	print "<table class=\"admin\" border=\"0\" cellspacing=\"0\" cellpadding=\"3\">\n";
	print " <tr>\n";
	print "  <th align=\"left\">Title</th>\n";
	print "  <th align=\"left\">Description</th>\n";
	print "  <th align=\"left\">Date</th>\n";
	print "  <th colspan=\"3\">Options</th>\n";
	print " </tr>\n";

	$isodd = TRUE;
	while ( list ( $key,$val ) = each ($results)) {

		$LinkID		= stripslashes($val["LinkID"]);
		$Url		= stripslashes($val["Url"]);
		$LinkName	= stripslashes($val["LinkName"]);
		$Description	= stripslashes($val["Description"]);
		$SubmitDate	= stripslashes($val["SubmitDate"]);

		$FormattedDate	= date("d/m/y",$SubmitDate);

		if ($isodd) {
			$rowclass = "odd";
			$isodd = FALSE;
		}
		else {
			$rowclass = "even";
			$isodd = TRUE;
		}

		// truncate description to a certain maximum length
		$truncate = 40;
		if (strlen($Description) > $truncate) $ShortDesc = substr($Description, 0, $truncate) . "...";
		else $ShortDesc = $Description;
	
		print " <tr class=\"$rowclass\">\n";
		print "  <td><a href=\"viewlinks.php?LinkID=$LinkID\">$LinkName</a></td>\n";
		print "  <td>$ShortDesc</td>\n";
		print "  <td>$FormattedDate</td>\n";
		print "  <td><a href=\"$Url\">visit</a></td>\n";
		print "  <td><a href=\"editlinks.php?LinkID=$LinkID\">edit</a></td>\n";
		print "  <td><a href=\"deletelinks.php?LinkID=$LinkID\">delete</a></td>\n";
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
