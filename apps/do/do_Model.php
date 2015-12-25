<?php

/* author   : edo satriani

 */
class do_Model extends configSettings {
	public $dbcon;

	public $dbhost;
	public $dbuser;
	public $dbpass;
	public $dbname;

	public $modify_status;

	public function __construct() {

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

	public function get_spk($no_spk) {
		//select the database
		mssql_select_db($this->dbname, $this->dbcon);

		//SQL Select statement
		$xml = new XMLHandler(XML_DIR . "web_request_spk.xml");
		$sql_from_xml = $xml->getNode("browsepo");
		$sqlselect = str_replace("FILTER_BY_PROGRAM"," AND TR_SALE_SPK.NO_SPK='$no_spk'",$sql_from_xml);

		//echo $sqlselect."<br>";
		//Run the SQL query
		$result=mssql_query($sqlselect);
		$numfields = mssql_num_fields($result);

		$jsonString = "{\"dt_objectTable\": [{\"dt_fieldsCollection\":[@fieldsCollection@],\"tableName\":\"SPK\",\"modify_status\":\"ORI\"}]}";
		$fieldsCollection= "";
		$cust_beli="";
		$cust_stnk="";
		$cust_kirim="";

		while ($row = mssql_fetch_row($result)) {
			for ($i=0; $i < $numfields; $i++)
			{
				if( $fieldsCollection!="" ){
					$fieldsCollection .= ",";
				}

				$fieldType =  mssql_field_type($result, $i);

				if( $fieldType == "datetime" ){
					$dateVal = strtotime($row[$i]);
					$fieldsCollection .= "{\"fieldName\":\"".mssql_field_name($result, $i)."\",\"fieldValue\":\"".date('m/d/Y',$dateVal)."\"}";
				}else{
					$fieldsCollection .= "{\"fieldName\":\"".mssql_field_name($result, $i)."\",\"fieldValue\":\"".$row[$i]."\"}";
				}
			}
		}

		$jsonString = str_replace("@fieldsCollection@",$fieldsCollection,$jsonString);
		return $jsonString;
	}


	public function get_active_fincoy() {
		//select the database
		mssql_select_db($this->dbname, $this->dbcon);

		//SQL Select statement
		$xml = new XMLHandler(XML_DIR . "ms_parti.xml");
		$sql_from_xml = $xml->getNode("leasing");

		$sqlselect = $sql_from_xml;

		//Run the SQL query
		$sqlquery = mssql_query($sqlselect);
		$string_result= "<option value>----- select -----</option>";
		while ($result = mssql_fetch_array($sqlquery))	{
			$string_result .= '<option value="'.$result["KODE_LEASING"].'">'.$result["KODE_LEASING"].'</option>';
		}
		return $string_result;
	}



	public function outstanding_do() {
		//select the database
		mssql_select_db($this->dbname, $this->dbcon);

		//SQL Select statement
		$xml = new XMLHandler(XML_DIR . "web_request_spk.xml");
		$sql_from_xml = $xml->getNode("outstanding_do");

		$sqlselect = str_replace("FILTER_BY_PROGRAM","",$sql_from_xml);

		//Run the SQL query
		$result=mssql_query($sqlselect);
		$numfields = mssql_num_fields($result);

		$string_result= "<table class=\"table table-hover\"><thead><tr>";

		$string_result = "";
		$string_result .= "<div class=\"box\">";
		$string_result .= "	<div class=\"box-header\">";
        $string_result .= "         <h3 class=\"box-title\">Current Outstanding Distribution</h3>";
        $string_result .= "        </div><!-- /.box-header -->";
        $string_result .= "        <div class=\"box-body\">";
        $string_result .= "          <table id=\"example1\" class=\"table table-bordered table-striped\">";
        $string_result .= "            <thead>";
        $string_result .= "              <tr>";


		for ($i=0; $i < $numfields; $i++)
		{
			$string_result .= "<th>".mssql_field_name($result, $i)."</th>";
		}
		$string_result .= "              </tr>";
		$string_result .= "            </thead>";
        $string_result .= "            <tbody>";

		while ($row = mssql_fetch_row($result)) {

			$string_result .= "<tr>";

			for ($i=0; $i < $numfields; $i++)
			{
				if($i==0){
					$string_result .= "<th scope=\"row\"><a href=\"load-order-for_".$row[$i]."\">".$row[$i]."</a></th>";
				}else{
					$string_result .= "<td>".$row[$i]."</td>";
				}
			}

			$string_result .= "</tr>";

		}


		$string_result .= "			 </tbody>";
		$string_result .= "		  </table>";
        $string_result .= "        </div><!-- /.box-body -->";
        $string_result .= "      </div><!-- /.box -->";

		return $string_result;
	}

  public function __destruct() {
 		unset($this);
 	}

}
