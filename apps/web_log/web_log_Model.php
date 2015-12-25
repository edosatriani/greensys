<?php

/* author   : edo satriani

 */
class web_log_Model extends configSettings {
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

    public function show_message() {
        return array(
            'title' => 'Aplikasi MVC',
            'body'  => 'Hello, World!'
        );
    }

		public function userInformation() {
				//select the database
				mssql_select_db($this->dbname, $this->dbcon);

				//SQL Select statement
				$sqlselect = "SELECT userid,password,userright,location,fullname,gender,position,member_since,avatar FROM pospass WITH (NOLOCK) WHERE userid ='" . $_COOKIE["user-id"] ."';";

				//Run the SQL query
				$sqlquery = mssql_query($sqlselect);

				while ($result = mssql_fetch_array($sqlquery)){
							$fullname = (string)$result["fullname"];
							$gender = (string)$result["gender"];
							$position = (string)$result["position"];
							$member_since = (string)$result["member_since"];
							$avatar = (string)$result["avatar"];
				}

				return array(
						'fullname' => $fullname,
						'gender'  => $gender,
						'position'  => $position,
						'member_since'  => $member_since,
						'avatar'  => $avatar
				);
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

		for ($i = 0; $i <= $pLen - strlen($lastcounter) ; $i++)
		{
			$temp .= "0";
		}

		$string_result = $vFormat.$temp.$lastcounter;

		return $string_result;
	}

	public function get_web_log() {
		//select the database
		mssql_select_db($this->dbname, $this->dbcon);

		//SQL Select statement
		$xml = new XMLHandler(XML_DIR . "web_request_spk.xml");
		$sql_from_xml = $xml->getNode("web_log");
		$sqlselect = str_replace("FILTER_BY_PROGRAM","",$sql_from_xml);

		//Run the SQL query
		$result=mssql_query($sqlselect);
		$numfields = mssql_num_fields($result);


		$string_result = "";

		while ($row = mssql_fetch_row($result)) {

			$string_result .= "<tr>";

			for ($i=0; $i < $numfields; $i++)
			{

				if (mssql_field_name($result, $i)=="IP_ADDRESS") {
					$ip = explode(":",$row[$i]);
					$string_result .= "<td>".$ip[0]."</td>";
					$string_result .= "<td>".$ip[1]."</td>";
				}else{
					$string_result .= "<td>".$row[$i]."</td>";
				}
			}

			$string_result .= "</tr>";

		}
		return $string_result;
	}

  public function __destruct() {
 		unset($this);
 	}

}
