<html><body>

<h1>Test SQLaccess.php</h1>

<?
include("/store/host/dir.evolt.org/includes/SQLaccess.php");

$db = new SQLaccess;

$db->init();

$table = "Links";
$IDfield = "LinkID";
$ID = 23;
$results = $db->delete_entry($table,$IDfield,$ID);

print "<pre>\n";
print_r($results);
print "</pre>\n";
?>

<?

//	*****************************************************************
//						SQLaccess syntax
//	*****************************************************************
//
//	INITIALISATION:
//
//	include("/store/host/dir.evolt.org/includes/SQLaccess.php");
//	$db = new SQLaccess;
//	if(!$db->init()) {
//		echo "Init: Initialisation failed.\n";
//		exit;
//	}
//
//	USAGE:
//
//	$results = $db->get_entry($table,$IDfield,$ID);
//	returns 2D results array or FALSE (fail or zero entries)
//
//	$results = $db->delete_entry($table,$IDfield,$ID);
//	returns TRUE or FALSE (fail)
//
//	$results = $db->add_entry($table,$fields,$values);
//	where:
//	$fields = "field1,field2,field3,field4,...,fieldN";
//	$values = "int_value,'str_value',bool_value,...,'str_value2'";
//	returns insertID or FALSE (fail)
//
//	$results = $db->edit_entry($table,$IDfield,$ID,$changes);
//	where:
//	$changes = "field1='str_value',field2='str_value2',field3=int_value";
//	returns TRUE or FALSE (fail)
//
//	Or run a custom SQL query with the following syntax:
//
//	$results = $db->select($sql);
//	$results = $db->delete($sql);
//	$results = $db->insert($sql);
//	$results = $db->update($sql);
//
//	*****************************************************************

?>

</body>
</html>
