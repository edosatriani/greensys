<?php
//if (session_id==""){
  session_start();
//}
error_reporting(0);

//devin test
//test ke2
//test ke3
//test 4

$dir = dirname(__FILE__);
$dir = str_replace("\\", "/", $dir);
$xmldir = "D:/H1 PSL/virtu/xml/query/";

define("LOCAL_DIR", $dir);
define("XML_DIR", $xmldir);

require_once(LOCAL_DIR . "/core/Router.php");

$objRouter = new Router();
$objRouter->init();


?>
