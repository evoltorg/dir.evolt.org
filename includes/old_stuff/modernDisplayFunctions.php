<?

function page_header($title="",$search_field="") {
	global $db;
	global $PHP_SELF;

	// if title is an integer generate breadcrumbs style title
	if (is_numeric($title)) {

		// run a recursive query to generate breadcrumbs trail array
		$db->get_ParentsInt($title);
		$path = $db->TRAIL;
		$title = "";
		if(!empty($path)) {
			// run through array to generate title string
			while (list($key,$val) = each($path)) {
				$CatName = stripslashes($val["CatName"]);
				$title = " &gt; $CatName$title";
			}
			$title = "Top$title";
		}
		else $title = "";
	}

	// if title is blank use default title
	if ($title == "") $title = "evolt.org Web Design Directory";

	// print the page header
print <<<EOF
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html lang="en">
<head>
<title>$title</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta name="description" content="The Web Design Resource List is a ever growing directory of resources of interest to web designers.">
<meta name="keywords" content="web, internet, computer, design, code, html, program, programming, links, list, directory, reference">
<meta name="author" content="Simon Coggins">
<meta name="robots" content="all">
<style type="text/css" media="all">
<!--
/* hide stylesheet from older browsers */
@import url(modern.css);
-->
</style>
<!-- link to print stylesheet -->
<link rel="stylesheet" type="text/css" media="print" href="print.css">
</head>
<!-- colours in the body tag, one of the few concessions to older browsers -->
<body bgcolor="#FFFFFF" text="#000000" link="#0033CC" vlink="#0033CC" alink="#99CCFF">
<div class="topband" align="center">
<h1 class="title">The Web Design Resource List</h1>
<form name="search" action="$PHP_SELF" method="post" class="form">
<input class="textinput" type="text" name="KeyWords" size="30">
<input class="textsubmit" type="submit" name="Search" value=" Search ">
</form>
</div>
<div class="breadcrumbs">
EOF;
}




function breadcrumbs($location="") {

	global $db;
	global $PHP_SELF;

	// if title is an integer generate breadcrumbs trail
	if (is_numeric($location)) {

		// run a recursive query to generate breadcrumbs trail array
		$db->get_ParentsInt($location);
		$path = $db->TRAIL;
		if(!empty($path)) {
			$location = "";
			$iscurrent = TRUE;
			// run through array to generate breadcrumbs string
			while ( list ( $key,$val ) = each ($path)) {
				$CatID		= stripslashes($val["CatID"]);
				$CatName	= stripslashes($val["CatName"]);

				if ($iscurrent) {
					$location = " <strong>&#187;</strong> \n<strong>$CatName</strong>$location";
				}
				else {
					$location = " <strong>&#187;</strong> \n<a href=\"$PHP_SELF?viewCat=$CatID\"><strong>$CatName</strong></a>$location";
				}
				$iscurrent = FALSE;
			}
		} else {
			$location = "";
		}
	}
	else if ($location != "") {
		// if location is not an integer, or blank format as a string
		$location = "<strong>&#187;</strong> <strong>$location</strong>";
	}


	// print the breadcrumbs trail
	print <<<EOF
<a href="$PHP_SELF"><strong>Top</strong></a> $location
EOF;
}




function print_error($type="") {

	if ($type == "connection") {
		print '
<div class="message" align="center">
<strong>
Connection error message.
</strong>
</div>
';
	}
	else if ($type == "noform") {
		print '
<div class="message" align="center">
<strong>
Invalid form request.
</strong>
</div>
';
	}
	else {
		print '
<div class="message" align="center">
<strong>
Unspecified error message.
</strong>
</div>
';

	}
}


function suggest($post_variables="") {
	global $db;
	global $PHP_SELF;

	if(!$db->suggest($post_variables)) {
		print <<<EOF
<div class="message" align="center"><strong>Suggestion failed! Required data missing, invalid, or you duplicated an existing entry.</strong></div><br>
EOF;
	} else {
		print <<<EOF
<div class="message" align="center"><strong>Suggestion submitted for approval.</strong></div><br>
EOF;
	}
}

function suggestCat($post_variables="") {
	global $db;
	global $PHP_SELF;

	if(!$db->suggestCat($post_variables)) {
		print <<<EOF
<div class="message" align="center"><strong>Suggestion failed! Required data missing, invalid, or you duplicated an existing entry.</strong></div><br>
EOF;
	} else {
		print <<<EOF
<div class="message" align="center"><strong>Suggestion submitted for approval.</strong></div><br>
EOF;
	}
}

function print_info($info="") {
	if ($info == "about") {
		include("../../includes/about.inc");
	}
	else if ($info == "guidelines") {
		include("../../includes/guidelines.inc");
	}
}

function print_top() {
	global $PHP_SELF;
	global $db;

	// number of subcategories to include for each category
	$breaknum = 3;

	// get all top level categories
	$data = $db->get_Cats();

	$totalCats = count($data);

	if (!empty($data)) {
		print "\n\n<table width=\"80%\" class=\"top\" align=center border=0 cellpadding=5 cellspacing=0>\n";
		for($i=0;$i<$totalCats;$i++) {
			$CatID = stripslashes($data[$i]["CatID"]);
			$CatName = stripslashes($data[$i]["CatName"]);

			if ($i/2 == round($i/2)) {
				print " <tr>\n";
			}

			print "  <td valign=\"top\">\n";
			print "  <h3><a href=\"$PHP_SELF?viewCat=$CatID\">$CatName</a></h3>\n";

			$subcats = $db->get_Cats($CatID);

			if(!empty($subcats)) {
				for ($j=0;$j<$breaknum;$j++) {
					if ($j<count($subcats)) {
						$SubCatID = stripslashes($subcats[$j]["CatID"]);
						$SubCatName = stripslashes($subcats[$j]["CatName"]);
						print "  <a href=\"$PHP_SELF?viewCat=$SubCatID\">$SubCatName</a>";
						// print commas where appropriate
						if ($j != $breaknum - 1 && $j < count($subcats)-1) print ", \n";
					}
				}
				// print dots if subcategories are truncated
				if ($j < count($subcats)) print "...";
			}

			print "\n  </td>\n";
			if ($i/2 != round($i/2)) {
				print " </tr>\n";
			}
		}
		print "</table>\n\n";
	}
}




function print_form($type="",$parent_cat="") {
	global $db;
	global $PHP_SELF;

	if("$parent_cat" == "top") { $parent_cat = 0; }
	$CatName = stripslashes($db->get_CatNames($parent_cat));
	if(empty($CatName)) { $CatName = "Top"; }

	if ($type == "link" AND !empty($parent_cat)) {

		// print an add a link form
		print <<<EOF
<div class="message" align="center"><strong>Add a link to: $CatName</strong></div><br>
<p>
To add a new link in the category <strong>$CatName</strong>, please fill in the form 
below. To add a link to a different category, click on "Suggest new link" 
at the bottom of the relevant category page.
</p>
<p>
The fields labelled in red are required. A one line 
description of the site is also appreciated. If this is your first 
suggestion, please read the <a href="$PHP_SELF?Info=guidelines">submission 
guidelines</a> before proceeding.
</p>
<form action="$PHP_SELF" method="post">
<input type="hidden" name="CatID" value="$parent_cat">
<table border="0" cellpadding="2" cellspacing="2">
<tr><td align="right"><strong style="color: red;">URL:</strong></td><td><input name="Url" size="40" VALUE="http://"></td></tr>
<tr><td align="right"><strong style="color: red;">Title:</strong></td><td><input name="LinkName" size="40"></td></tr>
<tr><td align="right"><strong>Description:</strong></td><td><textarea name="Description" rows="3" cols="40"></textarea></td></tr>
<tr><td align="right"><strong>Your Name:</strong></td><td><input name="SubmitName" size="40"></td></tr>
<tr><td align="right"><strong>Your Email:</strong></td><td><input name="SubmitEmail" size="40"></td></tr>
<tr><td></td><td><input type="submit" name="suggest" value="Submit Resource"></td></tr>
</table>
</form>
EOF;
	}
	elseif ($type == "category" AND !empty($parent_cat)) {

		// print an add a category form
		print <<<EOF
	<div class="message" align="center"><strong>Add a Sub Category to: $CatName</strong></div><br>
	<p>
	To add a sub category inside the category <strong>$CatName</strong>, please fill in 
	the form below. To add a sub category to a different category, click on "Suggest 
	new category" at the bottom of the relevant category page.
	</p>
	<p>
	The field labelled in red is required. A one line 
	description of the category is also appreciated. If this is your first 
	suggestion, please read the <a href="guidelines.php">submission 
	guidelines</a> before proceeding.
	</p>

	<form action="$PHP_SELF" method="post">
	<input type="hidden" name="CatID" value="$parent_cat">
	<table border="0" cellpadding="2" cellspacing="2">
	<tr><td align="right" style="color: red;"><strong>Category Title:</strong></td><td><input name="CatName" size="40"></td></tr>
	<tr><td align="right"><strong>Description:</strong></td><td><textarea name="CatDesc" rows="3" cols="40"></textarea></td></tr>
	<tr><td align="right"><strong>Your Name:</strong></td><td><input name="CatSubmitName" size="40"></td></tr>
	<tr><td align="right"><strong>Your Email:</strong></td><td><input name="CatSubmitEmail" size="40"></td></tr>
	<tr><td></td><td><input type="submit" name="suggestCat" value="Submit Resource"></td></tr>
	</table>
EOF;

	}
	else {
		// print an error
		print_error("noform");
	}
}




function links($CatID="") {

	global $PHP_SELF;
	global $db;

	$links	= $db->get_Links($CatID);

	// do nothing if CatID is blank
	if(!empty($CatID))
	{
		$currentID = $CatID;
		$currentName = $db->get_CatNames($CatID);

	} else {
		return;
	}

	// print category links
	if(!empty($links)) {
		print "<div class=\"links\">\n";

		print "<h2>$currentName:</h2>\n\n";

		while ( list ( $key,$val ) = each ($links)) {
			$Url		= stripslashes($val["Url"]);
			$LinkName	= stripslashes($val["LinkName"]);
			$Desc		= stripslashes($val["Description"]);
			print "<a href=\"$Url\">$LinkName</a> - $Desc<br>";
		}

		print "\n\n</div>\n\n";

	}

}




function subcategories($CatID="") {

	global $PHP_SELF;
	global $db;

	$data	= $db->get_Cats($CatID);

	// print top level categories if CatID is blank
	if(!empty($CatID))
	{
		$currentID = $CatID;
		$currentName = $db->get_CatNames($CatID);

	} else {
		print_top();
		return;
	}

	// print any category description
	$desc = $db->get_Desc($CatID);
	print "<p>\n$desc\n</p>\n\n";

	// print subcategories
	if(!empty($data)) {

		print "<div class=\"cats\">\n";
		print "<h2 class=\"subcats\">Sub Categories:</h2>\n";
		print "<ul>\n";
		while ( list ( $key,$val ) = each ($data)) {
			$CatID = stripslashes($val["CatID"]);
			$CatName = stripslashes($val["CatName"]);
			print "<li><a href=\"$PHP_SELF?viewCat=$CatID\"><strong>$CatName</strong></a></li>\n";
		}
		print "</ul>\n";
		print "</div>\n\n";
	}

}


function search_results($KeyWords="") {
	global $db;
	global $PHP_SELF;

	$hits = $db->search($KeyWords);
	if((!$hits) or (empty($hits))) {
		print <<<EOF
<div class="message" align="center"><strong>No matches.</strong></div><br>
EOF;
	} else {


	// re-form results array to a more useful form

	// create new array
	$search_out = array("LinkID" => array(),"CatID" => array(),"Url" => array(),"LinkName" => array(),"Description" => array());

		// loop through matches
		while ( list ($key,$hit) = each ($hits)) {
			if(!empty($hit)) {
				if (!in_array($hit["LinkID"],$search_out["LinkID"])) {
					// new value, so append to $search_out
					$search_out["CatID"][] = $hit["CatID"];
					$search_out["LinkID"][] = $hit["LinkID"];
					$search_out["Url"][] = $hit["Url"];
					$search_out["LinkName"][] = $hit["LinkName"];
					$search_out["Description"][] = $hit["Description"];
				}
				else {
					// existing value

					// find correct location
					for ($j=0;$j<count($search_out["LinkID"]);$j++) {
						if ($hit["LinkID"] == $search_out["LinkID"][$j]) $mark = $j;
					}

					// see if this slot is an array already
					if (!is_array($search_out["CatID"][$mark])) {
						// make it an array with existing value in slot zero
						$tempvalue = $search_out["CatID"][$mark];
						$search_out["CatID"][$mark] = array();
						$search_out["CatID"][$mark][] = $tempvalue;
					}

					// add value to the array
					array_push($search_out["CatID"][$mark],$hit["CatID"]);
				}
			}
		}

		$total = count($search_out["LinkID"]);
		$title = "Search Results";
		if ($total == 1) $msg = "Search returned [$total] match";
		else $msg = "Search returned [$total] matches";

		print "<div class=\"message\" align=\"center\">\n";
		print "<strong>\n";
		print "$msg\n";
		print "</strong>\n";
		print "</div>\n";

		print "<ul class=\"searchresults\">\n";

		// output results
		for ($i=0;$i<count($search_out["LinkID"]);$i++) {
			$LinkName = $db->get_LinkName($search_out["LinkID"][$i]);

			print "<li>\n<strong><a href=\"" . $search_out["Url"][$i] . "\">";
			print $LinkName . "</a></strong>\n<br>\n";
			print stripslashes($search_out["Description"][$i]) . "\n<br>\n";
			print "<small>Found in: ";

			if (!is_array($search_out["CatID"][$i])) {
				// output single Category match
				$CatName = $db->get_CatNames($search_out["CatID"][$i]);
				echo "<a href=\"" . $PHP_SELF . "?viewCat=" . $search_out["CatID"][$i] . "\">";
				echo "$CatName";
				echo "</a>\n<br>\n";
			}
			else {
				// output multiple Category matches
				$CatName = $db->get_CatNames($search_out["CatID"][$i][0]);
				echo "<a href=\"" . $PHP_SELF . "?viewCat=" . $search_out["CatID"][$i][0] . "\">";
				echo $CatName;
				echo "</a>";
				for ($j=1;$j<count($search_out["CatID"][$i]);$j++) {
					$CatName = $db->get_CatNames($search_out["CatID"][$i][$j]);
					echo " and ";
					echo "<a href=\"" . $PHP_SELF . "?viewCat=" . $search_out["CatID"][$i][$j] . "\">";
					echo $CatName;
					echo "</a>";
				}
			}
			echo "</small>\n</li>\n\n";
		}
	print "</ul>\n\n";
	}
}


function options($choices="") {
	global $PHP_SELF;
	if (!empty($choices)) {
		print "<div class=\"smalllinks\" align=\"right\">\n";
		print "<small>\n ";
		print "<a href=\"index.php\">evolt skin</a> | \n";

		$choices_array = explode(",",$choices);

		if (in_array("about",$choices_array)) {
			print "<a href=\"$PHP_SELF?Info=about\" title=\"Information about this site.\">about</a> |\n";
		}

		if (in_array("guidelines",$choices_array)) {
			print "<a href=\"$PHP_SELF?Info=guidelines\" title=\"Guidelines for link and category suggestions.\">guidelines</a>\n";
		}

		print "</small>\n";
		print "</div>\n";

	}
}


function suggest_links($CatID="") {
	global $PHP_SELF;

	// print the suggest links
	print <<<EOF
<p align="center">
<a href="$PHP_SELF?add=$CatID">Suggest new link</a> | 
<a href="$PHP_SELF?addCat=$CatID">Suggest new category</a>
</p>
EOF;
}

function evolt_logo() {
	print "<a href=\"http://www.evolt.org/\"><img src=\"evolt_logo.gif\" vspace=\"5\" border=\"0\" width=\"134\" height=\"50\" alt=\"evolt.org\" align=\"right\"></a>\n";
}

function page_footer() {
	// print the page footer
	print <<<EOF
<!--begin page_footer-->
</body>
</html>
EOF;
}

function start_main() {
	print <<<EOF
</div>
<!--end breadcrumbs-->

<!--begin Start_main-->

<!--end Start_main-->
EOF;
}

?>
