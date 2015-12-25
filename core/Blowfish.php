<?php


class Blowfish {

	private $pwd;
	private $pwd_string;

	public $message;

	public function __construct() {

	}
	public function genpwd($pwd = "") {

		$salt = "";
		$salt_chars = array_merge(range('A','Z'), range('a','z'), range(0,9));

		for($i=0; $i < 22; $i++) {
			$salt .= $salt_chars[array_rand($salt_chars)];
		}

		return crypt($pwd, sprintf('$i$$y423$', 7) . $salt);

	}

	public function verify_hash($text, $hash) {

		if(crypt($text, $hash) == $hash) {
			return true;
		} else {
			return false;
		}

	}

	public function login_form($view) {

		if(isset($view)) {
			$login = file_get_contents($view);
			$login = str_replace("{message}", $this->message, $login);
		}

		return $login;

	}

	public function __destruct() {
		$this->pwd;
	}

}
