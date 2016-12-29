<?

include("includes/evoltSQL.php");
include("includes/evoltDisplayFunctions.php");

if ($HTTP_POST_VARS["suggest"] && $HTTP_POST_VARS["remember"]) {
	// set cookies containing name and email if requested
	setcookie ("author", stripslashes($HTTP_POST_VARS["author"]),time()+60*60*24*365);
	setcookie ("email", stripslashes($HTTP_POST_VARS["email"]),time()+60*60*24*365);
}
elseif ($HTTP_POST_VARS["suggest"] && !$HTTP_POST_VARS["remember"]) {
	// otherwise delete the cookies
	setcookie ("author");
	setcookie ("email");
}

include("includes/suggest_header.php");

$db = new MySQL;
$dblink = $db->init();

function showform($errors="") {
	global $HTTP_POST_VARS;
	global $HTTP_GET_VARS;
	global $HTTP_COOKIE_VARS;
	global $db;

	// get categories from database
	$sql = "select CatID,CatName from Categories where CatApproved='1' order by CatName";
	$categories = $db->select($sql);

	// get form variables sent to the form
	if ($HTTP_POST_VARS) {
		// from a previous form submission
		$url = trim(htmlspecialchars(stripslashes($HTTP_POST_VARS["url"])));
		$title = trim(htmlspecialchars(stripslashes($HTTP_POST_VARS["title"])));
		$author = trim(htmlspecialchars(stripslashes($HTTP_POST_VARS["author"])));
		$email = trim(htmlspecialchars(stripslashes($HTTP_POST_VARS["email"])));
		$desc = trim(htmlspecialchars(stripslashes($HTTP_POST_VARS["desc"])));
		if ($HTTP_POST_VARS["remember"] == 1) {
			$isticked = " checked";
		}
		else {
			$isticked = "";
		}
	}
	elseif($HTTP_GET_VARS) {
		// from the bookmarklet
		$url = trim(htmlspecialchars(stripslashes($HTTP_GET_VARS["url"])));
		$title = trim(htmlspecialchars(stripslashes($HTTP_GET_VARS["title"])));
		$author = trim(htmlspecialchars(stripslashes($HTTP_GET_VARS["author"])));
		$email = trim(htmlspecialchars(stripslashes($HTTP_GET_VARS["email"])));
		$desc = trim(htmlspecialchars(stripslashes($HTTP_GET_VARS["desc"])));
		if($HTTP_COOKIE_VARS["author"] OR $HTTP_COOKIE_VARS["email"]) {
			$isticked = " checked";
		}
		else {
			$isticked = "";
		}
	}

	// if author and email aren't set, check cookies for values
	if (empty($author)) {
		// from the cookies
		$author = trim(htmlspecialchars(stripslashes($HTTP_COOKIE_VARS["author"])));
	}

	if (empty($email)) {
		// from the cookies
		$email = trim(htmlspecialchars(stripslashes($HTTP_COOKIE_VARS["email"])));
	}

	print "<form action=\"$PHP_SELF\" method=\"post\">";
	print "<table border=\"0\" cellpadding=\"2\" cellspacing=\"2\">";

	print "<tr><td>&nbsp;</td><td>\n";
	if (!empty($errors)) {
		print "One or more fields have not been completed correctly. Please make the necessary alterations then try again.";
	}
	else {
		print "Add a link to the evolt.org web design and development directory:\n";
	}
	print "<br><br>\n";
	print "Required fields are indicated with <strong>[square brackets]</strong>";
	print "<br><br>\n";
	print "</td></tr>\n";

	if (@in_array("no_url",$errors)) {
		print "<tr><td>&nbsp;</td><td>\n";
		print "<strong>You must enter a url:</strong>";
		print "</td></tr>\n";
	}
	print "<tr><td align=\"right\"><strong>[URL:]</strong></td><td><input name=\"url\" size=\"40\" VALUE=\"$url\"></td></tr>";
	if (@in_array("no_title",$errors)) {
		print "<tr><td>&nbsp;</td><td>\n";
		print "<strong>You must enter a title:</strong>";
		print "</td></tr>\n";
	}
	print "<tr><td align=\"right\"><strong>[Title:]</strong></td><td><input name=\"title\" size=\"40\" value=\"$title\"></td></tr>";
	print "<tr><td align=\"right\" valign=\"top\"><strong>Description:</strong></td><td><textarea name=\"desc\" rows=\"3\" cols=\"40\">$desc</textarea></td></tr>";
	print "<tr><td>&nbsp;</td><td>\n";
	print "<small><strong>Tip:</strong> In most browsers, any text selected will appear in the description field.</small>\n";
	print "</td></tr>\n";

	if (@in_array("no_cat",$errors)) {
		print "<tr><td>&nbsp;</td><td>\n";
		print "<strong>You must select at least one category to submit this link to.</strong>";
		print "</td></tr>\n";
	}

	print "<tr><td align=\"right\" valign=\"top\"><strong>[Categories:]</strong></td><td>";

	print "<small>(To select more than one hold down the control key while clicking)</small>\n<br>\n";
	print "<select name=\"CatID[]\" size=\"8\" multiple>\n";
	foreach ($categories as $category) {
		print " <option value=\"";
		print $category["CatID"];
		print "\"";
		if (@in_array($category["CatID"],$HTTP_POST_VARS["CatID"])) {
			print " selected";
		}
		print ">";
		print $category["CatName"];
		print "</option>\n";
	}
	print "</select>\n";
	print "</td></tr>";


	print "<tr><td align=\"right\"><strong>Your Name:</strong></td><td><input name=\"author\" size=\"40\" value=\"$author\"></td></tr>";
	print "<tr><td align=\"right\"><strong>Your Email:</strong></td><td><input name=\"email\" size=\"40\" value=\"$email\"></td></tr>";
	print "<tr><td align=\"right\" valign=\"top\"><strong>Save Name:</strong></td><td>\n";
	print "<input type=\"checkbox\" name=\"remember\" value=\"1\"$isticked><br>\n";
	print "<small>(Uses cookies to remember your name and email address for next time)</small>\n";
	print "</td></tr>\n";
	print "<tr><td></td><td><input type=\"submit\" name=\"suggest\" value=\"Submit Resource\"></td></tr>";
	print "</table>";
	print "</form>";
}

function validateform() {

	global $PHP_SELF;
	global $HTTP_POST_VARS;

	// make sure they have entered a title
	if (!$HTTP_POST_VARS["title"]) {
		$errors[] = "no_title";
	}

	// make sure they have entered a url
	if (!$HTTP_POST_VARS["url"]) {
		$errors[] = "no_url";
	}

	// make sure they have selected at least one category
	if (!$HTTP_POST_VARS["CatID"]) {
		$errors[] = "no_cat";
	}


	// return validation results
	if ($errors) {
		return array (FALSE,$errors);
	}
	else {
		return array (TRUE,$errors);
	}

}


function showresults() {
	global $db;
	global $PHP_SELF;
	global $HTTP_POST_VARS;

	$CatID = $HTTP_POST_VARS["CatID"];
	$url = trim(htmlspecialchars(stripslashes($HTTP_POST_VARS["url"])));
	$title = trim(htmlspecialchars(stripslashes($HTTP_POST_VARS["title"])));
	$author = trim(htmlspecialchars(stripslashes($HTTP_POST_VARS["author"])));
	$email = trim(htmlspecialchars(stripslashes($HTTP_POST_VARS["email"])));
	$desc = trim(htmlspecialchars(stripslashes($HTTP_POST_VARS["desc"])));
	$SubmitDate = time();

	// escape quotes for database
	$esc_title = addslashes($title);
	$esc_author = addslashes($author);
	$esc_desc =addslashes($desc);
//	echo "testing, ignore this: " . $esc_title;

	foreach ($CatID as $Cat) {
		$sql = "SELECT CatName FROM Categories WHERE CatID='$Cat'";
		$getCatNames = $db->select($sql);
		$CatName[] = $getCatNames[0]["CatName"];
	}

	// insert link into links table
	$sql = "INSERT INTO Links ";
	$sql .= "(Url,LinkName,Description,SubmitName,SubmitEmail,SubmitDate,Approved,Important) ";
	$sql .= "values ";
	$sql .= "('$url','$esc_title','$esc_desc','$esc_author','$email',$SubmitDate,'0','0') ";
//	$results = $db->insert($sql);
// Commented out insert as theforum has voted to close deo to
// new content
// Martin Burns 23 October 2006

	// insert link into CatLinks table (once for each category)
	foreach ($CatID as $category) {
		$sql2 = "INSERT INTO LinkCats ";
		$sql2 .= "(LinkID,CatID) ";
		$sql2 .= "values ";
		$sql2 .= "('$results','$category') ";
		//$results2 = $db->insert($sql2);
		// Commented out insert as theforum has voted to close deo to
		// new content
		// Martin Burns 23 October 2006
	}

	// if database insert is successful, print success page and email admin
	if ($results) {
		print "<p>\n";
		print "Your link has been submitted to the directory admin for review.\n";
		print "</p>\n";
		print "<p>\n";
		print "You submitted the following information:";

		print "<table border=\"0\" cellpadding=\"2\" cellspacing=\"2\">";
	
		print "<tr><td align=\"right\" valign=\"top\"><strong>Categories:</strong></td><td>";

		print implode (" and ", $CatName);

		print "</td></tr>";
		print "<tr><td align=\"right\"><strong>Title:</strong></td><td>$title</td></tr>";
		print "<tr><td align=\"right\"><strong>URL:</strong></td><td>$url</td></tr>";
		print "<tr><td align=\"right\" valign=\"top\"><strong>Description:</strong></td><td><div style=\"white-space: pre;\">$desc</div></td></tr>";
		print "<tr><td align=\"right\"><strong>Your Name:</strong></td><td>$esc_author</td></tr>";
		print "<tr><td align=\"right\"><strong>Your Email:</strong></td><td>$email</td></tr>";

		print "</table>";

		print "<p>\n";
		print "Thank you for your contribution!\n";
		print "</p>\n";

		mail("content@lists.evolt.org","New d.e.o. link!","A new link has been submitted to the evolt directory (using the bookmarklet).\n\nTo view/approve it go here:\n\nhttp://dir.evolt.org/manage/viewlinks.php?LinkID=$results\n","From:d.e.o. Robot <nobody@leo.evolt.org>");

		print "<script language=\"JavaScript\" type=\"text/javascript\">\n";
		print "<!--\n";
		print "document.write('<a href=\"javascript:self.close();\">Close this window</a>');\n";
		print "setTimeout(\"self.close();\",5000);\n";
		print "//-->\n";
		print "</script>\n\n";

	}
	// if database insert failed, email info to admin instead
	else {
#		$recipient = "content@lists.evolt.org";
		$recipient = "dmah@shaw.ca";
		$subject = "d.e.o. link submission [FAILED INSERT]";
		$headers = "From: $author <$email>\n";

		$message = "$author has just submitted a link to the evolt directory, but the entry could not be inserted.\n\n";
		$message .= "This is what they sent:\n\n";
		$message .= "Categories:  " . implode (" and ", $CatName) . "\n";
		$message .= "Title:       " . $title . "\n";
		$message .= "URL:         " . $url . "\n";
		$message .= "Description: " . $desc . "\n";
		$message .= "Author:      " . $author . "\n";
		$message .= "Email:      " . $email . "\n\n";
		$message .= "The SQL statements needed to insert and approve this entry is given below:\n\n";
		$message .= "INSERT INTO Links ";
		$message .= "(Url,LinkName,Description,SubmitName,SubmitEmail,SubmitDate,Approved,Important) ";
		$message .= "values ";
		$message .= "('$url','$esc_title','$esc_desc','$esc_author','$email',$SubmitDate,'0','0')\n";
		foreach ($CatID as $category) {
			$message .= "INSERT INTO LinkCats ";
			$message .= "(LinkID,CatID) ";
			$message .= "values ";
			$message .= "('$results','$category') \n";
		}
		$message .= "\nRemember to put the auto increment ID in instead of [LINK_ID] when adding to LinkCats table.\n\n";
		$message .= "Have a nice day!\n\n";

		// mail message to admin
		mail($recipient,$subject,$message,$headers);

		print "<p>\n";
		print "Your link has been submitted to the directory admin for review.\n";
		print "</p>\n";
		print "<p>\n";
		print "You submitted the following information:";

		print "<table border=\"0\" cellpadding=\"2\" cellspacing=\"2\">";
	
		print "<tr><td align=\"right\" valign=\"top\"><strong>Categories:</strong></td><td>";

		print implode (" and ", $CatName);

		print "</td></tr>";
		print "<tr><td align=\"right\"><strong>Title:</strong></td><td>$title</td></tr>";
		print "<tr><td align=\"right\"><strong>URL:</strong></td><td>$url</td></tr>";
		print "<tr><td align=\"right\" valign=\"top\"><strong>Description:</strong></td><td><div style=\"white-space: pre;\">$desc</div></td></tr>";
		print "<tr><td align=\"right\"><strong>Your Name:</strong></td><td>$author</td></tr>";
		print "<tr><td align=\"right\"><strong>Your Email:</strong></td><td>$email</td></tr>";

		print "</table>";

		print "<p>\n";
		print "Thank you for your contribution!\n";
		print "</p>\n";

		print "<script language=\"JavaScript\" type=\"text/javascript\">\n";
		print "<!--\n";
		print "document.write('<a href=\"javascript:self.close();\">Close this window</a>');\n";
		print "setTimeout(\"self.close();\",5000);\n";
		print "//-->\n";
		print "</script>\n\n";

	}


}

if ($HTTP_POST_VARS["suggest"]) {

	// validate the submitted data
	list ($is_valid,$errors) = validateform();

	if ($is_valid) {
		// display results page
		showresults();

	}
	else {
		showform($errors);
	}

}
else {
	showform();
}


include("includes/suggest_footer.php");

?>
