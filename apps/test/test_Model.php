<?php
 
/* author   : edo satriani

 */
class test_Model extends configSettings {
	public $dbcon;
	
	public $dbhost;
	public $dbuser;
	public $dbpass;
	public $dbname;

	public $modify_status;
		
	public function __construct() {
		
		// Start the session
		session_start();
	
		$this->LoadSettings();
		
		$username = $this->dbuser;
		$password = $this->dbpass;
		$hostname = $this->dbhost;
		
		$this->modify_status="INS";
		try {
			//connection to the database
			$this->dbcon = mssql_connect($hostname, $username, $password)or die("Unable to connect to MSSQL");
		} catch(Exception $e) {
			throw new Exception($e->getMessage());
		}
	}
	
	public function getConfiguration($config_id) {
		
		//select the database
		mssql_select_db($this->dbname, $this->dbcon);
	
		//SQL Select statement
		$sqlselect = "SELECT config_value FROM app_configuration WHERE config_id = " . $config_id .";";
		
		//Run the SQL query
		$sqlquery = mssql_query($sqlselect);
		$string_result="";
		while ($result = mssql_fetch_array($sqlquery))	{
				$string_result = $result["config_value"];
		}
		
		return $string_result;
	}
	
	
	public function AutoNumberWithFormat($nTable,$nField,$vFormat,$pLen) {
		
		//select the database
		mssql_select_db($this->dbname, $this->dbcon);
	
		//SQL Select statement
		$sqlselect = "SELECT ISNULL(CAST(MAX(SUBSTRING(". $nField . ",len('" . $vFormat ."')+1, " . $pLen . ")) AS INTEGER)+1,1) AS NUM ";
		$sqlselect .= "FROM " . $nTable ." WHERE SUBSTRING(" . $nField .", 1,len('" . $vFormat . "')) ='" . $vFormat . "'";
		
		//Run the SQL query
		$sqlquery = mssql_query($sqlselect);
		
		$num_rows = mssql_num_rows($sqlquery);
		
		if($num_rows == 0)  {
			$lastcounter=1;
		}else{
			while ($result = mssql_fetch_array($sqlquery))	{
				$lastcounter = $result["NUM"];
			}
		}
		
		for ($i = 1; $i <= $pLen - strlen($lastcounter) ; $i++) 
		{
			$temp .= "0";
		}
		
		$string_result = $vFormat.$temp.$lastcounter;
		
		return $string_result;
	}
	
	public function get_active_size() {
		//select the database
		mssql_select_db($this->dbname, $this->dbcon);
	
		//SQL Select statement
		$xml = new XMLHandler(XML_DIR . "test.xml");
		$sql_from_xml = $xml->getNode("size");
		$sqlselect = $sql_from_xml;

		//Run the SQL query
		$sqlquery = mssql_query($sqlselect);
		
		$string_result= "[";
		while ($result = mssql_fetch_array($sqlquery))	{
			if ($string_result != "[") {
				$string_result .= ",";
			}
			$string_result .= "{id:'".$result['SIZEID']."',name:'".$result['DESCRIPTION']."'}";
		}
		$string_result .= "]";
		return $string_result;
	}
	
	public function get_activetrans($criteria) {
		//select the database
		mssql_select_db($this->dbname, $this->dbcon);
	
		//SQL Select statement
		$xml = new XMLHandler(XML_DIR . "test.xml");
		$sql_from_xml = $xml->getNode("active_trans");
		
		$sqlselect = str_replace("FILTER_BY_PROGRAM"," AND SESSION_ID = '".$criteria."' ",$sql_from_xml);
		
		//Run the SQL query
		$result=mssql_query($sqlselect);
		$numfields = mssql_num_fields($result);
		
		$string_result= "<table class=\"table table-hover\"><thead><tr>";
		
		
		for ($i=0; $i < $numfields; $i++) 
		{ 
			$string_result .= "<th>".mssql_field_name($result, $i)."</th>";
		}

		$string_result .="</tr></thead><tbody>";
			
		while ($row = mssql_fetch_row($result)) {

			$string_result .= "<tr>";
			
			for ($i=0; $i < $numfields; $i++) 
			{ 
				if($i==0){
					$string_result .= "<th scope=\"row\">".$row[$i]."</th>";
				}else{
					$string_result .= "<td>".$row[$i]."</td>";
				}
				if (mssql_field_name($result, $i)=="SIZE") {
					if (!isset($_SESSION["size_id"]) && empty($_SESSION["size_id"])){			
						$_SESSION["size_id"] = $row[$i];
					}
				}
			}

			$string_result .= "</tr>";

		}
		$string_result .= "</tbody></table>";
		return $string_result;
	}
	
	public function count_activetrans($criteria) {
		//select the database
		mssql_select_db($this->dbname, $this->dbcon);
	
		//SQL Select statement
		$xml = new XMLHandler(XML_DIR . "test.xml");
		$sql_from_xml = $xml->getNode("count_active_trans");
		
		$sqlselect = str_replace("FILTER_BY_PROGRAM"," AND SESSION_ID = '".$criteria."' ",$sql_from_xml);
		
		//Run the SQL query
		$sqlquery = mssql_query($sqlselect);
		$string_result="";
		while ($result = mssql_fetch_array($sqlquery))	{
				$string_result = $result["BARCODE_COUNT"];
		}
		
		return $string_result;
	}
 
}