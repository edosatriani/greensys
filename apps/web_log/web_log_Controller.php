<?php

class web_log_Controller {

    public function __construct() {
		  $model = new web_log_Model;
      $userinfo =   $model->userInformation();
      $weblogrow = $model->get_web_log();

  		$weblogdata = "";
  		$weblogdata .= "<div class=\"box\">";
  		$weblogdata .= "	<div class=\"box-header\">";
        $weblogdata .= "         <h3 class=\"box-title\">Current Visitor Log</h3>";
        $weblogdata .= "        </div><!-- /.box-header -->";
        $weblogdata .= "        <div class=\"box-body\">";
        $weblogdata .= "          <table id=\"example1\" class=\"table table-bordered table-striped\">";
        $weblogdata .= "            <thead>";
        $weblogdata .= "              <tr>";
        $weblogdata .= "                <th>Timestamp</th>";
        $weblogdata .= "                <th>IP Address</th>";
        $weblogdata .= "                <th>Platform(s)</th>";
		$weblogdata .= "				<th>Referer</th>";
        $weblogdata .= "                <th>Activity</th>";
          $weblogdata .= "                <th>User</th>";
        $weblogdata .= "              </tr>";
        $weblogdata .= "            </thead>";
        $weblogdata .= "            <tbody>";
		$weblogdata .=  $weblogrow;

		$weblogdata .= "			 </tbody>";
		$weblogdata .= "		  </table>";
        $weblogdata .= "        </div><!-- /.box-body -->";
        $weblogdata .= "      </div><!-- /.box -->";

        require_once 'web_log_View.php';
    }

}
