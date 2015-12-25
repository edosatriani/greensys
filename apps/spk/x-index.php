<?php
 

$dir = dirname(__FILE__);
$dir = str_replace("\\", "/", $dir);
$xmldir = "D:/H1 PSL/virtu/xml/query/";

define("ROOT_DIR",$_SERVER['DOCUMENT_ROOT']."/greensys/");
define("LOCAL_DIR", $dir);
define("XML_DIR", $xmldir);

spl_autoload_register(function($class){
    require_once $class . '.php';
});
 
new Controller;
