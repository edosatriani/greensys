<?php
	$template = file_get_contents(LOCAL_DIR."/template/starter.html");
	$template = str_replace("{@menu-title@}","SPK",$template);
	$template = str_replace("{@menu-description@}",$_SESSION["ws-name"],$template);
	$template = str_replace("{@module@}","apps",$template);
	$template = str_replace("{@sub-module@}","spk",$template);

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

	$templatespk = file_get_contents(LOCAL_DIR."/template/spk/mainlayout.beb");
	$templatespk = str_replace("{@entry-spk-content@}",file_get_contents(LOCAL_DIR."/template/spk/entryspk.beb"),$templatespk);

	$additionalcss = "<link href=\"assets/css/core.css\" rel=\"stylesheet\">";
  	$template = str_replace("{@additional-css@}",$additionalcss,$template);

	$corejavascript .= "<script src=\"assets/js/global-function.js\"></script>\n";
	$template = str_replace("{@core-javascript@}",$corejavascript,$template);

	$additionaljavascript = "<script src=\"assets/spinner/js/iosOverlay.js\"></script>\n";
	$additionaljavascript .= "<script src=\"assets/spinner/js/spin.min.js\"></script>\n";
	$additionaljavascript .= "<script src=\"assets/spinner/js/custom.js\"></script>\n";
	$additionaljavascript .= "<script src=\"assets/js/app_greenSys_dataaccess.js\"></script>\n";
  	$additionaljavascript .= "<script>var dataStringSPK ='{@dataspk@}';</script>\n";
  	$additionaljavascript .= "<script src=\"assets/js/function-spk.js\"></script>\n";
  	$template = str_replace("{@additional-javascript@}",$additionaljavascript,$template);

	$template = str_replace("{@main-content@}",$templatespk,$template);
	$template = str_replace("{@mode@}",$modify_status,$template);
	$template = str_replace("{@readonlymode@}",$readonlymode,$template);
	$template = str_replace("{@dataspk@}",$dataspk,$template);
	$template = str_replace("{@themes@}","default",$template);
	$template = str_replace("{@autonumber@}",$autonumber,$template);
	$template = str_replace("{@today@}",date("m/d/Y"),$template);
	$template = str_replace("{@direct-active-wilayah-0@}",$active_wilayah,$template);
	$template = str_replace("{@direct-active-vehicletype-0@}",$active_unittype,$template);
	$template = str_replace("{@direct-active-salestype-0@}",$active_salestype,$template);
	$template = str_replace("{@tahun-rakit@}",date("Y"),$template);
	$template = str_replace("{@ajax-related-supervisor-salestype@}",$active_defaultspv,$template);
	$template = str_replace("{@direct-active-stocklocation-0@}",$active_stocklocation,$template);
	$template = str_replace("{@direct-active-fincoy-0@}",$active_fincoy,$template);
	$template = str_replace("{@inqury-spk-filterfield@}",$inquiry_filterfield,$template);

	echo $template;

?>
