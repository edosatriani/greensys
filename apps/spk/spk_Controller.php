<?php

class spk_Controller {

    public function __construct() {
		    $model = new spk_Model;
        $userinfo =   $model->userInformation();

        $shield = new Security();
		    $modify_status = "INS";
        $readonlymode = "";

        if($_GET['param']!=""){
    			$id = $shield->shield($_GET['param']);
    			$dataspk = $model->get_spk($id);
    			$modify_status = $model->modify_status;
    		}else{
    			$dataspk = "{}";
    		}

		$autonumber = "";
		if($modify_status == "INS") {
			$autonumbermode = $model->getConfiguration("100");
			if ($autonumbermode == "YES") {
				$autonumberformat = $model->getConfiguration("107");
				$autonumber = $model->AutoNumberWithFormat("TR_SALE_SPK","NO_SPK",$autonumberformat.date("ym"),"4");
			}else{
				$autonumber = "";
			}
		}else{
			$readonlymode = "readonly";
		}

		$active_wilayah = $model->get_active_wilayah();
		$active_unittype = $model->get_active_unittype();
		$active_salestype =  $model->get_active_salestype();

		$id = $shield->shield($_GET['param']);
		$active_defaultspv =  $model->get_active_supervisor($id);

		$active_stocklocation = $model->get_active_stocklocation();
		$active_fincoy = $model->get_active_fincoy();
		$inquiry_filterfield = $model->get_inqury_filter();

        require_once 'spk_View.php';
    }

}
