<?php
	$template = file_get_contents(LOCAL_DIR."/template/starter.html");
	$template = str_replace("{@menu-title@}","Green Tyre",$template);
	$template = str_replace("{@menu-description@}","Retrieve Barcode",$template);
	$template = str_replace("{@module@}","apps",$template);
	$template = str_replace("{@sub-module@}","greentyre",$template);

	$templateentry = file_get_contents(LOCAL_DIR."/template/test/entry.beb");

	$additionalcss = "<link href=\"assets/css/test.css\" rel=\"stylesheet\">";
  	$template = str_replace("{@additional-css@}",$additionalcss,$template);
	
	$additionaljavascript = "<script src=\"assets/js/app_greenSys_dataaccess.js\"></script>\n";
	$additionaljavascript .= "<script src=\"assets/js/function-test.js\"></script>\n";
	$template = str_replace("{@additional-javascript@}",$additionaljavascript,$template);
	
	$template = str_replace("{@main-content@}",$templateentry,$template);
	$template = str_replace("{@mode@}",$modify_status,$template);
	$template = str_replace("{@readonlymode@}",$readonlymode,$template);
	$template = str_replace("{@themes@}","default",$template);	
	$template = str_replace("{@session-id@}",$_SESSION["session_id"],$template);
	$template = str_replace("{@autonumber@}",$autonumber,$template);
	$template = str_replace("{@today@}",date("m/d/Y"),$template);
	$template = str_replace("{@time@}",date("h:m:s"),$template);
	$template = str_replace("{@direct-active-size-0@}",$active_size,$template);
	$template = str_replace("{@current-active-size@}",$currentactivesize,$template);
	$template = str_replace("{@active-session-count@}",$countactivetrans,$template);
	$template = str_replace("{@active-trans-0@}",$activetrans,$template);
	
	
	echo $template;
	
?>