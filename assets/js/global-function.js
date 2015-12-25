	
	function getRealIpAddress(){
		var realip=getCookie("greensysclientinfo");
		if (realip=="") {
			$.get("https://api.ipify.org/", function(data, status){
				if (data!=""){
					var clientinfo=data;
					if(/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) ) {
						if (navigator.userAgent.indexOf("Android")>-1) {
							clientinfo+=":android";
						}
						
						if (navigator.userAgent.indexOf("webOS")>-1) {
							clientinfo+=":webOS";
						}
						
						if (navigator.userAgent.indexOf("iPhone")>-1) {
							clientinfo+=":iPhone";
						}
						
						if (navigator.userAgent.indexOf("iPad")>-1) {
							clientinfo+=":iPad";
						}
						
						if (navigator.userAgent.indexOf("iPod")>-1) {
							clientinfo+=":iPod";
						}
						
						if (navigator.userAgent.indexOf("BlackBerry")>-1) {
							clientinfo+=":BlackBerry";
						}
						
						if (navigator.userAgent.indexOf("IEMobile")>-1) {
							clientinfo+=":IEMobile";
						}
						
						if (navigator.userAgent.indexOf("Opera Mini")>-1) {
							clientinfo+=":Opera Mini";
						}
					}else{
						clientinfo+=":desktop";
					}
					setCookie("greensysclientinfo",clientinfo,1);
				}
			});
		}
		
	}
	
	function setCookie(cname, cvalue, exdays) {
		var d = new Date();
		d.setTime(d.getTime() + (exdays*24*60*60*1000));
		var expires = "expires="+d.toUTCString();
		document.cookie = cname + "=" + cvalue + "; " + expires;
	}

	function getCookie(cname) {
		var name = cname + "=";
		var ca = document.cookie.split(';');
		for(var i=0; i<ca.length; i++) {
			var c = ca[i];
			while (c.charAt(0)==' ') c = c.substring(1);
			if (c.indexOf(name) == 0) return c.substring(name.length, c.length);
		}
		return "";
	}
	
	
	$(document).ready(function(){
		//setCookie("greensysclientinfo","",0);
		//$(".sidebar-toggle").trigger("click");
		getRealIpAddress();
	});