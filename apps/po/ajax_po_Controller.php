<?php
	
	// $dir = dirname(__FILE__);
	// $dir = str_replace("\\", "/", $dir);
	// $xmldir = "D:/H1 PSL/virtu/xml/query/";
	 
	// //define("LOCAL_DIR", $dir);
	// define("XML_DIR", $xmldir);
	// define("LOCAL_DIR",$_SERVER['DOCUMENT_ROOT']."/greensys/");
	
	
	
	// spl_autoload_register(function($class){
		// require_once(LOCAL_DIR . "/core/Router.php");
	// });
	
	// spl_autoload_register(function($class){
		// require_once $class . '.php';
	// });
	
	
 class ajax_spk_Controller {
	
	public function __construct() {
		if($_SERVER['HTTP_X_REQUESTED_WITH']=="XMLHttpRequest"){
			$model = new spk_Model;
			$shield = new Security();

			switch($_GET['mode']){
				case "101":
					$id = $shield->shield($_GET['param']);
					$active_response = $model->get_active_unitcolour($id);
					break;
				case "102":
					$id = $shield->shield($_GET['param']);
					$active_response = $model->get_active_supervisor($id);
					break;
				case "103":
					$id = $shield->shield($_GET['param']);
					$id2 = $shield->shield($_GET['param2']);
					$active_response = $model->get_active_koordinator($id,$id2);
					break;
				case "104":
					$id = $shield->shield($_GET['param']);
					$id2 = $shield->shield($_GET['param2']);
					$id3 = $shield->shield($_GET['param3']);
					$id4 = $shield->shield($_GET['param4']);
					$active_response = $model->get_active_sales($id,$id2,$id3,$id4);
					break;
				case "105":
					$id = $shield->shield($_GET['param']);
					$id2 = $shield->shield($_GET['param2']);
					$active_response = $model->get_active_unitprice($id,$id2);
					break;
				case "106":
					$id = $shield->shield($_GET['param']);
					$active_response = $model->get_active_kecamatan($id);
					break;
				case "107":
					$id = $shield->shield($_GET['param']);
					$active_response = $model->get_active_kelurahan($id);
					break;
				case "108":
					$id = $shield->shield($_GET['param']);
					$id2 = $shield->shield($_GET['param2']);
					$active_response = $model->run_inquiry($id,$id2);
					break;
				case "109":
					$id = $shield->shield($_GET['param']);
					$active_response = $model->run_inquirydetail($id);
					break;
			}
		
			echo $active_response;
		}else{
			echo "[{}]";
		}

	}
 }