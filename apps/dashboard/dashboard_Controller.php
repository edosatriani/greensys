<?php

class dashboard_Controller {

    public function __construct() {
        $model = new dashboard_Model;
		    //$userinfo =   $model->userInformation();
        $json_dashboard_summary = $model->dashboard_summary();
        $json_salesbyfincoy_summary = $model->salesbyfincoy_summary();
        $array_salesstatistik_0_summary = $model->salesstatistik_summary('SALES');
        $array_salesstatistik_1_summary = $model->salesstatistik_summary('FAKTUR');
        //echo $json_dashboard_summary;
        $jfo = json_decode($json_dashboard_summary);

  			$spk = 0;
  			$sales = 0;
        $faktur = 0;
        $tagihan = 0;

  			$dt_fields = $jfo->dt_objectTable->dt_fieldsCollection;
        if (is_array($dt_fields) || is_object($dt_fields)){
    			foreach ($dt_fields as $fields){
            switch(strtolower($fields->fieldName)){
              case "spk":
                  $spk = $fields->fieldValue;
                  break;
              case "sales":
    				      $sales =  $fields->fieldValue;
                  break;
              case "faktur":
  				        $faktur =  $fields->fieldValue;
                  break;
              case "tagihan":
                  $tagihan =  $fields->fieldValue;
                  break;
            }
    			}
        }
        require_once 'dashboard_View.php';
    }

}
