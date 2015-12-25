	var updateMode=false;
    var usingFinCoy=false;

    var selectedKotaBeli="";
    var selectedKecBeli="";
    var selectedDesBeli="";
    var selectedKotaStnk="";
    var selectedKecStnk="";
    var selectedDesStnk="";

    var selectedTipe="";
    var selectedColour="";
    var selectedSalesType="";
    var selectedSPV="";
    var selectedKoor="";
    var selectedSales="";
    var selectedSalesGroup="";
    var selectedAsalSPK="";
    var selectedLeasing="";

	//$(document).ready(function(){

		var dataJsonSPK = JSON.parse(dataStringSPK);

		//console.log(dataJsonSPK);

		if (dataJsonSPK) {
			var mainTable = dataJsonSPK["dt_objectTable"];
            if (mainTable) {

                var relMainTable = mainTable[0]["dt_relatedTables"];
				if (relMainTable.length>0){
					//$("#loader").modal({backdrop: "static"});
					showLoader();
					updateMode=true;
					var firstrelTable = relMainTable[0]["dt_relatedTable"];
					var secondrelTableCols = relMainTable[1]["dt_relatedTable"];
					var thirdrelTableCols = relMainTable[2]["dt_relatedTable"];
					var firstrelFieldCols = firstrelTable[0]["dt_relfieldCollection"]
					var secondrelFieldCols = secondrelTableCols[0]["dt_relfieldCollection"];
					var thirdrelFieldCols = thirdrelTableCols[0]["dt_relfieldCollection"];

					for(var i=0;i<firstrelFieldCols.length;i++){
						if(firstrelFieldCols[i]["fieldName"]!="KODE_WILAYAH"){
							$("#pemohon").find(':input[name="'+firstrelFieldCols[i]["fieldName"]+'"]').val(firstrelFieldCols[i]["fieldValue"]);
						}
						switch (firstrelFieldCols[i]["fieldName"]) {
								case "KODE_WILAYAH":
									selectedKotaBeli = firstrelFieldCols[i]["fieldValue"];
									break;
								 case "KODE_KECAMATAN":
									selectedKecBeli = firstrelFieldCols[i]["fieldValue"];
									break;
								 case "KODE_KELURAHAN":
									selectedDesBeli = firstrelFieldCols[i]["fieldValue"];
									break;
						}
					}

					for(var i=0;i<secondrelFieldCols.length;i++){
						if(secondrelFieldCols[i]["fieldName"]!="KODE_WILAYAH"){
							$("#stnk").find(':input[name="'+secondrelFieldCols[i]["fieldName"]+'"]').val(secondrelFieldCols[i]["fieldValue"]);
						}
						switch (secondrelFieldCols[i]["fieldName"]) {
								case "KODE_WILAYAH":
									selectedKotaStnk = secondrelFieldCols[i]["fieldValue"];
									break;
								 case "KODE_KECAMATAN":
									selectedKecStnk = secondrelFieldCols[i]["fieldValue"];
									break;
								 case "KODE_KELURAHAN":
									selectedDesStnk = secondrelFieldCols[i]["fieldValue"];
									break;
						}
					}

					for(var i=0;i<thirdrelFieldCols.length;i++){
						$("#kirim").find(':input[name="'+thirdrelFieldCols[i]["fieldName"]+'"]').val(thirdrelFieldCols[i]["fieldValue"]);
					}

					var fieldCols = mainTable[0]["dt_fieldsCollection"];

					for(var i=0;i<fieldCols.length;i++){
						$(':input[name="'+fieldCols[i]["fieldName"]+'"]').val(fieldCols[i]["fieldValue"]);

						switch (fieldCols[i]["fieldName"]) {
							case "KODE_TIPE":
								selectedTipe = fieldCols[i]["fieldValue"];
								break;
							case "KODE_WARNA":
								selectedColour = fieldCols[i]["fieldValue"];
								break;
							 case "JENIS_SALES":
								selectedSalesType = fieldCols[i]["fieldValue"];
								break;
							case "SPV_ID":
								selectedSPV = fieldCols[i]["fieldValue"];
								break;
							case "KOORD_ID":
								selectedKoor = fieldCols[i]["fieldValue"];
								break;
							case "SALES_ID":
								selectedSales = fieldCols[i]["fieldValue"];
								break;
							case "NOMOR_GROUP":
								selectedSales = selectedSales+"_"+fieldCols[i]["fieldValue"];
								selectedSalesGroup = fieldCols[i]["fieldValue"];
								break;
							 case "JABATAN_SLS":
								selectedSales = selectedSales+"_"+fieldCols[i]["fieldValue"];
								break;
							case "KODE_ASAL_SPK":
								selectedAsalSPK = fieldCols[i]["fieldValue"];
								break;
							case "KODE_LEASING":
								selectedLeasing = fieldCols[i]["fieldValue"];
								break;
							case "ROADOFSPK":
								if (fieldCols[i]["fieldValue"]!="0"){
									$("#mainForm").attr("onsubmit","return false;");
									$("#submitform").hide();
								}
								break;
						}
					}
					if(selectedLeasing.trim()!=""){
						usingFinCoy=true;
					}
					if(!usingFinCoy){
						$("#optCash").trigger('click');
					}
				}
            }
            //console.log(dataJsonSPK);

		}

	//});


	$("#searchspk").click(function(){
		window.location = "loadspk_"+$("#nospk").val();
	});

	$("#entrypemohon").click(function(){
		$("#pemohon").modal({backdrop: "static"});
	});

	$("#entrystnk").click(function(){
		$("#stnk").modal({backdrop: "static"});
	});

	$("#clearform").click(function(){
		if (confirm('Are you sure you want to clear up this form?')) {
		    // Clear it!
		    window.location = "entry-spk";
		} else {
		    // Do nothing!
		}

	});

	$("#inquryspk").click(function(){
		runInquiry();
	});

	$("#filterby").keyup(function(){
		if($("#filterby").val().length>1){
			runInquiry();
		}
	});

	var copyMode=false;
	$('.datepicker').datepicker();
	$('.selectize').selectize({
		selectOnTab: true
	});

	function runInquiry(){
		var xhr;
		xhr && xhr.abort();
		var param1=$("select[name=FILTERCRITERIA] option:selected").val();;
		var param2=$("#filterby").val();
		xhr = $.ajax({
			url: 'runinqury_' + param1 +'_'+ param2 ,
			beforeSend: function() {
				$(".bs-result-detail").hide();
				$(".inquiry-result").show();
				$(".inquiry-result").html('<div>loading .... <span class="glyphicon glyphicon-refresh glyphicon-refresh-animate"></span></div>');
			},
			success: function(results) {
				$(".inquiry-result").html(results);
			},
			error: function() {
				$(".inquiry-result").html("Error while executing this action, please try again!");
				$(".inquiry-result").show();
				$(".bs-result-detail").hide();
			}
		})
	}

	function runInquiryDetail(param){
		var xhr;
		xhr && xhr.abort();
		xhr = $.ajax({
			url: 'inqurydetail_' + param,
			beforeSend: function() {
				$(".inquiry-result").hide();
				$(".bs-result-detail").show();
				$("#inqury-result-detail").html('<tr><td colspan="3">loading .... <span class="glyphicon glyphicon-refresh glyphicon-refresh-animate"></span></td></tr>');
			},
			success: function(results) {
				$("#inqury-result-detail").html(results);
			},
			error: function() {
				$("#inqury-result-detail").html("Error while executing this action, please try again!");
				$(".bs-result-detail").show();
				$(".inquiry-result").hide();
			}
		})
	}

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

	function buildCustomerBeli(){

		//var formValidation=formValidation("pemohon");

		if (!formValidation("pemohon")){
			return false;
		}

		$("input[name=NAMA_PEMOHON]").val($("#NAMA_CUSTOMER_BELI").val());
		var alamat = $("#ALAMAT_BELI").val()+" "+$("#KODE_KELURAHAN_BELI option:selected").text()+" "+$("#KODE_KECAMATAN_BELI option:selected").text()+" "+$("#KODE_WILAYAH_BELI option:selected").text()+" "+$("#KODEPOS_CUSTOMER_BELI").val()+" HP: "+$("#HP_CUSTOMER_BELI").val();
		$("textarea[name=ALAMAT_PEMOHON]").text(alamat);
		$("textarea[id=ALAMAT_LENGKAP_BELI]").text(alamat);
		$("textarea[id=ALAMAT_LENGKAP_KIRIM]").text(alamat);

		if($("#CUSTOMER_BELI").val()==""){
			copyCustomerBeli();
		}

		$("#pemohon").modal("hide");
	}

	function copyCustomerBeli(){
		$("#NAMA_CUSTOMER_STNK").val($("#NAMA_CUSTOMER_BELI").val());
		$("#ALAMAT_STNK").val($("#ALAMAT_BELI").val());

		/*var $options = $("#KODE_KELURAHAN_BELI > option").clone();
		$('#KODE_KELURAHAN_STNK').append($options);
		$("#KODE_KELURAHAN_STNK").val($("#KODE_KELURAHAN_BELI").val());

		var $options = $("#KODE_KECAMATAN_BELI > option").clone();
		$('#KODE_KECAMATAN_STNK').append($options);
		$("#KODE_KECAMATAN_STNK").val($("#KODE_KECAMATAN_BELI").val());

		$("#KODE_WILAYAH_STNK").val($("#KODE_WILAYAH_BELI").val());*/
		copyMode = true;
		select_wil_stnk.setValue($("#KODE_WILAYAH_BELI").val(),false);

		$("#KODEPOS_CUSTOMER_STNK").val($("#KODEPOS_CUSTOMER_BELI").val());
		$("#HP_CUSTOMER_STNK").val($("#HP_CUSTOMER_BELI").val());
		$("#ALT_CUSTOMER_STNK").val($("ALT_CUSTOMER_BELI").val());

		$("#NAMA_CUSTOMER_KIRIM").val($("#NAMA_CUSTOMER_BELI").val());
		$("#ALAMAT_KIRIM").val($("#ALAMAT_BELI").val());
		$("#KODE_KELURAHAN_KIRIM").val($("#KODE_KELURAHAN_BELI").val());
		$("#KODE_KECAMATAN_KIRIM").val($("#KODE_KECAMATAN_BELI").val());
		$("#KODE_WILAYAH_KIRIM").val($("#KODE_WILAYAH_BELI").val())
		$("#KODEPOS_CUSTOMER_KIRIM").val($("#KODEPOS_CUSTOMER_BELI").val());
		$("#HP_CUSTOMER_KIRIM").val($("#HP_CUSTOMER_BELI").val());
		$("#ALT_CUSTOMER_KIRIM").val($("ALT_CUSTOMER_BELI").val());

	}

	function buildCustomerSTNK(){
		$("input[name=NAMA_STNK]").val($("#NAMA_CUSTOMER_STNK").val());
		var alamat = $("#ALAMAT_STNK").val()+" "+$("#KODE_KELURAHAN_STNK option:selected").text()+" "+$("#KODE_KECAMATAN_STNK option:selected").text()+" "+$("#KODE_WILAYAH_STNK option:selected").text()+" "+$("#KODEPOS_CUSTOMER_STNK").val()+" HP: "+$("#HP_CUSTOMER_STNK").val();;
		$("textarea[name=ALAMAT_STNK]").text(alamat);
		$("textarea[id=ALAMAT_LENGKAP_STNK]").text(alamat);

		$("#pemohon").modal("hide");
	}

	/* Unit Type & Colour Section start here */
	var select_type, $select_type;
	var select_colour, $select_colour;

	$select_type = $('#unittype').selectize({
		options: jsonUnitType,
		valueField: 'id',
		labelField: 'name',
		searchField: ['name'],
		selectOnTab: true,
		render: {
			option: function(item, escape) {
				var label = item.id || item.name;
				var caption = item.id ? item.name : null;
				return '<div>' +
					'<span class="caption">' + escape(label) + '</span> - ' +
					(caption ? '<span class="caption">' + escape(caption) + '</span>' : '') +
				'</div>';
			}
		},
		onChange: function(value) {
			if (!value.length) return;
			select_colour.disable();
			select_colour.clearOptions();
			select_colour.load(function(callback) {
				var xhr;

				xhr && xhr.abort();
				xhr = $.ajax({
					url: 'getunitcolour_' + value,
					success: function(results) {
						select_colour.enable();
						loadunitprice();
						callback(JSON.parse(results));

                        select_colour.setValue(selectedColour);
					},
					error: function() {
						callback();
					}
				})
			});
		}
	});

	$select_colour = $('#unitcolour').selectize({
		selectOnTab: true,
		valueField: 'id',
		labelField: 'name',
		searchField: ['name']
	});

	select_colour  = $select_colour[0].selectize;
	select_type = $select_type[0].selectize;


	select_colour.disable();

    /* Unit Type & Colour Section end here */


	/* Regional Select Element Section start here */
	var select_wil, $select_wil;
	var select_camat, $select_camat;
	var select_lurah, $select_lurah;

	var select_wil_stnk, $select_wil_stnk;
	var select_camat_stnk, $select_camat_stnk;
	var select_lurah_stnk, $select_lurah_stnk;

	$select_wil = $('#KODE_WILAYAH_BELI').selectize({
		selectOnTab: true,
		onChange: function(value) {
			if (!value.length) return;
			select_camat.disable();
			select_lurah.disable();
			select_camat.clearOptions();
			select_lurah.clearOptions();
			select_camat.load(function(callback) {
				var xhr;

				xhr && xhr.abort();
				xhr = $.ajax({
					url: 'getkecamatan_' + value,
					success: function(results) {
						select_camat.enable();
						callback(JSON.parse(results));
						select_camat.setValue(selectedKecBeli);
					},
					error: function() {
						callback();
					}
				})
			});
		}
	});

	$select_camat = $('#KODE_KECAMATAN_BELI').selectize({
		valueField: 'id',
		labelField: 'name',
		searchField: ['name'],
		selectOnTab: true,
		onChange: function(value) {
			if (!value.length) return;
			select_lurah.disable();
			select_lurah.clearOptions();
			select_lurah.load(function(callback) {
				var xhr;

				xhr && xhr.abort();
				xhr = $.ajax({
					url: 'getkelurahan_' + value,
					success: function(results) {
						select_lurah.enable();
						callback(JSON.parse(results));
						select_lurah.setValue(selectedDesBeli);
					},
					error: function() {
						callback();
					}
				})
			});
		}
	});

	$select_lurah = $('#KODE_KELURAHAN_BELI').selectize({
		selectOnTab: true,
		valueField: 'id',
		labelField: 'name',
		searchField: ['name']
	});

	select_lurah  = $select_lurah[0].selectize;
	select_camat  = $select_camat[0].selectize;
	select_wil = $select_wil[0].selectize;

	select_camat.disable();
	select_lurah.disable();

	//Data STNK start here
	$select_wil_stnk = $('#KODE_WILAYAH_STNK').selectize({
		selectOnTab: true,
		onChange: function(value) {
			if (!value.length) return;
			select_camat_stnk.disable();
			select_lurah_stnk.disable();
			select_camat_stnk.clearOptions();
			select_lurah_stnk.clearOptions();
			select_camat_stnk.load(function(callback) {
				var xhr;

				xhr && xhr.abort();
				xhr = $.ajax({
					url: 'getkecamatan_' + value,
					success: function(results) {
						select_camat_stnk.enable();
						callback(JSON.parse(results));
						select_camat_stnk.setValue(selectedKecStnk);
					},
					error: function() {
						callback();
					},
					complete : function() {
						if (copyMode) {
							select_camat_stnk.setValue($("#KODE_KECAMATAN_BELI").val(),false);
						}
					}
				})
			});
		}
	});

	$select_camat_stnk = $('#KODE_KECAMATAN_STNK').selectize({
		valueField: 'id',
		labelField: 'name',
		searchField: ['name'],
		selectOnTab: true,
		onChange: function(value) {
			if (!value.length) return;
			select_lurah_stnk.disable();
			select_lurah_stnk.clearOptions();
			select_lurah_stnk.load(function(callback) {
				var xhr;

				xhr && xhr.abort();
				xhr = $.ajax({
					url: 'getkelurahan_' + value,
					success: function(results) {
						select_lurah_stnk.enable();
						callback(JSON.parse(results));
						select_lurah_stnk.setValue(selectedDesStnk);
					},
					error: function() {
						callback();
					},
					complete : function() {
						if (copyMode) {
							select_lurah_stnk.setValue($("#KODE_KELURAHAN_BELI").val(),false);
							buildCustomerSTNK();
							copyMode = false;
						}
					}
				})
			});
		}
	});

	$select_lurah_stnk = $('#KODE_KELURAHAN_STNK').selectize({
		selectOnTab: true,
		valueField: 'id',
		labelField: 'name',
		searchField: ['name']
	});

	select_lurah_stnk  = $select_lurah_stnk[0].selectize;
	select_camat_stnk  = $select_camat_stnk[0].selectize;
	select_wil_stnk = $select_wil_stnk[0].selectize;

	select_camat_stnk.disable();
	select_lurah_stnk.disable();

	/* Regional Select Element Section end here */


	/* Sales Person Section start here */
	var select_salestype, $select_salestype;
	var select_spv, $select_spv;
	var select_koor, $select_koor;
	var select_sales, $select_sales;

	$select_salestype = $('#salestype').selectize({
		selectOnTab: true,
		onChange: function(value) {
			if (!value.length) return;
			select_spv.disable();
			select_koor.disable();
			select_sales.disable();
			select_spv.clearOptions();
			select_koor.clearOptions();
			select_sales.clearOptions();
			switch(value){
				case "COUNTER":
					select_spv.addOption({"id":"COUNTER","name":"COUNTER"});
					select_spv.setValue("COUNTER");
					select_koor.addOption({"id":"COUNTER","name":"COUNTER"});
					select_koor.setValue("COUNTER");
					select_sales.load(function(callback) {
						var xhr;

						xhr && xhr.abort();
						xhr = $.ajax({
							url: 'getsupervisor_' + value,
							success: function(results) {
								select_sales.enable();
								callback(JSON.parse(results));
								if (selectedSales != "") {
									select_sales.setValue(selectedSales);
								}
								hideLoader();
							},
							error: function() {
								callback();
							}
						})
					});
					break;
				case "DEALER LAIN":
					select_spv.addOption({"id":"DEALER LAIN","name":"DEALER LAIN"});
					select_spv.setValue("DEALER LAIN");
					select_koor.addOption({"id":"DEALER LAIN","name":"DEALER LAIN"});
					select_koor.setValue("DEALER LAIN");
					select_sales.load(function(callback) {
						var xhr;

						xhr && xhr.abort();
						xhr = $.ajax({
							url: 'getsupervisor_' + value,
							success: function(results) {
								select_sales.enable();
								callback(JSON.parse(results));
								hideLoader();
							},
							error: function() {
								callback();
							}
						})
					});
					break;
				default:
					select_spv.load(function(callback) {
						var xhr;
						xhr && xhr.abort();
						xhr = $.ajax({
							url: 'getsupervisor_' + value,
							success: function(results) {
								select_spv.enable();
								callback(JSON.parse(results));
								var paramArr=selectedSalesGroup.split(".");
								var param2=paramArr[0];
								select_spv.setValue(selectedSPV+"_"+param2);
							},
							error: function() {
								callback();
							}
						})
					});
					break;
			}

		}
	});

	$select_spv = $('#supervisor').selectize({
		valueField: 'id',
		labelField: 'name',
		searchField: ['name'],
		selectOnTab: true,
		onChange: function(value) {
			if (!value.length) return;
			select_koor.disable();
			select_sales.disable();
			select_koor.clearOptions();
			select_sales.clearOptions();
			salestype=$('#salestype').val();
			if ( salestype!= "COUNTER" && salestype!= "DEALER LAIN" ){
					if (selectedSPV == "") {
					var objectParam2=$("select[name=SPV_ID_X] option:selected").val();
					var paramArr=objectParam2.split("_");
					var param2=paramArr[1];
				}else{
					var paramArr=selectedSalesGroup.split(".");
					var param2=paramArr[0];
					var param3=paramArr[1];
					if (typeof param3=="undefined") {
						//param3 = "001";
					}
				}
				select_koor.load(function(callback) {
					var xhr;

					xhr && xhr.abort();
					xhr = $.ajax({
						url: 'getkoordinator_' + salestype + '_' +param2 ,
						success: function(results) {
							select_koor.enable();
							callback(JSON.parse(results));
							if(typeof param3 =="undefined"){
								select_koor.setValue(selectedKoor+"_"+param2);
							}else{
								select_koor.setValue(selectedKoor+"_"+param2+"."+param3);
							}

						},
						error: function() {
							callback();
						}
					})
				});
			}

		}
	});

	$select_koor = $('#koordinator').selectize({
		valueField: 'id',
		labelField: 'name',
		searchField: ['name'],
		selectOnTab: true,
		onChange: function(value) {
			if (!value.length) return;
			select_sales.disable();
			select_sales.clearOptions();
			salestype=$('#salestype').val();
			if ( salestype!= "COUNTER" && salestype!= "DEALER LAIN" ){
				if (selectedSPV == "") {
					var objectParam2=$("select[name=SPV_ID_X] option:selected").val();
					var paramArr=objectParam2.split("_");
					var param2=paramArr[1];
					var objectParam3=$("select[name=KOORD_ID_X] option:selected").val();
					if(objectParam3!=""){
						var paramArr2=objectParam3.split("_");
						var param3=paramArr2[1];
						var param4=0;
					}else{
						var param3="";
						var param4=1;
					}
				}else{

					var paramArr=selectedSalesGroup.split(".");
					var param2=paramArr[0];

					if(selectedKoor == "") {
						var param3=paramArr[0];
						var param4=1;
					}else{
						if (typeof paramArr[1]=="undefined") {
							var param3 = paramArr[0]; //+".001";
						}else{
							var param3=paramArr[0]+"."+paramArr[1];
						}
						var param4=0;
					}

				}

				select_sales.load(function(callback) {
					var xhr;

					xhr && xhr.abort();
					xhr = $.ajax({
						url: 'getsalesforce_' + salestype + '_' +param2 + "_" + param3 + "_" + param4 ,
						success: function(results) {
							select_sales.enable();
							callback(JSON.parse(results));
							//console.log(selectedSales);
							select_sales.setValue(selectedSales);

							selectedTipe="";
							selectedColour="";
							selectedSalesType="";
							selectedSPV="";
							selectedKoor="";
							selectedSales="";
							selectedSalesGroup="";
							selectedAsalSPK="";
							selectedLeasing="";

							selectedKotaBeli="";
							selectedKecBeli="";
							selectedDesBeli="";
							selectedKotaStnk="";
							selectedKecStnk="";
							selectedDesStnk="";
							//$("#loader").modal("hide");
							hideLoader();
						},
						error: function() {
							callback();
						}
					})
				});
			}

		}
	});

	$select_sales = $('#sales').selectize({
		selectOnTab: true,
		valueField: 'id',
		labelField: 'name',
		searchField: ['name'],
		onChange: function(value) {
			if (!value.length) return;
			var objectParam=$("select[name=SALES_ID_DUMMY] option:selected").val();
			var objectParam2=$("select[name=SALES_ID_DUMMY] option:selected").text();
			var paramArr=objectParam.split("_");
			if(paramArr.length>0){
				var myspv=$("select[name=SPV_ID_X] option:selected").val();
				var spvArr = myspv.split("_");
				$("input[name=SPV_ID]").val(spvArr[0]);
				$("input[name=NAMA_SUPERVISOR]").val($("select[name=SPV_ID_X] option:selected").text());

				var mykoord=$("select[name=KOORD_ID_X] option:selected").val();
				var koordArr = mykoord.split("_");
				$("input[name=KOORD_ID]").val(koordArr[0]);
				$("input[name=KOORDINATOR]").val($("select[name=KOORD_ID_X] option:selected").text());
				$("input[name=SALES_ID]").val(paramArr[0]);
				$("input[name=NAMA_SALES]").val(objectParam2);
				$("input[name=NOMOR_GROUP]").val(paramArr[1]);
				$("input[name=JABATAN_SLS]").val(paramArr[2]);
			}


		}
	});

	select_sales  = $select_sales[0].selectize;
	select_koor  = $select_koor[0].selectize;
	select_spv  = $select_spv[0].selectize;
	select_salestype = $select_salestype[0].selectize;

	select_sales.disable();
	select_koor.disable();
	select_spv.disable();

	/* Sales Person Section end here */

	var $select_asalspk,select_asalspk;
	var $select_leasing,select_leasing;

	$select_asalspk = $('#asalspk').selectize({
		onChange:  function(value) {
			if (!value.length) return;
			var objectParam=$("select[name=KODE_ASAL_SPK] option:selected").text();
			$("input[name=NAMA_ASAL_SPK]").val(objectParam);
		}
	});

	select_asalspk  = $select_asalspk[0].selectize;

	$select_leasing = $('#leasing').selectize();
	select_leasing  = $select_leasing[0].selectize;

	function loadunitprice(){
		var objectParam=$("select[name=KODE_TIPE]").val();
		var objectParam2=$("select[name=GOL_HARGA] option:selected").val();
		var param2=objectParam2.substring(0,1);
		$.get("getunitprice_"+objectParam+"_"+param2, function(data, status){
			$("input[name=HARGA]").val(data);
		});
	}


	function saveSPK(mode){
		if (confirm('Are you sure you want to save this update into the database?')) {
		    // Save it!

			var dtAccess=new dataAccess();
			var actionValidation=true;
			var dataKey="0";
			var upperCase=true;
			dtAccess.initialize("TR_SALE_SPK","",mode);

			dtAccess.addRelatedTable("MS_CUST_PEMBELI_BIODATA","CST",mode);
			dtAccess.addRelatedTable("MS_CUST_STNK_BIODATA","CST",mode);
			dtAccess.addRelatedTable("MS_CUST_KIRIM_BIODATA","CST",mode);

			$("#mainForm").find(":input").each(function() {
				var nosaveFlag = $(this).attr("nosave");
				if ( $(this).attr("name")!= "undefined" && typeof nosaveFlag == "undefined") {
					var relTblIndex = $(this).attr("relatedTblIndex");

					if ( $(this).attr("data-mandatory")=="true") {
						if ($(this).val()==""){
							alert($(this).attr("validation-string")+" field doesn't accepted without a value!");
							if ( typeof relTblIndex != "undefined" ) {
								switch (parseInt(relTblIndex)){
									case 0:
										$("#entrypemohon").trigger("click");
										break
									case 1:
										$("#entrystnk").trigger("click");
										break;
								}
							}
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

					if ( typeof relTblIndex == "undefined" ) {
						if(upperCase){
							dtAccess.addItem($(this).attr("name"),$(this).val().toUpperCase(),$(this).attr("dataType"),dataKey,$(this).attr("data-flag"));
						}else{
							dtAccess.addItem($(this).attr("name"),$(this).val(),$(this).attr("dataType"),dataKey,$(this).attr("data-flag"));
						}
					}else{
						if(upperCase){
							dtAccess.addItemRelated(parseInt($(this).attr("relatedTblIndex")),$(this).attr("name"),$(this).val().toUpperCase(),$(this).attr("dataType"),dataKey,$(this).attr("data-flag"));
						}else{
							dtAccess.addItemRelated(parseInt($(this).attr("relatedTblIndex")),$(this).attr("name"),$(this).val(),$(this).attr("dataType"),dataKey,$(this).attr("data-flag"));
						}
					}
				}
			});
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

		if(updateMode){
			select_wil.setValue(selectedKotaBeli);
			select_wil_stnk.setValue(selectedKotaStnk);
			select_type.setValue(selectedTipe);
			select_salestype.setValue(selectedSalesType);
			select_asalspk.setValue(selectedAsalSPK)
			select_leasing.setValue(selectedLeasing);
		}else{
			usingFinCoy=true;
		}
	});
