<?
function PrintHeadlines()
{
   system('/home/sites/dir.evolt.org/web/sidebar.pl');

/**********************************************************************
   $file = fopen("http://www.evolt.org/xml/articles.xml", "r");

   if (!$file) {
      print "Couldn't connect to evolt.org\n";
      exit;
   }

   while (!feof($file)) {
      $line = fgets($file, 1024);

      if (eregi('<a href=\"([^\"]+)\">(.+)</a>', $line, $match)) {
         print "      <p><a href='$match[1]' target='_content'>" .
               $match[2] . "</a></p>\n"; 
      }
   }

   fclose($file);
**********************************************************************/

}
?>

<html>
<head>
<title>evolt.org</title>
<link rel="stylesheet" type="text/css" href="http://evolt.org/evolt/thisisold_isaac.css">
</head>

<body bgcolor="#cccccc">

<table width="98%" cellspacing="0" cellpadding="2" border="0">
<tr>
   <td>
      <a href="http://dir.evolt.org/sidebar.php">Refresh</a>
   </td>
</tr>
<tr>
   <td bgcolor="#000000"><a href="http://evolt.org/" target="_content"><img src="http://evolt.org/evolt/images/evolt_logo_static_b.gif" alt="evolt.org" border="0"></a></td>
</tr>
<tr>
   <td class="main">
      <a href="http://browsers.evolt.org/" target="_content">Browsers</a> |
      <a href="http://lists.evolt.org/" target="_content">Lists</a> |
      <a href="http://lists.evolt.org/harvest/" target="_content">Tips</a> |
<!--      <a href="http://members.evolt.org/" target="_content">Members</a> | -->
      <a href="http://dir.evolt.org/" target="_content">Directory</a>
      <br><br>
   </td>
</tr>
<tr>
   <td class="main">
<?
   PrintHeadlines();
?>
   </td>
</tr>
</table>

</body>
</html>
