<?php

/* author   : edo satriani

 */
class ob_update_Model extends configSettings {
	public $dbcon;

	public $dbhost;
	public $dbuser;
	public $dbpass;
	public $dbname;

	public function __construct() {

		$this->LoadSettings();

		$username = $this->dbuser;
		$password = $this->dbpass;
		$hostname = $this->dbhost;

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

	public function executeQuery($strSQL){

		try {

			//select the database
			mssql_select_db($this->dbname, $this->dbcon);

			//Run the SQL query
			$this->result = mssql_query($strSQL);

			if(!$this->result) {
				throw new Exception($this->dbcon->error);
			}else{
				return true;
			}

		} catch(Exception $e) {

			throw new Exception($e->getMessage());

		}
	}

  public function __destruct() {
 		unset($this);
 	}

}
