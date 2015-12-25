<?php

/* author   : edo satriani

 */
class dashboard_Model extends configSettings {
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

	public function dashboard_summary() {
		//select the database
		mssql_select_db($this->dbname, $this->dbcon);

		//SQL Select statement
		$xml = new XMLHandler(XML_DIR . "web_request_spk.xml");
		$sql_from_xml = $xml->getNode("dashboard_summary");
		$sqlselect = str_replace("FILTER_BY_PROGRAM","'".$_SESSION["user-ws"]."'",$sql_from_xml);

		//Run the SQL query
		$result=mssql_query($sqlselect);
		$numfields = mssql_num_fields($result);

		$jsonString = "{\"dt_objectTable\": {\"dt_fieldsCollection\":[@fieldsCollection@],\"tableName\":\"dashboard_summary\",\"modify_status\":\"ORI\"}}";
		$fieldsCollection= "";

		while ($row = mssql_fetch_row($result)) {
			if( $fieldsCollection!="" ){
				$fieldsCollection .= ",";
			}
			$fieldsCollection .= "{\"fieldName\":\"".$row[0]."\",\"fieldValue\":\"".$row[1]."\"}";
		}
		$jsonString = str_replace("@fieldsCollection@",$fieldsCollection,$jsonString);
		return $jsonString;
	}

	public function salesbyfincoy_summary() {
		//select the database
		mssql_select_db($this->dbname, $this->dbcon);

		//SQL Select statement
		$xml = new XMLHandler(XML_DIR . "web_request_spk.xml");
		$sql_from_xml = $xml->getNode("salesbyfincoy_summary");
		$sqlselect = str_replace("FILTER_BY_PROGRAM","year(getdate()), month(getdate()),'".$_SESSION["user-ws"]."'",$sql_from_xml);

		//Run the SQL query
		$result=mssql_query($sqlselect);
		//$numfields = mssql_num_fields($result);

		$colorCollection = array(
				'0' => "#f56954",
				'1'  => "#00a65a",
				'2'  => "#f39c12",
				'3'  => "#00c0ef",
				'4'  => "#3c8dbc",
				'5'  => "#d2d6de",
				'6'  => "#423F3F",
				'7'  => "#B43ADC",
		);

		$jsonString = "[";
		$fieldsCollection= "";
		$counter = 0;
		while ($row = mssql_fetch_row($result)) {
			if ($counter==8){
				$counter = 0;
			}
			if( $fieldsCollection!="" ){
				$fieldsCollection .= ",";
			}
			$fieldsCollection .= "{\"value\":\"".$row[1]."\",\"color\":\"".$colorCollection[$counter]."\",\"highlight\":\"".$colorCollection[$counter]."\",\"label\":\"".$row[0]."\"}";
			$counter++;
		}
		$jsonString .= 	$fieldsCollection."]";
		return $jsonString;
	}

	public function salesstatistik_summary($filterby) {
		//select the database
		mssql_select_db($this->dbname, $this->dbcon);

		//SQL Select statement
		$xml = new XMLHandler(XML_DIR . "web_request_spk.xml");
		$sql_from_xml = $xml->getNode("salesstatistik_summary");
		$sqlselect = str_replace("FILTER_BY_PROGRAM","year(getdate()),'".$_SESSION["user-ws"]."'",$sql_from_xml);
		$sqlselect = $sqlselect." WHERE TIPE='".$filterby."' ";
		//Run the SQL query
		$result=mssql_query($sqlselect);
		//$numfields = mssql_num_fields($result);

		$jsonString = "[";
		$fieldsCollection= "";
		$counter = 0;
		while ($row = mssql_fetch_row($result)) {

			if( $fieldsCollection!="" ){
				$fieldsCollection .= ",";
			}
			$fieldsCollection .=(string)$row[2];
			$counter++;
		}

		for($i=$counter;$i<6;$i++){
			if( $fieldsCollection!="" ){
				$fieldsCollection .= ",";
			}
			$fieldsCollection .= 0;
		}

		$jsonString .= 	$fieldsCollection."]";
		return $jsonString;
	}


  public function __destruct() {
 		unset($this);
 	}

}
