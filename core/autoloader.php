<?php

class autoloader {
	
	protected $class;
	public $app;
	
	public function __construct($app = "") {
		
		$this->app = $app;
		spl_autoload_register(array($this, "app"));
						
	}

	public function app($class) {
	
		$this->class = $class;
		if(file_exists(LOCAL_DIR . "/apps/" . $this->app . "/" . $this->class . ".php")) {
			require_once(LOCAL_DIR . "/apps/" . $this->app ."/" . $this->class . ".php");
		}

	}

	public function all($class) {
	
		$this->class = $class;
		if(file_exists(LOCAL_DIR . "/apps/" . $this->app . "/" . $this->class . ".php")) {
			require_once(LOCAL_DIR . "/apps/" . $this->app ."/" . $this->class . ".php");
		}
	
	}
	


	    
}
