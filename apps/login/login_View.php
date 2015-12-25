<?php
	$template = file_get_contents(LOCAL_DIR."/template/login.html");

	$loginstatus=false;


/*function encryptIt( $q ) {
    $cryptKey  = 'qSB0rGbIn5UB1xG03efbCf';
    $qEncoded      = base64_encode( mcrypt_encrypt( MCRYPT_RIJNDAEL_256, md5( $cryptKey ), $q, MCRYPT_MODE_CBC, md5( md5( $cryptKey ) ) ) );
    return( $qEncoded );
}

function decryptIt( $q ) {
    $cryptKey  = 'qSB0rGbIn5UB1xG03efbCf';
    $qDecoded      = rtrim( mcrypt_decrypt( MCRYPT_RIJNDAEL_256, md5( $cryptKey ), base64_decode( $q ), MCRYPT_MODE_CBC, md5( md5( $cryptKey ) ) ), "\0");
    return( $qDecoded );
}*/

	if($datauser!=""){
		$jfo = json_decode($datauser);
		if($jfo->uservalidation == "true"){
			$loginstatus=true;
		}
	}
	if(!$loginstatus){
			//$login = new Blowfish();
			//$verify = $login->verify_hash($_POST['pwd'], $cfg->pass);
			//echo $login->genpwd("112015");
			/*$input = "mjs0715";

			$encrypted = encryptIt( $input );
			$decrypted = decryptIt( $encrypted );

			echo $encrypted . '<br />' . $decrypted;*/
			echo $template;
			//session_destroy();
	}else{
			header("Location: dashboard");
	}


?>
