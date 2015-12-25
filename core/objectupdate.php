<?php

/* author   : edo satriani

 */
class objectupdate extends configSettings {
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

	public function record_webaccess_log($activity){
		try
		{

			$ip_address = $this->get_client_ip();
			$actual_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
			$strSQL = "INSERT INTO WEB_ACCESS (IP_ADDRESS,URL_REFERER,DESCRIPTION,ACTIVE_USER) VALUES ('".$ip_address."','".$actual_link."','".$activity."','".$_SESSION["user-id"]."');";
			$result = $this->executeQuery($strSQL);

		} catch(Exception $e) {

			throw new Exception($e->getMessage());

		}
	}

	// Function to get the client IP address
	public function get_client_ip() {
		$ipaddress = '';
		/*if (getenv('HTTP_CLIENT_IP'))
			$ipaddress = getenv('HTTP_CLIENT_IP');
		else if(getenv('HTTP_X_FORWARDED_FOR'))
			$ipaddress = getenv('HTTP_X_FORWARDED_FOR');
		else if(getenv('HTTP_X_FORWARDED'))
			$ipaddress = getenv('HTTP_X_FORWARDED');
		else if(getenv('HTTP_FORWARDED_FOR'))
			$ipaddress = getenv('HTTP_FORWARDED_FOR');
		else if(getenv('HTTP_FORWARDED'))
		   $ipaddress = getenv('HTTP_FORWARDED');
		else if(getenv('REMOTE_ADDR'))
			$ipaddress = getenv('REMOTE_ADDR');
		else
			$ipaddress = 'UNKNOWN';
		*/
		$cookie_name="greensysclientinfo";

		if(isset($_COOKIE[$cookie_name])) {
			 $ipaddress=$_COOKIE[$cookie_name];
		}
		return $ipaddress;
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
