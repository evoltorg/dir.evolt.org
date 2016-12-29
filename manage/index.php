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
@import url(/admin.css);
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
<strong class="current">Admin Home</strong>
</div>

<div class="smalllinks" align="right">
<small>
<strong class="current">admin</strong> | 
<a href="links.php" title="Links Admin.">links</a> | 
<a href="categories.php" title="Category Admin.">categories</a>
</small>
</div>

<a href="links.php" title="Links Admin."><h2>Links Admin</h2></a>
<p>
Make changes to the links within the directory here.
</p>

<a href="categories.php" title="Category Admin."><h2>Category Admin</h2></a>
<p>
Make changes to the Categories within the directory here.
</p>

</body>
</html>
