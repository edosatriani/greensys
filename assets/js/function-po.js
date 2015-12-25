	var updateMode=false;
    var usingFinCoy=false;

    var selectedKotaBeli="";
    var selectedKecBeli="";
    var selectedDesBeli="";
        
	//$(document).ready(function(){
		
		var dataJsonSPK = JSON.parse(dataStringSPK);
		
		//console.log(dataJsonSPK);

		if (dataJsonSPK) {
			var mainTable = dataJsonSPK["dt_objectTable"];
            if (mainTable) {
                
                	var fieldCols = mainTable[0]["dt_fieldsCollection"];
								
					for(var i=0;i<fieldCols.length;i++){
						$(':input[name="'+fieldCols[i]["fieldName"]+'"]').val(fieldCols[i]["fieldValue"]);
					}
            }
            //console.log(dataJsonSPK);

		}
		
	//});
		

	$("#searchspk").click(function(){
		window.location = "loadpo_"+$("#nospk").val();
	});

	
	$("#clearform").click(function(){
		if (confirm('Are you sure you want to clear up this form?')) {
		    // Clear it!
		    window.location = "po-statement";
		} else {
		    // Do nothing!
		}
		
	});
		
	$('.datepicker').datepicker();
	$('.selectize').selectize({
		selectOnTab: true
	});
		
	function formValidation(container){
		var frmValidation = true;
		$("#"+container).find(".form-group").removeClass("has-error");
		$("#"+container).find(":input").each(function() {
			if ( this.name != "" && this.type != "hidden"){
				//console.log ( this.name +': '+this.value);
				if ( this.value == "" && this.getAttribute("dataValidation")=="true"){
					$("#error_on_beli").show();
					$("#pemohon").scrollTop(0);
					$("#" + this.id).parent().addClass("has-error");
					frmValidation =  false;
				}
			}
		});
		return frmValidation;
	}
		
				
	var $select_leasing,select_leasing;
	$select_leasing = $('#leasing').selectize();
	select_leasing  = $select_leasing[0].selectize;

	function saveSPK(mode){
		if (confirm('Are you sure you want to save this update into the database?')) {
		    // Save it!
		
			var dtAccess=new dataAccess();
			var actionValidation=true;
			var dataKey="0";
			var upperCase=true;

			$("#ARL_AMOUNT").val($("#HARGA").val());
			$("#ARL_PAID").val($("#DP_SYSTEM").val());
			$("#ARL_BALANCE").val(parseInt($("#HARGA").val())-parseInt($("#DP_SYSTEM").val()));

			dtAccess.initialize("TR_SALE_SPK","","UPD");
			
			dtAccess.addRelatedTable("AR_MASTER","ARL","INS");
			dtAccess.addRelatedTable("AR_DETAIL","ARL","INS");
			dtAccess.addRelatedTable("AR_DETAIL","ARL","INS");
			
			$("#mainForm").find(":input").each(function() {
				var nosaveFlag = $(this).attr("nosave");
				if ( $(this).attr("name")!= "undefined" && typeof nosaveFlag == "undefined") {
					
					if ( $(this).attr("data-mandatory")=="true") {
						if ($(this).val()==""){
							alert($(this).attr("validation-string")+" field doesn't accepted without a value!");
							actionValidation = false;
							return false;
						}
					}
					
					if ( $(this).attr("data-mandatory")=="true-false" && usingFinCoy) {
						if ($(this).val()==""){
							alert($(this).attr("name")+" doesn't accepted without a value!");
							actionValidation = false;
							return false;
						}
					}
					
					if (typeof $(this).attr("dataKey") == "undefined" ) {
						dataKey="0";
					}else{
						dataKey	=  $(this).attr("dataKey");
					}

					if (typeof $(this).attr("data-case") == "undefined" ) {
						upperCase=true;
					}else{
						if($(this).attr("data-case")=="0"){
							upperCase =  false;
						}
					}

					if(upperCase){
						dtAccess.addItem($(this).attr("name"),$(this).val().toUpperCase(),$(this).attr("dataType"),dataKey,$(this).attr("data-flag"));
					}else{
						dtAccess.addItem($(this).attr("name"),$(this).val(),$(this).attr("dataType"),dataKey,$(this).attr("data-flag"));
					}
				}
			});

			if(actionValidation){
				dtAccess.addItemRelated(0,"NO_BUKTI","","string","0","autonumber:true:0:999");
				dtAccess.addItemRelated(0,"TGL_POSTING",$("#TS").val(),"date","0","");
				dtAccess.addItemRelated(0,"NO_SPK",$("#nospk").val(),"string","0","");
				dtAccess.addItemRelated(0,"TIPE","2","string","0","");
				dtAccess.addItemRelated(0,"DEBET","0","numeric","0","");
				dtAccess.addItemRelated(0,"CREDIT","0","numeric","0","");

				dtAccess.addItemRelated(1,"NO_SPK",$("#nospk").val(),"string","0","");
				dtAccess.addItemRelated(1,"NO_BUKTI","","string","0","related-field:NO_BUKTI_999");
				dtAccess.addItemRelated(1,"TGL_POSTING",$("#TS").val(),"date","0","");
				dtAccess.addItemRelated(1,"KODE_SUBSIDI","08","string","0","");
				dtAccess.addItemRelated(1,"DESCRIPTION","HARGA OTR","string","0","");
				dtAccess.addItemRelated(1,"DEBET",$("#HARGA").val(),"numeric","0","");
				dtAccess.addItemRelated(1,"CREDIT","0","numeric","0","");
				dtAccess.addItemRelated(1,"TIPE","LEASING","string","0","");

				dtAccess.addItemRelated(2,"NO_SPK",$("#nospk").val(),"string","0","");
				dtAccess.addItemRelated(2,"NO_BUKTI","","string","0","related-field:NO_BUKTI_999");
				dtAccess.addItemRelated(2,"TGL_POSTING",$("#TS").val(),"date","0","");
				dtAccess.addItemRelated(2,"KODE_SUBSIDI","08","string","0","");
				dtAccess.addItemRelated(2,"DESCRIPTION","HARGA OTR","string","0","");
				dtAccess.addItemRelated(2,"DEBET","0","numeric","0","");
				dtAccess.addItemRelated(2,"CREDIT",$("#DP_SYSTEM").val(),"numeric","0","");
				dtAccess.addItemRelated(2,"TIPE","LEASING","string","0","");

			}


			$("textarea[name=jsonobject]").text(dtAccess.getJSONstring());
			
			//console.log(dtAccess.getJSON());
			if(!actionValidation){
				return false;
			}
		} else {
		    // Do nothing!
		    return false;
		}
	}
	
	
	$(document).ready(function(){
		
		
	});