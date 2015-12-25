<?php

/* author   : edo satriani

 */
class spk_Model extends configSettings {
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
		$sql_from_xml = $xml->getNode("browsespk");
		$sqlselect = str_replace("FILTER_BY_PROGRAM"," WHERE NO_SPK='$no_spk'",$sql_from_xml);

		//echo $sqlselect."<br>";
		//Run the SQL query
		$result=mssql_query($sqlselect);
		$numfields = mssql_num_fields($result);

		$jsonString = "{\"dt_objectTable\": [{\"dt_fieldsCollection\":[@fieldsCollection@],\"dt_relatedTables\":[@relfieldsCollection@],\"tableName\":\"SPK\",\"modify_status\":\"ORI\"}]}";
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

				if(mssql_field_name($result, $i)=="CUSTOMER_BELI"){
					$cust_beli=$row[$i];
				}

				if(mssql_field_name($result, $i)=="CUSTOMER_STNK"){
					$cust_stnk=$row[$i];
				}

				if(mssql_field_name($result, $i)=="CUSTOMER_KIRIM"){
					$cust_kirim=$row[$i];
				}
			}
		}

		$relfieldsCollectionGroup = "";
		$relfieldsCollection= "";

		if($cust_beli!=""){
			$sql_from_xml = $xml->getNode("customer_beli");
			$sqlselect = str_replace("FILTER_BY_PROGRAM"," WHERE KODE_CUSTOMER='$cust_beli'",$sql_from_xml);
			$result=mssql_query($sqlselect);
			$numfields = mssql_num_fields($result);

			while ($row = mssql_fetch_row($result)) {
				for ($i=0; $i < $numfields; $i++)
				{
					if( $relfieldsCollection!="" ){
						$relfieldsCollection .= ",";
					}

					$fieldType =  mssql_field_type($result, $i);

					if( $fieldType == "datetime" ){
						$dateVal = strtotime($row[$i]);
						$relfieldsCollection .= "{\"fieldName\":\"".mssql_field_name($result, $i)."\",\"fieldValue\":\"".date('m/d/Y',$dateVal)."\"}";
					}else{
						$relfieldsCollection .= "{\"fieldName\":\"".mssql_field_name($result, $i)."\",\"fieldValue\":\"".$row[$i]."\"}";
					}
				}
			}
			$this->modify_status="UPD";
		}

		if($relfieldsCollection!=""){
			$relfieldsCollectionGroup = "{\"dt_relatedTable\":[{\"dt_relfieldCollection\":[".$relfieldsCollection."],\"tableName\":\"BELI\"}]}";
		}

		$relfieldsCollection= "";

		if($cust_stnk!=""){
			$sql_from_xml = $xml->getNode("customer_stnk");
			$sqlselect = str_replace("FILTER_BY_PROGRAM"," WHERE KODE_CUSTOMER='$cust_stnk'",$sql_from_xml);
			$result=mssql_query($sqlselect);
			$numfields = mssql_num_fields($result);

			while ($row = mssql_fetch_row($result)) {
				for ($i=0; $i < $numfields; $i++)
				{
					if( $relfieldsCollection!="" ){
						$relfieldsCollection .= ",";
					}

					$fieldType =  mssql_field_type($result, $i);

					if( $fieldType == "datetime" ){
						$dateVal = strtotime($row[$i]);
						$relfieldsCollection .= "{\"fieldName\":\"".mssql_field_name($result, $i)."\",\"fieldValue\":\"".date('m/d/Y',$dateVal)."\"}";
					}else{
						$relfieldsCollection .= "{\"fieldName\":\"".mssql_field_name($result, $i)."\",\"fieldValue\":\"".$row[$i]."\"}";
					}
				}
			}

			if($relfieldsCollection!=""){
				$relfieldsCollectionGroup = $relfieldsCollectionGroup.","."{\"dt_relatedTable\":[{\"dt_relfieldCollection\":[".$relfieldsCollection."],\"tableName\":\"STNK\"}]}";
			}

		}

		$relfieldsCollection= "";

		if($cust_kirim!=""){
			$sql_from_xml = $xml->getNode("customer_kirim");
			$sqlselect = str_replace("FILTER_BY_PROGRAM"," WHERE KODE_CUSTOMER='$cust_kirim'",$sql_from_xml);
			$result=mssql_query($sqlselect);
			$numfields = mssql_num_fields($result);

			while ($row = mssql_fetch_row($result)) {
				for ($i=0; $i < $numfields; $i++)
				{
					if( $relfieldsCollection!="" ){
						$relfieldsCollection .= ",";
					}

					$fieldType =  mssql_field_type($result, $i);

					if( $fieldType == "datetime" ){
						$dateVal = strtotime($row[$i]);
						$relfieldsCollection .= "{\"fieldName\":\"".mssql_field_name($result, $i)."\",\"fieldValue\":\"".date('m/d/Y',$dateVal)."\"}";
					}else{
						$relfieldsCollection .= "{\"fieldName\":\"".mssql_field_name($result, $i)."\",\"fieldValue\":\"".$row[$i]."\"}";
					}
				}
			}

			if($relfieldsCollection!=""){
				$relfieldsCollectionGroup = $relfieldsCollectionGroup.","."{\"dt_relatedTable\":[{\"dt_relfieldCollection\":[".$relfieldsCollection."],\"tableName\":\"KIRIM\"}]}";
			}

		}


		$jsonString = str_replace("@fieldsCollection@",$fieldsCollection,$jsonString);
		$jsonString = str_replace("@relfieldsCollection@",$relfieldsCollectionGroup,$jsonString);
		return $jsonString;
	}



	public function get_active_unittype() {
		//select the database
		mssql_select_db($this->dbname, $this->dbcon);

		//SQL Select statement
		$xml = new XMLHandler(XML_DIR . "ms_unit.xml");
		$sql_from_xml = $xml->getNode("tipe");
		$sqlselect = $sql_from_xml;

		//Run the SQL query
		$sqlquery = mssql_query($sqlselect);

		// $string_result= "<option value>----- select -----</option>";
		// while ($result = mssql_fetch_array($sqlquery))	{
			// $string_result .= '<option value="'.$result["KODE_TIPE"].'">'.$result["NAMA_TIPE"].'</option>';
		// }

		$string_result= "[";
		while ($result = mssql_fetch_array($sqlquery))	{
			if ($string_result != "[") {
				$string_result .= ",";
			}
			$string_result .= "{id:'".$result['KODE_TIPE']."',name:'".$result['NAMA_TIPE']."'}";
		}
		$string_result .= "]";
		return $string_result;
	}

	public function get_active_unitcolour($unittype) {
		//select the database
		mssql_select_db($this->dbname, $this->dbcon);

		//SQL Select statement
		$xml = new XMLHandler(XML_DIR . "ms_unit.xml");
		$sql_from_xml = $xml->getNode("warna");
		$sqlselect = $sql_from_xml." WHERE KODE_TIPE='$unittype' ORDER BY NAMA_WARNA";

		//Run the SQL query
		$sqlquery = mssql_query($sqlselect);
		$string_result= "[";
		while ($result = mssql_fetch_array($sqlquery))	{
			if ($string_result != "[") {
				$string_result .= ",";
			}
			$string_result .= '{"id":"'.$result["KODE_WARNA"].'","name":"'.$result["NAMA_WARNA"].'"}';
		}
		$string_result .= "]";
		return $string_result;
	}

	public function get_active_unitprice($unittype,$priceindex) {
		//select the database
		mssql_select_db($this->dbname, $this->dbcon);

		//SQL Select statement
		$xml = new XMLHandler(XML_DIR . "ms_unit.xml");
		$sql_from_xml = $xml->getNode("browseharga");

		$sqlselect = str_replace("FILTER_BY_PROGRAM"," WHERE MS_UNIT_HARGA.KODE_TIPE='$unittype' AND MS_UNIT_HARGA.AKTIF='Y' ",$sql_from_xml);

		//Run the SQL query
		$sqlquery = mssql_query($sqlselect);
		$string_result= "0";
		while ($result = mssql_fetch_array($sqlquery))	{
			switch($priceindex){
				case "1":
					$string_result = number_format($result["HARGA_OTR"]);
					break;
				case "2":
					$string_result = number_format($result["HARGA_KOSONG"]);
					break;
				case "3":
					$string_result = number_format($result["HARGA_DEALER"]);
					break;
			}
		}
		return $string_result;
	}

	public function get_active_salestype() {
		//select the database
		mssql_select_db($this->dbname, $this->dbcon);

		//SQL Select statement
		$xml = new XMLHandler(XML_DIR . "ms_parti.xml");
		$sql_from_xml = $xml->getNode("jenissales");

		$sqlselect = $sql_from_xml;
		//Run the SQL query
		$sqlquery = mssql_query($sqlselect);
		$string_result = '<option value></option>';
		while ($result = mssql_fetch_array($sqlquery))	{
			$string_result .= '<option value="'.$result["JENIS_SALES"].'">'.$result["JENIS_SALES"].'</option>';
		}
		return $string_result;
	}

	public function get_active_supervisor($salestype) {
		//select the database
		mssql_select_db($this->dbname, $this->dbcon);

		//SQL Select statement

		$xml = new XMLHandler(XML_DIR . "ms_parti.xml");
		$sql_from_xml = $xml->getNode("groupsales");

		switch($salestype){
			case "COUNTER":
				$sqlselect = str_replace("FILTER_BY_PROGRAM"," AND (GRUP.JABATAN='COUNTER')",$sql_from_xml);
				break;
			case "CHANNEL":
				$sqlselect = str_replace("FILTER_BY_PROGRAM"," AND (GRUP.JABATAN='SPV CHANNEL')",$sql_from_xml);
				break;
			case "DEALER LAIN":
				$sql_from_xml = $xml->getNode("dealer");
				$sqlselect = $sql_from_xml;
				break;
			default:
				$sqlselect = str_replace("FILTER_BY_PROGRAM"," AND (GRUP.JABATAN='SPV SALES')",$sql_from_xml);
				break;
		}
		//Run the SQL query
		$sqlquery = mssql_query($sqlselect);

		// $string_result = "<option value>----- select -----</option>";
		// while ($result = mssql_fetch_array($sqlquery))	{
			// $string_result .= '<option value="'.$result["SALES_ID"].'_'.$result["NOMOR_GROUP"].'_'.$result["JABATAN"].'">'.$result["NAMA"].'</option>';
		// }

		$string_result= "[";
		while ($result = mssql_fetch_array($sqlquery))	{
			if ($string_result != "[") {
				$string_result .= ",";
			}
			if($salestype == "COUNTER"){
				$string_result .= '{"id":"'.$result["SALES_ID"].'_'.$result["NOMOR_GROUP"].'_COUNTER","name":"'.$result["NAMA"].'"}';
			}else{
				$string_result .= '{"id":"'.$result["SALES_ID"].'_'.$result["NOMOR_GROUP"].'","name":"'.$result["NAMA"].'"}';
			}


		}
		$string_result .= "]";

		return $string_result;
	}

	public function get_active_koordinator($salestype,$spvid) {
		//select the database
		mssql_select_db($this->dbname, $this->dbcon);

		//SQL Select statement

		$xml = new XMLHandler(XML_DIR . "ms_parti.xml");
		$sql_from_xml = $xml->getNode("groupsales");

		switch($salestype){
			case "CHANNEL":
				$sqlselect = str_replace("FILTER_BY_PROGRAM"," AND (SUBSTRING(NOMOR_GROUP,1,3)='$spvid')",$sql_from_xml);
				break;
			default:
				$sqlselect = str_replace("FILTER_BY_PROGRAM"," AND (SUBSTRING(NOMOR_GROUP,1,3)='$spvid') AND NOMOR_GROUP <> '$spvid' AND GRUP.JABATAN NOT IN ('SALES', 'COUNTER')",$sql_from_xml);
				break;
		}
		//Run the SQL query
		$sqlquery = mssql_query($sqlselect);

		// $string_result= "<option value>----- select -----</option>";
		// while ($result = mssql_fetch_array($sqlquery))	{
			// $string_result .= '<option value="'.$result["SALES_ID"].'_'.$result["NOMOR_GROUP"].'_'.$result["JABATAN"].'">'.$result["NAMA"].'</option>';
		// }

		$string_result= "[";
		while ($result = mssql_fetch_array($sqlquery))	{
			if ($string_result != "[") {
				$string_result .= ",";
			}
			$string_result .= '{"id":"'.$result["SALES_ID"].'_'.$result["NOMOR_GROUP"].'","name":"'.$result["NAMA"].'"}';
		}
		$string_result .= "]";

		return $string_result;
	}

	public function get_active_sales($salestype,$spvid,$koorid,$state) {
		//select the database
		mssql_select_db($this->dbname, $this->dbcon);

		//SQL Select statement

		$xml = new XMLHandler(XML_DIR . "ms_parti.xml");
		$sql_from_xml = $xml->getNode("groupsales");

		if($state == "0"){
			if($salestype=="SALES"){
				$sqlselect = str_replace("FILTER_BY_PROGRAM","  AND (SUBSTRING(NOMOR_GROUP,1,7)='$koorid')",$sql_from_xml);
			}else{
				$sqlselect = str_replace("FILTER_BY_PROGRAM","  AND (SUBSTRING(NOMOR_GROUP,1,3)='$spvid')",$sql_from_xml);
			}
		}else{
			$sqlselect = str_replace("FILTER_BY_PROGRAM","  AND (SUBSTRING(NOMOR_GROUP,1,7)='".$spvid.".000' OR NOMOR_GROUP ='$spvid')",$sql_from_xml);
		}
		//Run the SQL query
		$sqlquery = mssql_query($sqlselect);

		// $string_result= "<option value>----- select -----</option>";
		// while ($result = mssql_fetch_array($sqlquery))	{
			// $string_result .= '<option value="'.$result["SALES_ID"].'_'.$result["NOMOR_GROUP"].'_'.$result["JABATAN"].'">'.$result["NAMA"].'</option>';
		// }

		$string_result= "[";
		while ($result = mssql_fetch_array($sqlquery))	{
			if ($string_result != "[") {
				$string_result .= ",";
			}
			$string_result .= '{"id":"'.$result["SALES_ID"].'_'.$result["NOMOR_GROUP"].'_'.$result["JABATAN"].'","name":"'.$result["NAMA"].'"}';
		}
		$string_result .= "]";


		return $string_result;
	}

	public function get_active_stocklocation() {
		//select the database
		mssql_select_db($this->dbname, $this->dbcon);

		//SQL Select statement
		$xml = new XMLHandler(XML_DIR . "spk.xml");
		$sql_from_xml = $xml->getNode("asalspk");

		$sqlselect = $sql_from_xml;

		//Run the SQL query
		$sqlquery = mssql_query($sqlselect);
		$string_result= "<option value>----- select -----</option>";
		while ($result = mssql_fetch_array($sqlquery))	{
			$string_result .= '<option value="'.$result["KODE_LOKASI"].'">'.$result["NAMA_LOKASI"].'</option>';
		}
		return $string_result;
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

	public function get_active_wilayah() {
		//select the database
		mssql_select_db($this->dbname, $this->dbcon);

		//SQL Select statement
		$xml = new XMLHandler(XML_DIR . "ms_regional.xml");
		$sql_from_xml = $xml->getNode("wilayah");

		$sqlselect = $sql_from_xml;

		//Run the SQL query
		$sqlquery = mssql_query($sqlselect);
		$string_result= "<option value>----- select -----</option>";
		while ($result = mssql_fetch_array($sqlquery))	{
			$string_result .= '<option value="'.$result["KODE_WILAYAH"].'">'.$result["NAMA_WILAYAH"].'</option>';
		}
		return $string_result;
	}

	public function get_active_kecamatan($wilid) {
		//select the database
		mssql_select_db($this->dbname, $this->dbcon);

		//SQL Select statement
		$xml = new XMLHandler(XML_DIR . "ms_regional.xml");
		$sql_from_xml = $xml->getNode("kecamatan");

		$sqlselect = str_replace("FILTER_BY_PROGRAM"," WHERE KODE_WILAYAH='$wilid' ",$sql_from_xml);

		//Run the SQL query
		$sqlquery = mssql_query($sqlselect);

		// $string_result= "<option value>----- select -----</option>";
		// while ($result = mssql_fetch_array($sqlquery))	{
			// $string_result .= '<option value="'.$result["KODE_KECAMATAN"].'">'.$result["NAMA_KECAMATAN"].'</option>';
		// }

		$string_result= "[";
		while ($result = mssql_fetch_array($sqlquery))	{
			if ($string_result != "[") {
				$string_result .= ",";
			}
			$string_result .= '{"id":"'.$result["KODE_KECAMATAN"].'","name":"'.$result["NAMA_KECAMATAN"].'"}';
		}
		$string_result .= "]";
		return $string_result;
	}

	public function get_active_kelurahan($kecid) {
		//select the database
		mssql_select_db($this->dbname, $this->dbcon);

		//SQL Select statement
		$xml = new XMLHandler(XML_DIR . "ms_regional.xml");
		$sql_from_xml = $xml->getNode("kelurahan");

		$sqlselect = str_replace("FILTER_BY_PROGRAM"," WHERE KODE_KECAMATAN='$kecid' ",$sql_from_xml);

		//Run the SQL query
		$sqlquery = mssql_query($sqlselect);

		// $string_result= "<option value>----- select -----</option>";
		// while ($result = mssql_fetch_array($sqlquery))	{
			// $string_result .= '<option value="'.$result["KODE_KELURAHAN"].'">'.$result["NAMA_KELURAHAN"].'</option>';
		// }

		$string_result= "[";
		while ($result = mssql_fetch_array($sqlquery))	{
			if ($string_result != "[") {
				$string_result .= ",";
			}
			$string_result .= '{"id":"'.$result["KODE_KELURAHAN"].'","name":"'.$result["NAMA_KELURAHAN"].'"}';
		}
		$string_result .= "]";
		return $string_result;
	}

	public function get_inqury_filter() {

		$xml = new XMLHandler(XML_DIR . "inquiry.xml");
		$string_from_xml = $xml->getNode("inquiryfield");

		$arrFilter = split (";",$string_from_xml);

		$string_result= "";
		foreach( $arrFilter as $value )
         {
            $string_result .=   '<option value="'.$value.'">'.$value.'</option>';
         }

		return $string_result;
	}

	public function run_inquiry($filter,$criteria) {
		//select the database
		mssql_select_db($this->dbname, $this->dbcon);

		//SQL Select statement
		$xml = new XMLHandler(XML_DIR . "inquiry.xml");
		$sql_from_xml = $xml->getNode("shortinquiry");

		$sqlselect = str_replace("FILTER_BY_PROGRAM"," WHERE ".$filter." like '%".$criteria."%' ",$sql_from_xml);

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
					$string_result .= "<th scope=\"row\"><a href=\"javascript:runInquiryDetail('".$row[$i]."')\">".$row[$i]."</a></th>";
				}else{
					$string_result .= "<td>".$row[$i]."</td>";
				}
			}

			$string_result .= "</tr>";

		}
		$string_result .= "</tbody></table>";

		return $string_result;
	}

	public function run_inquirydetail($criteria) {
		//select the database
		mssql_select_db($this->dbname, $this->dbcon);

		//SQL Select statement
		$xml = new XMLHandler(XML_DIR . "inquiry.xml");
		$sql_from_xml = $xml->getNode("inquiry");

		$sqlselect = str_replace("FILTER_BY_PROGRAM"," WHERE SPK.NO_SPK = '".$criteria."' ",$sql_from_xml);

		//Run the SQL query
		$result=mssql_query($sqlselect);
		$numfields = mssql_num_fields($result);

		$string_result= "";

		while ($row = mssql_fetch_row($result)) {

			for ($i=0; $i < $numfields; $i++)
			{
				$fieldType =  mssql_field_type($result, $i);

				if( $fieldType == "datetime" ){
					if($row[$i]!=null){
						$dateVal = strtotime($row[$i]);
						$string_result .= "<tr><th scope=\"row\">".mssql_field_name($result, $i)."</th><td>:</td><td>".date('d-m-Y',$dateVal)."</td></tr>";
					}else{
						$string_result .= "<tr><th scope=\"row\">".mssql_field_name($result, $i)."</th><td>:</td><td>-</td></tr>";
					}
				} else {
					if($i==0){
							$string_result .= "<tr><th scope=\"row\">".mssql_field_name($result, $i)."</th><td>:</td><td>".$row[$i]." <a href='loadspk_".$criteria."'><button type=\"button\" class=\"btn btn-info btn-xs\">edit</button></a></td></tr>";
					}else{
						$string_result .= "<tr><th scope=\"row\">".mssql_field_name($result, $i)."</th><td>:</td><td>".$row[$i]."</td></tr>";
					}
				}
			}

		}
		$string_result .= "<tr><th scope=\"row\" colspan=3><a href='loadspk_".$criteria."'><button type=\"button\" class=\"btn btn-info\">edit mode</button></a></th></tr>";

		return $string_result;
	}

  public function __destruct() {
 		unset($this);
 	}

}
