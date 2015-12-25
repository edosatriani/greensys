<?php
	$template = file_get_contents(LOCAL_DIR."/template/starter.html");
	$template = str_replace("{@menu-title@}","Stock RFS",$template);
	$template = str_replace("{@menu-description@}",$_SESSION["ws-name"],$template);
	$template = str_replace("{@module@}","Home",$template);
	$template = str_replace("{@sub-module@}","Stock RFS",$template);
	/*Default-content, must be exist in all View Object */
	$template = str_replace("{@username@}",$_SESSION["user-fullname"],$template);
	$template = str_replace("{@user-member-since@}",$_SESSION["user-member-since"],$template);
	$template = str_replace("{@user-ws@}",$_SESSION["user-ws"],$template);
	$template = str_replace("{@user-position@}",$_SESSION["user-position"],$template);
	$template = str_replace("{@active-data@}",str_replace("dbH1_","",$_SESSION["activedb"]),$template);
	if($_SESSION["user-avatar"]!=""){
		$user_avatar = $_SESSION["user-avatar"];
	}else{
		if($_SESSION["user-gender"]=="female"){
				$user_avatar = "avatar3.png";
		}else{
				$user_avatar = "avatar5.png";
		}
	}
	$template = str_replace("{@user-avatar@}",$user_avatar,$template);
  /*end of default content*/

	$additionalcss = "<link href=\"assets/css/core.css\" rel=\"stylesheet\">";
  	$template = str_replace("{@additional-css@}",$additionalcss,$template);

	$corejavascript .= "<script src=\"assets/js/global-function.js\"></script>\n";
	$template = str_replace("{@core-javascript@}",$corejavascript,$template);

	$additionaljavascript = "<script src=\"plugins/datatables/jquery.dataTables.min.js\"></script>";
    $additionaljavascript .= "<script src=\"plugins/datatables/dataTables.bootstrap.min.js\"></script>";
	$additionaljavascript .= "<script>".
      "$(function () { " .
       " $(\"#example1\").DataTable(); ".
      "});".
    "</script>";

	$template = str_replace("{@additional-javascript@}",$additionaljavascript,$template);

	$maincontent = file_get_contents(LOCAL_DIR."/template/stock/mainlayout.beb");
	$template = str_replace("{@main-content@}",$weblogdata,$template);


	echo $template;

?>
