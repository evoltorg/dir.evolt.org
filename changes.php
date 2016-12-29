<html>
<head>
<title>Changes to evolt directory code</title>
</head>
<body>
<h1>Changes to evolt directory code</h1>
<p>
This page is to keep track of recent changes to dir.evolt.org, so we 
know who has done what and when. Just keep adding entries below:
</p>
<!-- add new entries here -->

<hr>
<h2>d.e.o. uploaded to directory members area</h2>
<p>
I've uploaded my latest version of the site. I've opted for the emerald colour scheme. Currently Colette is working on writing a function to calculate the number of links/subcategories within a specified category. Jacob is going to work on generating more friendly urls for the categories. I have a few small changes to the HTML to make, plus a stack of new links to add to the database.
</p>
<small>
<!-- Author -->
Posted by Simon
<!-- Author Email -->
&lt;<a href="mailto:ppxsjc1@nottingham.ac.uk">ppxsjc1@nottingham.ac.uk</a>&gt; 
<!-- Date and time posted -->
<!-- British Summer time = GMT+1 ; Central Daylight Time = GMT-5 -->
at 16:24 GMT (17:24 BST / 11:24 CDT) on 21-May-01.
</small>

<h2>d.e.o files copied for development</h2>
<p>I made copies of the index.php and include files and added "_colette" to the filenames.  I'm trying add the new functions from phpHoo2 that show the amount of links in each category.  The functions need to be modified to connect with database.  I wanted to be able to test the code without losing any original work.</p>
<small>
Posted by Colette
&lt;<a href="mailto:colette@wi.rr.com">colette@wi.rr.com</a>&gt;
at 10:42 AM CDT / 4:42 PM BST on 23-May-01.
</small>

<h2>the link and category counts are up and running!</h2>
<p>
I've made a few changes to the SQL query and now the link and category counts are functioning. I've copied my modified version of Colette's files over the previous versions.
</p>
<small>
<!-- Author -->
Posted by Simon
<!-- Author Email -->
&lt;<a href="mailto:ppxsjc1@nottingham.ac.uk">ppxsjc1@nottingham.ac.uk</a>&gt; 
<!-- Date and time posted -->
<!-- British Summer time = GMT+1 ; Central Daylight Time = GMT-5 -->
at 18:02 GMT (19:02 BST / 13:02 CDT) on 25-May-01.
</small>


<h2>Tweaks to code and about/guidelines text</h2>
<p>
I've made a few changes to the about/guidelines include files and changes the display code so the headers and footers are taken from a centrally maintained source. Still to do:
</p>
<ul>
<li><strong>HTML tweaking</strong><br>Some padding issues on the submit pages</li>
<li><strong><strike>Improve the search</strike></strong> - Done 09-June-01<br> <strike>I have the code, but need to plug it in and get it working.</strike></li>
<li><strong><strike>Friendly URLs.</strike></strong> - Done 22-Oct-01<br> <strike>Need to write a couple of functions, one to generate a friendly url string from a category id, another to take the string and convert it back. Then we can use mod_rewrite to get it working seamlessly.</strike></li>
<li><strong><strike>Generic submit a site page</strike></strong> - Done 22-Oct-01<br><strike>I want to create a submit a link page that can be used with a bookmarklet to make submissions more straightforward. This is fairly low priority at the moment.</strike></li>
</ul>
<small>
<!-- Author -->
Posted by Simon
<!-- Author Email -->
&lt;<a href="mailto:ppxsjc1@nottingham.ac.uk">ppxsjc1@nottingham.ac.uk</a>&gt; 
<!-- Date and time posted -->
<!-- British Summer time = GMT+1 ; Central Daylight Time = GMT-5 -->
at 17:06 GMT (18:06 BST / 12:06 CDT) on 07-June-01.
</small>


<h2>Improved search</h2>
<p>
The search engine now features AND, NOT and OR operators, +/- shortcuts for AND/NOT and phrases (by using double quotes)
</p>
<small>
<!-- Author -->
Posted by Simon
<!-- Author Email -->
&lt;<a href="mailto:ppxsjc1@nottingham.ac.uk">ppxsjc1@nottingham.ac.uk</a>&gt; 
<!-- Date and time posted -->
<!-- British Summer time = GMT+1 ; Central Daylight Time = GMT-5 -->
at 19:52 GMT (20:52 BST / 14:52 CDT) on 09-June-01.
</small>

<h2>Current To Do list</h2>
<p>
Right, so the rewriting is all sorted now and the bookmarklet seems to be fine too. The new to do list:
</p>
<ul>
  <li>Still need to sort out padding issues on some pages.</li>
  <li>Need to write an article advertising the deo bookmarklet.</li>
  <li>Need to re-write the admin side of d.e.o. to allow authorised 
control over individual branches of the directory tree. This is a pretty 
big job, which I'll more than likely need some help with. It would be nice to 
integrate the login with the evolt site-wide system. Need more info on how 
the priv levels work. Also need to spend a while thinking about exactly how 
this will work.</li>
</ul>
<small>
<!-- Author -->
Posted by Simon
<!-- Author Email -->
&lt;<a href="mailto:ppxsjc1@nottingham.ac.uk">ppxsjc1@nottingham.ac.uk</a>&gt; 
<!-- Date and time posted -->
<!-- British Summer time = GMT+1 ; Central Daylight Time = GMT-5 -->
at 19:35 GMT (20:35 BST / 14:35 CDT) on 22-Oct-01.
</small>
<hr>
<h2>d.e.o. closed to new link and category submission</h2>
<p>
Theforum voted to close deo to new content on 13 October 2006. Thus,
I have:</p>
<ul>
<li>Copied suggest.php to suggest_old.php
<li>Commented out the sql inserts in original suggest.php
<li>Added comments in suggest.php to note this.
</ul>
<p>
Not entirely elegant (better: add user messages),
but rn I'm not that bothered, with 60+ spam submissions
each day.
</p>
<small>
<!-- Author -->
Posted by Martin Burns
<!-- Author Email -->
&lt;<a href="mailto:martin@easyweb.co.uk">martin@easyweb.co.ukk</a>&gt;
<!-- Date and time posted -->
<!-- British Summer time = GMT+1 ; Central Daylight Time = GMT-5 -->
at 12:44 GMT (13:44 BST)  on 23-Oct-06.
</small>

</body>
</html>
