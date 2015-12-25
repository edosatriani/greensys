<?php

class po_Controller {

    public function __construct() {
    		$model = new po_Model;
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
		$outstanding_po = $model->outstanding_po();

        require_once 'po_View.php';
    }

}
