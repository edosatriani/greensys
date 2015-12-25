<?php



class XMLHandler {

	public $file;
	public $obj;
	public $children;
	public $child;

	public $array;

	public $value;

	public $node;

	public $fix;

	public function __construct($file = "") {

		$this->file = $file;

		if(!file_exists($file)) {
			throw new Exception(get_class($this) . ": File Doesn't exist " . $file);
		}else {
			$this->readXML($file);
		}

	}
	public function readXML($file = "") {

		$this->node = array();

		if(file_exists($file)) {
			$this->obj = simplexml_load_file($file);
		} else {
			throw new Exception(_FILE_DONT_EXIST . " " .$file);
		}

		if(!$this->obj) {
			throw new Exception(_WARNING_LOADING_FILE . " " ."<b>" . $file . "</b>");
		}

	}


	public function Child($child, $subchild) {

		if($this->obj) {

			$_value = 0;

			foreach($this->obj->$child as $key => $value) {
				$_value = $value->$subchild;
				if($_value == "_blank") {
					$_value = "";
				}
			}
		} else {
			throw new Exception(_XML_ERROR_CHILD);
		}

		return $_value;


	}

	public function getNode($key) {

		if($this->obj) {
			$_value = $this->obj->$key;
		} else {
			throw new Exception(_XML_ERROR_CHILD);
		}

		return $_value;
	}

	public function addNode($value) {

		try {
			$xml = (string)simplexml_load_file($this->file);
			//echo "XML:".$xml."-".$this->file;
			$sxe = new SimpleXMLElement($xml);

 			$sxe->addChild($value);

			$this->obj->asXML($this->file);

		} catch (Exception $e) {

		}

	}

	public function editChild($path, $value) {

		try {

			$this->node = $this->obj->xpath($path);

			if(empty($value)) {
				$this->node[0][0] = "_blank";
			} else {
				$this->node[0][0] = htmlspecialchars($value);
			}

			$this->obj->asXML($this->file);

		} catch (Exception $e) {

		}

	}


 	public function __destruct() {
 		unset($this);
 	}


}
