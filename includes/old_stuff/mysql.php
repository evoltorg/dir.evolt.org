<?

Class MySQL
{
	var $CONN = "";
	var $DBASE = "astro38";
	var $USER = "astro38";
	var $PASS = "Sl%%per!2";
	var $SERVER = "localhost";

	var $TRAIL = array();
	var $HITS = array();

	var $AUTOAPPROVE = false;

	function error($text)
	{
		$no = mysql_errno();
		$msg = mysql_error();
		echo "[$text] ( $no : $msg )<BR>\n";
		exit;
	}

	function init ()
	{
		$user = $this->USER;
		$pass = $this->PASS;
		$server = $this->SERVER;
		$dbase = $this->DBASE;

		$conn = mysql_connect($server,$user,$pass);
		if(!$conn) {
			$this->error("Connection attempt failed");
		}
		if(!mysql_select_db($dbase,$conn)) {
			$this->error("Dbase Select failed");
		}
		$this->CONN = $conn;
		return true;
	}

//	*****************************************************************
//						MySQL Specific methods
//	*****************************************************************


	function select ($sql="", $column="")
	{
		if(empty($sql)) { return false; }
		if(!eregi("^select",$sql))
		{
			echo "<H2>Wrong function silly!</H2>\n";
			return false;
		}
		if(empty($this->CONN)) { return false; }
		$conn = $this->CONN;
		$results = mysql_query($sql,$conn);
		if( (!$results) or (empty($results)) ) {
			// mysql_free_result($results);
			return false;
		}
		$count = 0;
		$data = array();
		while ( $row = mysql_fetch_array($results))
		{
			$data[$count] = $row;
			$count++;
		}
		mysql_free_result($results);
		return $data;
	}

	function insert ($sql="")
	{
		if(empty($sql)) { return false; }
		if(!eregi("^insert",$sql))
		{
			echo "<H2>Wrong function silly!</H2>\n";
			return false;
		}
		if(empty($this->CONN)) { return false; }
		$conn = $this->CONN;
		$results = mysql_query($sql,$conn);
		if(!$results) { return false; }
		$results = mysql_insert_id();
		return $results;
	}

	function delete ($sql="")
	{
		if(empty($sql)) { return false; }
		if(!eregi("^delete",$sql))
		{
			echo "<H2>Wrong function silly!</H2>\n";
			return false;
		}
		if(empty($this->CONN)) { return false; }
		$conn = $this->CONN;
		$results = mysql_query($sql,$conn);
		return $results;
	}

	function update ($sql="")
	{
		if(empty($sql)) { return false; }
		if(!eregi("^update",$sql))
		{
			echo "<H2>Wrong function silly!</H2>\n";
			return false;
		}
		if(empty($this->CONN)) { return false; }
		$conn = $this->CONN;
		$results = mysql_query($sql,$conn);
		return $results;
	}

//	*****************************************************************
//						phpHoo Specific Methods
//	*****************************************************************

	function get_Cats ($CatParent= "")
	{
		if(empty($CatParent))
		{
			$CatParent = "IS NULL";
		} else {
			$CatParent = "= $CatParent";
		}
		$sql = "SELECT CatID,CatName FROM Categories WHERE (CatApproved != 0) AND CatParent $CatParent ORDER BY CatName";
		$results = $this->select($sql);
		return $results;
	}

//	The primer for a recursive query
	function get_ParentsInt($CatID="")
	{
		if(empty($CatID)) { return false; }
		unset($this->TRAIL);
		$this->TRAIL = array();
		$this->get_Parents($CatID);
	}

//	Use get_ParentsInt(), NOT this one!
//	The power of recursive queries

	function get_Parents ($CatID="")
	{
		if( (empty($CatID)) or ("$CatID" == "NULL")) { return false; }
		$sql = "SELECT CatID,CatParent,CatName from Categories where CatID = $CatID";

		$conn = $this->CONN;
		$results = mysql_query($sql,$conn);
		if( (!$results) or (empty($results)) ) {
			// mysql_free_result($results);
			return false;
		}

		while ( $row = mysql_fetch_array($results))
		{
			$trail = $this->TRAIL;
			$count = count($trail);
			$trail[$count] = $row;
			$this->TRAIL = $trail;
			$id = $row["CatParent"];
			$this->get_Parents($id);
		}
		return true;
	}

	function get_CatIDFromName($CatName="")
	{
		if(empty($CatName)) { return false; }
		$sql = "SELECT CatID from Categories where CatName = '$CatName'";
		$results = $this->select($sql);
		if(!empty($results))
		{
			$results = $results[0]["CatID"];
		}
		return $results;
	}

	function get_CatNames( $CatID="")
	{
		if($CatID == 0) { return "Top"; }
		$single = false;
		if(!empty($CatID))
		{
			$single = true;
			$CatID = "WHERE CatID = $CatID";
		}
		$sql = "SELECT CatName from Categories $CatID";
		$results = $this->select($sql);
		if($single)
		{
			if(!empty($results))
			{
				$results = $results[0]["CatName"];
			}
		}
		return $results;
	}

	function get_LinkName($LinkID="")
	{
		if($LinkID == "") return false;

		$sql = "SELECT LinkName from Links where LinkID=$LinkID";
		$results = $this->select($sql);
		if(!empty($results))
		{
			$results = $results[0]["LinkName"];
		}

		return $results;
	}

	function get_Links($CatID = "")
	{
		if(empty($CatID))
		{
			$CatID = "= 0";
		} else {
			$CatID = "= $CatID";
		}

		$sql = "SELECT Url,LinkName,Description FROM Links, LinkCats WHERE  (Approved != 0) AND CatID $CatID AND Links.LinkID = LinkCats.LinkID ORDER BY LinkName";
		$results = $this->select($sql);
		return $results;
	}

	function get_CatFromLink($LinkID="")
	{
		if(empty($LinkID)) { return false; }
		$sql = "SELECT CatID FROM LinkCats WHERE LinkID = $LinkID";
		$results = $this->select($sql);
		if(!empty($results))
		{
			// If there are more than one this currently returns the first only
			// Remove this if statement to return an array of values
			$results = $results[0]["CatID"];
		}
		return $results;
	}

	function get_Desc($CatID="")
	{
		if(empty($CatID)) { return false; }
		$sql = "SELECT CatDesc FROM Categories WHERE CatID = $CatID";
		$results = $this->select($sql);
		if(!empty($results))
		{
			$results = $results[0]["CatDesc"];
		}
		return $results;
	}

	function search ($keywords = "")
	{
		if(empty($keywords)) { return false; }

		$DEBUG = ""; // set DEBUG == "\n" to see this query

		$keywords = trim(urldecode($keywords));
		$keywords = ereg_replace("([    ]+)"," ",$keywords);

		if(!ereg(" ",$keywords))
		{
			// Only 1 keyword
			$KeyWords[0] = "$keywords";
		} else {
			$KeyWords = explode(" ",$keywords);
		}

		$sql = "SELECT DISTINCT Links.LinkID,CatID,Url,LinkName,Description FROM Links, LinkCats WHERE (Approved != 0) AND Links.LinkID = LinkCats.LinkID AND ( $DEBUG ";
		$count = count($KeyWords);

		if( $count == 1)
		{
			$single = $KeyWords[0];
			$sql .= " (Description LIKE '%$single%') OR (LinkName LIKE '%$single%') OR (Url LIKE '%$single%') ) ORDER BY LinkName $DEBUG ";
		} else {
			$ticker = 0;
			while ( list ($key,$word) = each ($KeyWords) )
			{
				$ticker++;
				if(!empty($word))
				{
					if($ticker != $count)
					{
						$sql .= " ( (Description LIKE '%$word%') OR (LinkName LIKE '%$word%') OR (Url LIKE '%$word%') ) OR $DEBUG ";
					} else {
						// Last condition, omit the trailing OR
						$sql .= " ( (Description LIKE '%$word%') OR (LinkName LIKE '%$word%') OR (Url LIKE '%$word%') ) $DEBUG ";
					}
				}
			}
			$sql .= " ) ORDER BY LinkName $DEBUG";
		}

		if(!empty($DEBUG)) { echo "<PRE>$sql\nTicker [$ticker]\nCount [$count]</PRE>\n"; }

		$results = $this->select($sql);
		return $results;
	}

	function suggest ($postData="")
	{
		if( (empty($postData)) or (!is_array($postData)) ) { return false; }

		$CatID = $postData["CatID"];
		$Url = $postData["Url"];
		$Description = $postData["Description"];
		$LinkName = $postData["LinkName"];
		$SubmitName = $postData["SubmitName"];
		$SubmitEmail = $postData["SubmitEmail"];
		$ExtraCats = $postData["ExtraCats"];
		$SubmitDate = time();

		if(empty($Url)) { return false; }
		if(empty($LinkName)) { return false; }
		if(empty($SubmitName)) { $SubmitName = "Anonymous"; }
		if(empty($SubmitEmail)) { $SubmitEmail = "Not given"; }

		// strip most HTML from input
		$Url = strip_tags($Url);
		$LinkName = strip_tags($LinkName);
		$SubmitName = strip_tags($SubmitName);
		$SubmitEmail = strip_tags($SubmitEmail);
		$ExtraCats = strip_tags($ExtraCats);
		$Description = strip_tags($Description,"<a>,<i>,<b>,<strong>,<em>");

		// reg exp to strip javascript: statements and event handlers
		$pattern = array("/<a[^>]*javascript:[^>]*?>(.*?)<\/a>/si","/(<a[^>]*) on[^ >]*([^>]*>)/si");
		$replace = array("\\1","\\1\\2");
		$Description = preg_replace($pattern,$replace,$Description);

		$Approved = 0;
		if($this->AUTOAPPROVE) { $Approved = 1; }
		$Important = 0;

		$sql = "INSERT INTO Links ";
		$sql .= "(Url,LinkName,Description,SubmitName,SubmitEmail,SubmitDate,Approved,Important) ";
		$sql .= "values ";
		$sql .= "('$Url','$LinkName','$Description','$SubmitName','$SubmitEmail',$SubmitDate,$Approved,$Important) ";

		$results = $this->insert($sql);

		$sql2 .= "INSERT INTO LinkCats ";
		$sql2 .= "(LinkID,CatID) ";
		$sql2 .= "values ";
		$sql2 .= "('$results','$CatID') ";

		$results2 = $this->insert($sql2);

		return $results;
	}


	function suggestCat ($postData="")
	{
		if( (empty($postData)) or (!is_array($postData)) ) { return false; }

		$CatID = $postData["CatID"];
		if ($CatID == 0) $CatID = "NULL";
		$CatName = $postData["CatName"];
		$CatDesc = $postData["CatDesc"];
		$CatSubmitName = $postData["CatSubmitName"];
		$CatSubmitEmail = $postData["CatSubmitEmail"];
		$CatSubmitDate = time();

		if(empty($CatName)) { return false; }
		if(empty($CatSubmitName)) { $CatSubmitName = "Anonymous"; }
		if(empty($CatSubmitEmail)) { $CatSubmitEmail = "Not given"; }

		// strip most HTML from input
		$CatName = strip_tags($CatName);
		$CatSubmitName = strip_tags($CatSubmitName);
		$CatSubmitEmail = strip_tags($CatSubmitEmail);
		$CatDesc = strip_tags($CatDesc,"<a>,<i>,<b>,<strong>,<em>");

		// reg exp to strip javascript: statements and event handlers
		$pattern = array("/<a[^>]*javascript:[^>]*?>(.*?)<\/a>/si","/(<a[^>]*) on[^ >]*([^>]*>)/si");
		$replace = array("\\1","\\1\\2");
		$CatDesc = preg_replace($pattern,$replace,$CatDesc);

		$CatApproved = 0;
		if($this->AUTOAPPROVE) { $CatApproved = 1; }


		$sql = "INSERT INTO Categories ";
		$sql .= "(CatID,CatName,CatParent,CatDesc,CatSubmitName,CatSubmitEmail,CatSubmitDate,CatApproved) ";
		$sql .= "values ";
		$sql .= "(NULL,'$CatName',$CatID,'$CatDesc','$CatSubmitName','$CatSubmitEmail',$CatSubmitDate,$CatApproved) ";

		$results = $this->insert($sql);

		return $results;
	}

}	//	End Class
?>
