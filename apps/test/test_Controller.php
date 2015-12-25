<?php
 
class test_Controller {

    public function __construct() {
		$model = new test_Model;
       
        $shield = new Security();
		$modify_status = "INS";
        $readonlymode = "";
		
		if($_GET['action']=="dropsession"){
			session_destroy();
		}
		// Start the session
		session_start();
		
		if (!isset($_SESSION["session_id"]) && empty($_SESSION["session_id"])){
			
			$_SESSION["session_id"] = md5(microtime().$_SERVER['REMOTE_ADDR']);
		}
		$autonumber = "";
		if($modify_status == "INS") {
			$autonumbermode = $model->getConfiguration("100");
			if ($autonumbermode == "YES") {
				$autonumberformat = "1234";
				$autonumber = $model->AutoNumberWithFormat("EP0101","NO_MC",$autonumberformat.date("ym"),"4");
			}else{
				$autonumber = "";
			}
		}else{
			$readonlymode = "readonly";
		}
		
		$active_size = $model->get_active_size();
		$activesession = $_SESSION["session_id"];
		$countactivetrans =  $model->count_activetrans($activesession);
		$activetrans = $model->get_activetrans($activesession);
		$currentactivesize=$_SESSION["size_id"];
		
		require_once 'test_View.php';
    }
 
}