<?php


class configSettings {

	public $dbhost;
	public $dbuser;
	public $dbpass;
	public $dbname;

	public function __construct() {

		$this->LoadSettings();

	}


public function LoadSettings() {

	try {
		$xml = new XMLHandler(LOCAL_DIR . "/etc/greenSys.config.xml");

		$this->dbhost = $xml->Child("database", "dbhost");
		$this->dbuser = $xml->Child("database", "dbuser");
		$this->dbpass = $xml->Child("database", "dbpass");
		$this->dbname = $xml->Child("database", "dbname");

		if(isset($_SESSION["activedb"])){
			$this->dbname =$_SESSION["activedb"];
		}

	} catch (Exception $e) {
		$title = "ERROR IN CLASS: " . get_class($this);
		$test = new MsgBox($title, $e->getMessage());
		$this->content .= $test->Show();
	}

}

}
