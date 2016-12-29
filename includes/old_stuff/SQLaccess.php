<?

//	*****************************************************************
//						SQLaccess syntax
//	*****************************************************************
//
//	INITIALISATION:
//
//	include("../includes/SQLaccess.php");
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


Class SQLaccess {

	var $CONN = "";
	var $DBASE = "directory";
	var $USER = "directory";
	var $PASS = "3voltweb";
	var $SERVER = "localhost";

	function error($text) {
		$no = mysql_errno();
		$msg = mysql_error();
		echo "[$text] ( $no : $msg )<BR>\n";
		exit;
	}

	function init () {
		$user = $this->USER;
		$pass = $this->PASS;
		$server = $this->SERVER;
		$dbase = $this->DBASE;

		$conn = mysql_connect($server,$user,$pass);
		if(!$conn) {
			$this->error("init: Connection attempt failed");
		}
		if(!mysql_select_db($dbase,$conn)) {
			$this->error("init: Database Select failed");
		}
		$this->CONN = $conn;
		return true;
	}

//	*****************************************************************
//						MySQL Specific methods
//	*****************************************************************

	function select ($sql="") {
		if(empty($sql)) { return false; }
		if(!eregi("^select",$sql)) {
			echo "select: SQL is not a select function.\n";
			return false;
		}
		$conn = $this->CONN;
		if(empty($conn)) { return false; }
		$results = mysql_query($sql,$conn);
		if( (!$results) or (empty($results)) ) {
			$this->error("select: Invalid SQL statement.");
			return false;
		}
		$count = 0;
		$data = array();
		while ( $row = mysql_fetch_array($results)) {
			$data[$count] = $row;
			$count++;
		}
		mysql_free_result($results);
		return $data;
	}

	function insert ($sql="") {
		if(empty($sql)) { return false; }
		if(!eregi("^insert",$sql)) {
			echo "insert: SQL is not an insert function.\n";
			return false;
		}
		$conn = $this->CONN;
		if(empty($conn)) { return false; }
		$results = mysql_query($sql,$conn);
		if(!$results) {
			$this->error("insert: Invalid SQL statement.");
			return false;
		}
		$results = mysql_insert_id();
		return $results;
	}

	function delete ($sql="") {
		if(empty($sql)) { return false; }
		if(!eregi("^delete",$sql)) {
			echo "delete: SQL is not a delete function.\n";
			return false;
		}
		$conn = $this->CONN;
		if(empty($conn)) { return false; }
		$results = mysql_query($sql,$conn);
		if(!$results) {
			$this->error("delete: Invalid SQL statement.");
			return false;
		}
		return $results;
	}

	function update ($sql="") {
		if(empty($sql)) { return false; }
		if(!eregi("^update",$sql)) {
			echo "update: SQL is not an update function.\n";
			return false;
		}
		$conn = $this->CONN;
		if(empty($conn)) { return false; }
		$results = mysql_query($sql,$conn);
		if(!$results) {
			$this->error("update: Invalid SQL statement.");
			return false;
		}
		return $results;
	}

//	*****************************************************************
//						DBAccess Specific Methods
//	*****************************************************************

	function get_entry ($table="", $IDfield="", $ID="") {
		if(empty($table) || empty($IDfield) || empty($ID)) { return false; }
		$sql = "SELECT * FROM $table WHERE $IDfield=$ID";
		$results = $this->select($sql);
		return $results;
	}

	function delete_entry ($table="", $IDfield="", $ID="") {
		if(empty($table) || empty($IDfield) || empty($ID)) { return false; }
		$sql = "DELETE FROM $table WHERE $IDfield=$ID";
		$results = $this->delete($sql);
		return $results;
	}

	function add_entry ($table="", $fields="", $values="") {
		if(empty($table) || empty($fields) || empty($values)) { return false; }
		$sql = "INSERT INTO $table ($fields) VALUES ($values)";
		$results = $this->insert($sql);
		return $results;
	}

	function edit_entry ($table="", $IDfield="", $ID="", $changes="") {
		if(empty($table) || empty($IDfield) || empty($ID) || empty($changes)) { return false; }
		$sql = "UPDATE $table SET $changes WHERE $IDfield=$ID";
		$results = $this->update($sql);
		return $results;
	}

}	//	End Class
?>
