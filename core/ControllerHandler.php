<?php


class ControllerHandler {

	public $class;
	
	public function __construct($var) {
		
		$this->init($var);
	
	}
	
	

	
	public function init($var) {
	
		if(isset($_GET['sr'])) {
				
			
			$sr = $_GET['sr'];
			
			if(!isset($_GET['app'])) {
				die(_GET_APP_DONT_EXIST);
			}
			
			//$class_methods = get_class_methods("appController");
			$security = new Security();
			$shield_var = $security->shield($_GET['app']);
			$class_methods = get_class_methods($shield_var . "_Controller");
			//var_dump($class_methods);
				
			foreach ($class_methods as $method_name) {
				//echo "$method_name\n";
				
				
				if(($sr == $method_name)) {	
						
						/**
 						($sr != "__construct") && _
 						($sr != "__call") && _
 						($sr != "__callStatic") && _
 						($sr != "__get") && _
 						($sr != "__set") && _
 						($sr != "__isset") && _
 						($sr != "__unset") && _
 						($sr != "__sleep") && _
 						($sr != "__get") && _
 						($sr != "__wakeup") && _
 						($sr != "__toString") && _
 						($sr != "__invoke") && _
 						($sr != "__destruct")) {
 						**/
 						
					switch($sr) {						
						// llama staticamente
 						//appController::$sr();
 						//appModel::$sr();
 						//AppView::$sr();
						
						case $sr:
						$var->$sr();
						break;
					
						
					} // switch
	
				} // if
					
			} // for each
	
		} else {
			if((!isset($_GET['sr']))) {
					$var->main();
			}
		}
	
	} 
	
	
}
