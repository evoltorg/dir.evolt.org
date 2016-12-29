<table width="98%" border="0" cellpadding="4" cellspacing="0">

<tr>
<td align="right" class="pageTitle1" nowrap><h1>&nbsp;&nbsp;build&nbsp;</h1></td>
<td class="pageTitle2"><h1>&nbsp;the evolt.org directory</h1></td>
</tr>

<tr>
<td valign="top" align="right" class="main" nowrap>&nbsp;</td>
<td valign="top" class="main">

<?php

global $submit,$username,$useremail;

// check the fields don't contain quotes (which would mess up the javascript in the bookmarklet)
function validate ($input) {
 return (!ereg('[\'"]', $input));
}

// our form for user input
$form = "<p>You can help to build the evolt.org directory by suggesting new links and categories for inclusion. To make this super easy we've create a bookmarklet (<a href=\"http://www.bookmarklets.com/about/\" title=\"About Bookmarklets\" target=\"_blank\">what's a bookmarklet?</a>) that let's you make submissions with just a couple of clicks. Simply enter your name and email address in the fields below to generate your own personalised version.</p>\n
<p>Please note that filling in the fields is entirely optional (you can submit the form blank and still get a working bookmarklet). This form doesn't use your details for anything other than generating the bookmarklet.</p>\n
<form action=\"/info/bookmarklet\" method=\"post\">
<table><tr><td>Name:</td>
<td><input type=\"text\" name=\"username\" maxlength=\"50\" size=\"30\" value=\"" . htmlentities(stripslashes($username)) . "\"></td></tr>
<td>Email:</td><td><input type=\"text\" maxlength=\"50\" name=\"useremail\" size=\"30\" value=\"" . htmlentities(stripslashes($useremail)) . "\"></td></tr>
<tr><td>&nbsp;</td><td><input type=\"submit\" name=\"submit\" value=\"Generate Personalised Bookmarklet\"></td></tr></table></form>" ;

// container for error message
$errormsg = "<p><strong>Whoops! You cannot use quotes in the name or email address fields. Please remove them and try again.</strong></p> " ;

// PAGE LOGIC //
if (!isset($submit)) { // form isn't submitted
 
 print $form ; // display form
} else { // form has been submitted
 
 if (validate(stripslashes($username)) && validate(stripslashes($useremail))) { //everything looks valid
 print ("<p>Thank you.</p>\n
 <p>We have generated your bookmarklet. For help and instructions see below.</p>");
 print ("<a href=\"javascript:if(document.selection){desc=escape(document.selection.createRange().text);}else if(document.getSelection){desc=escape(document.getSelection());}else{desc='';}title=escape(document.title);author=escape('$username');email=escape('$useremail');url=escape(document.location);urlstring='?url='+url+'&author='+author+'&email='+email+'&desc='+desc+'&title='+title;void(window.open('http://dir.evolt.org/suggest.php'+urlstring,'newwindow','width=600,height=400,scrollbars=yes'))\" title=\"Add to evolt\">Add to evolt</a>");
 print "<br>&nbsp;<br></td></tr>\n<tr>\n<td align=\"right\" class=\"pageTitle1\" nowrap><h1>&nbsp;how&nbsp;to&nbsp;&nbsp;</h1></td>\n<td class=\"pageTitle2\"><h1>&nbsp;use the bookmarklet</h1></td>\n</tr>\n<tr>\n<td valign=\"top\" align=\"right\" class=\"main\" nowrap>&nbsp;</td>\n<td valign=\"top\" class=\"main\">\n";
 print "<p>To start using the bookmarklet, simply drag the \"add to evolt\" link above to your personal toolbar or save it as a bookmark/favorite. </p><p>When you find a page you would like to submit to the directory, click the bookmarklet while visiting the URL in question. A submission form will appear prefilled with the site URL, title and the name and email address you just entered. In most common browsers any text that was selected when you used the bookmarklet will appear in the description field. </p><p>Simply edit the fields as required, select one or more category from the list (use the control key to select several categories) and hit submit.</p>\n";
 } else { // Uh-oh, something is up with the entered data
 
 print $errormsg ;
 print $form ;
 
 }
}
?>

</td>
</tr>
</table>