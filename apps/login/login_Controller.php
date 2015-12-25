<?php

class login_Controller {

    public function __construct() {
  		$model = new login_Model;
      $shield = new Security();

      if($_GET["param"]=="exec"){
        $username = $_POST["username"];
  			$myuserid = $shield->shield($username);
        $mypassword = $shield->shield($_POST["password"]);
  			$datauser = $model->get_userinfo($myuserid,$mypassword);
  		}else{
        $datauser ="[]";
      }

      require_once 'login_View.php';
    }

}
?>
