<?php
/* author   : edo satriani

 */
class login_Model extends configSettings {
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

	public function get_userinfo($userid,$password) {

		//select the database
		mssql_select_db($this->dbname, $this->dbcon);

		//SQL Select statement
		$sqlselect = "SELECT userid,password,userright,ws,ws_name,location,fullname,gender,position,member_since,avatar FROM pospass WITH (NOLOCK) WHERE userid ='" . $userid ."';";

		//Run the SQL query
		$sqlquery = mssql_query($sqlselect);
    $string_result = '{"uservalidation":"false"}';

		//$login = new Blowfish();
		//$encpassword = $login->genpwd($password);
		$encpassword = $this->encryptIt($password);

		while ($result = mssql_fetch_array($sqlquery)){
				//$verify = $login->verify_hash($password, $result["password"]);

				if ($encpassword == $result["password"]){
          	$xml = new XMLHandler(LOCAL_DIR . "/etc/greenSys.config.xml");
						$_SESSION["activedb"] = (string) $xml->Child("locationmapping", $result["location"]);
						$_SESSION["user-id"] = (string)$userid;
						$_SESSION["user-ws"] = (string)$result["ws"];
						$_SESSION["ws-name"] = (string)$result["ws_name"];
						$_SESSION["user-fullname"] = (string)$result["fullname"];
						$_SESSION["user-gender"] = (string)$result["gender"];
						$_SESSION["user-position"] = (string)$result["position"];
						$_SESSION["user-member_since"] = (string)$result["member_since"];
						$_SESSION["user-avatar"] = (string)$result["avatar"];
						//session_write_close();

						//setcookie("user-id",(string)$userid, time()+3600*24);

          	$string_result = '{"uservalidation":"true","fullname":"'. $result["fullname"].'","gender":"'. $result["gender"].'","position":"'. $result["position"].'","member_since":"'. $result["member_since"].'","avatar":"'. $result["avatar"].'"}';

				}
    }

    return $string_result;
	}

	function encryptIt( $q ) {
	    $cryptKey  = 'qSB0rGbIn5UB1xG03efbCf';
	    $qEncoded      = base64_encode( mcrypt_encrypt( MCRYPT_RIJNDAEL_256, md5( $cryptKey ), $q, MCRYPT_MODE_CBC, md5( md5( $cryptKey ) ) ) );
	    return( $qEncoded );
	}

	public function __destruct() {
 		unset($this);
 	}

}
?>
