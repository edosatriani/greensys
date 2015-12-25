var dataAccess = Class({ 
	var jsonObject;
	
	initialize: function(tableName, updateStatus) {
		var jsonString = '{"' + tableName + '": {"fieldsCollection":[],"modify_status":"'+ updateStatus +'"}';
		
		this.jsonObject = JSON.parse(jsonString);
	},	
    addItem: function(fieldNname, fieldValue, fieldType, primaryKey) {
        this.jsonObject["fieldsCollection"].push({"fieldName":fieldNname,"fieldValue":fieldValue,"fieldType":fieldType,"primaryKey":primaryKey});
    },
	getJSON: function() {
		return this.jsonObject;
	}
	
}); 

/*function loadkecamatanBeli(){
			$("#KODE_KECAMATAN_BELI").prop('disabled', 'disabled');
			$("#KODE_KELURAHAN_BELI").prop('disabled', 'disabled');
			var objectParam=$("#KODE_WILAYAH_BELI option:selected").val();
			$.get("ajax-controller.php?mode=106&param="+objectParam, function(data, status){
				$("#KODE_KECAMATAN_BELI").html(data);
				$("#KODE_KECAMATAN_BELI").prop('disabled', false);
				$("#KODE_KELURAHAN_BELI").prop('disabled', false);
			});
		}
		
		function loadkelurahanBeli(){
			$("#KODE_KELURAHAN_BELI").prop('disabled', 'disabled');
			var objectParam=$("#KODE_KECAMATAN_BELI option:selected").val();
			$.get("ajax-controller.php?mode=107&param="+objectParam, function(data, status){
				$("#KODE_KELURAHAN_BELI").html(data);
				$("#KODE_KELURAHAN_BELI").prop('disabled', false);
			});
		}
		
		function loadkecamatanSTNK(){
			$("#KODE_KECAMATAN_STNK").prop('disabled', 'disabled');
			$("#KODE_KELURAHAN_STNK").prop('disabled', 'disabled');
			var objectParam=$("#KODE_WILAYAH_STNK option:selected").val();
			$.get("ajax-controller.php?mode=106&param="+objectParam, function(data, status){
				$("#KODE_KECAMATAN_STNK").html(data);
				$("#KODE_KECAMATAN_STNK").prop('disabled', false);
				$("#KODE_KELURAHAN_STNK").prop('disabled', false);
			});
		}
		
		function loadkelurahanSTNK(){
			$("#KODE_KELURAHAN_STNK").prop('disabled', 'disabled');
			var objectParam=$("#KODE_KECAMATAN_STNK option:selected").val();
			$.get("ajax-controller.php?mode=107&param="+objectParam, function(data, status){
				$("#KODE_KELURAHAN_STNK").html(data);
				$("#KODE_KELURAHAN_STNK").prop('disabled', false);
			});
		}
		*/
		


		function loadunitcolour(){
			/*$("select[name=KODE_WARNA]").prop('disabled', 'disabled');
			var objectParam=$("select[name=KODE_TIPE] option:selected").val();
			$.get("ajax-controller.php?mode=101&param="+objectParam, function(data, status){
				$("select[name=KODE_WARNA]").html(data);
				$("select[name=KODE_WARNA]").prop('disabled', false);
				loadunitprice();
			});*/
		}

function loadactivesupervisor(){
			$("select[name=SPV_ID]").prop('disabled', 'disabled');
			var objectParam=$("select[name=JENIS_SALES] option:selected").val();
			$.get("ajax-controller.php?mode=102&param="+objectParam, function(data, status){
				switch(objectParam){
					case "COUNTER":
						$("select[name=SPV_ID]").html('<option value="COUNTER">COUNTER</COUNTER>');
						$("select[name=KOORD_ID]").html('<option value="COUNTER">COUNTER</COUNTER>');
						$("select[name=KOORD_ID]").prop('disabled', 'disabled');
						$("select[name=SALES_ID]").html(data);
						break;
					case "DEALER LAIN":
						$("select[name=SPV_ID]").html('<option value="COUNTER">DEALER LAIN</COUNTER>');
						$("select[name=KOORD_ID]").html('<option value="COUNTER">DEALER LAIN</COUNTER>');
						$("select[name=KOORD_ID]").prop('disabled', 'disabled');
						$("select[name=SALES_ID]").html(data);
						break;
					default:
						$("select[name=SPV_ID]").html(data);
						$("select[name=SPV_ID]").prop('disabled', false);
						$("select[name=KOORD_ID]").prop('disabled', false);
						$("select[name=SALES_ID]").prop('disabled', false);
						loadactivekoordinator();
						break;
				}
				
			});
		}
		
		function loadactivekoordinator(){
			$("select[name=KOORD_ID]").prop('disabled', 'disabled');
			var objectParam=$("select[name=JENIS_SALES] option:selected").val();
			var objectParam2=$("select[name=SPV_ID] option:selected").val();
			var paramArr=objectParam2.split("_");
			var param2=paramArr[1];
			$.get("ajax-controller.php?mode=103&param="+objectParam+"&param2="+param2, function(data, status){
				switch(objectParam){
					case "SALES":
						$("select[name=KOORD_ID]").html(data);
						$("select[name=KOORD_ID]").prop('disabled', false);
						$("select[name=SALES_ID]").prop('disabled', false);
						break;
					case "CHANNEL":
						$("select[name=KOORD_ID]").html(data);
						$("select[name=KOORD_ID]").prop('disabled', false);
						$("select[name=SALES_ID]").prop('disabled', false);
						break;
				}
				loadactivesales();
			});
		}
		
		function loadactivesales(){
			$("select[name=SALES_ID]").prop('disabled', 'disabled');
			var objectParam=$("select[name=JENIS_SALES] option:selected").val();
			var objectParam2=$("select[name=SPV_ID] option:selected").val();
			var paramArr=objectParam2.split("_");
			var param2=paramArr[1];
			var objectParam3=$("select[name=KOORD_ID] option:selected").val();
			
			if(objectParam3!=""){
				var paramArr2=objectParam3.split("_");
				var param3=paramArr2[1];
				var param4=0;
			}else{
				var param3="";
				var param4=1;
			}
			
			$.get("ajax-controller.php?mode=104&param="+objectParam+"&param2="+param2+"&param3="+param3+"&param4="+param4, function(data, status){
				$("select[name=SALES_ID]").html(data);
				$("select[name=SALES_ID]").prop('disabled', false);
						
			});
		}