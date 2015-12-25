<?php

class do_Controller {

    public function __construct() {
  		$model = new do_Model;
      $userinfo =   $model->userInformation();

      $shield = new Security();
	    $modify_status = "INS";
      $readonlymode = "";

      if($_GET['param']!=""){
  			$id = $shield->shield($_GET['param']);
  			$datapo = $model->get_spk($id);
  			$modify_status = "UPD";
  		}else{
  			$datapo = "{}";
  		}

		    $autonumber = "";


		$active_fincoy = $model->get_active_fincoy();
		$outstanding_do = $model->outstanding_do();

        require_once 'do_View.php';
    }

}
