<?php

class stock_Controller {

    public function __construct() {
		  $model = new stock_Model;
      $userinfo =   $model->userInformation();
      $weblogrow = $model->get_unit_rfs();

  		$weblogdata = "";
  		$weblogdata .= "<div class=\"box\">";
  		$weblogdata .= "	<div class=\"box-header\">";
        $weblogdata .= "         <h3 class=\"box-title\">Stock Ready For Sale</h3>";
        $weblogdata .= "        </div><!-- /.box-header -->";
        $weblogdata .= "        <div class=\"box-body\">";
        $weblogdata .= "          <table id=\"example1\" class=\"table table-bordered table-striped\">";
        $weblogdata .= "            <thead>";
        $weblogdata .= "              <tr>";
        $weblogdata .= "                <th>Tipe</th>";
        $weblogdata .= "                <th>Model</th>";
        $weblogdata .= "                <th>Warna</th>";
		    $weblogdata .= "				        <th>No. Mesin</th>";
        $weblogdata .= "				        <th>No. Rangka</th>";
        $weblogdata .= "                <th>Tahun</th>";
        $weblogdata .= "                <th>Lokasi</th>";
        $weblogdata .= "                <th>Nama Lokasi</th>";
        $weblogdata .= "              </tr>";
        $weblogdata .= "            </thead>";
        $weblogdata .= "            <tbody>";
		$weblogdata .=  $weblogrow;

		$weblogdata .= "			 </tbody>";
		$weblogdata .= "		  </table>";
        $weblogdata .= "        </div><!-- /.box-body -->";
        $weblogdata .= "      </div><!-- /.box -->";

        require_once 'stock_View.php';
    }

}
