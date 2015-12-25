	var select_size, $select_size;

	$select_size = $('#sizing').selectize({
		options: jsonSize,
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
		}
	});
	
	select_size = $select_size[0].selectize;
	if(currentSize!=""){
		select_size.setValue(currentSize);
		select_size.disable();
	}
	
	$("#newsession").click(function(){
		if (confirm('Are you sure you want to setup a new session?')) {
		    // Clear it!
		    window.location = "index.php?app=test&action=dropsession";
		} else {
		    // Do nothing!
		}
		
	});
	$(document).ready(function(){
		document.getElementById("barcode").focus();
	});
	
	
	function saveEntry(mode){
		//if (confirm('Are you sure you want to save this update into the database?')) {
		    // Save it!
		
			var dtAccess=new dataAccess();
			var actionValidation=true;
			var dataKey="0";
			var upperCase=true;
			dtAccess.initialize("EP0101",mode);
			
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

					dtAccess.addItem($(this).attr("name"),$(this).val(),$(this).attr("dataType"),dataKey,$(this).attr("data-flag"));
				}
			});
			$("textarea[name=jsonobject]").text(dtAccess.getJSONstring());
			
			//console.log(dtAccess.getJSON());
			if(!actionValidation){
				return false;
			}
		//} else {
		    // Do nothing!
		  //  return false;
		//}
	}