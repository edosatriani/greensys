<?php

class boot {

	protected $class;

	public function __construct() {
		
		spl_autoload_register(array($this, "core"));

	}

	public function core($class) {

		$this->class = $class;
		if(file_exists(LOCAL_DIR . "/core/" . $this->class . ".php")) {
			require_once(LOCAL_DIR . "/core/" . $this->class . ".php");
		}


	}


}
