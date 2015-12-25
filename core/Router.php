<?php
//require_once(LOCAL_DIR . "/core/Security.php");
require_once(LOCAL_DIR . "/core/boot.php");
//require_once(LOCAL_DIR . "/core/objectupdate.php");

class Router {

	public $local_name;
	public $app;

	public $message;


	function __constructor() {
			//session_start();
	}

	function init() {
		$init = new boot();

		if(isset($_GET['app'])) {
			if(empty($_GET['app'])) {
				header("Location: dashboard");
			}

			//$sr = $_GET['sr'];
			if(!isset($_SESSION['user-id'])) {
				$app_get = "login";
			}else{
				$security = new Security();
				$app_get = $security->shield($_GET['app']);
				$app_get_sub = $security->shield($_GET['sub_app']);
			}

			switch ($app_get) {
				case $app_get:
					if(file_exists(LOCAL_DIR . "/apps/" . $app_get . "/" . $app_get . "_Controller.php")) {
						$ldr = new autoloader($app_get);

						if ($app_get_sub){
							$dynamic = "ajax_" . $app_get . "_Controller";
						}else{
							$dynamic = $app_get . "_Controller";
							$weblog = new objectupdate();
							$result = $weblog->record_webaccess_log("module : ".$app_get);
						}

						$object = new $dynamic();
						if ($app_get == "object") {
							$object->breakJSONObject();
						}
						if ($app_get == "ob_update") {
							$object->breakJSONObject();
						}

					}else {
						if( $app_get == "logout") {
							//unset($_SESSION["user-id"]);
							session_destroy();
							//setcookie("user-id","", time()-3600);
							header("Location: login");
						}else{
							$weblog = new objectupdate();
							$result = $weblog->record_webaccess_log("Accessing wrong module");
							header("Location: page-not-found");
						}
					}
				break;

			}

		} else {
			$weblog = new objectupdate();
			$result = $weblog->record_webaccess_log("Accessing wrong module");

			header("Location: dashboard");
		}


	}


}
