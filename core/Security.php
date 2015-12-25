<?php

class Security {

	private $var;

	public function shield($var = "") {

		$this->var = $var;

		$this->Level1($this->var);
		$this->Level2($this->var);
		$this->Level3($this->var);

		return $this->fix();

	}

	public function fix() {
		return $this->__toString();
	}

	public function Level1($str) {
		$this->var = htmlentities($str);
		return $this->var;
	}

	public function Level2($str) {

		$this->var = str_replace("'", "", $this->var);
		//$this->var = str_replace(".", "", $this->var);
		$this->var = str_replace("..", "", $this->var);
		$this->var = str_replace("/", "", $this->var);
		$this->var = str_replace("%20", "", $this->var);
		$this->var = str_replace("__", "", $this->var);
		return $this->var;
	}

	public function Level3($str) {
		$this->var = str_replace("document.cookie", "", $this->var);
		$this->var = str_replace("alert(*)", "", $this->var);
		return $this->var;
	}

	public function __toString() {
		return (string)$this->var;
	}

	public function __destruct() {
		unset ($this->var);
	}

}
