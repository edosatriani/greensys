var dataAccess = function(){ 
	var jsonObject;
	this.initialize = function(tableName, autonumFormat, modify_status) {
		var jsonString = '{"dt_objectTable": [{"dt_fieldsCollection":[],"dt_relatedTables":[],"tableName":"' + tableName + '","autonumFormat":"'+ autonumFormat+ '","modify_status":"'+ modify_status +'"}]}';
		this.jsonObject = JSON.parse(jsonString);
	}
	this.addRelatedTable = function(tableName,autonumFormat,modify_status){
		if ( typeof tableName!= "undefined" ) {
			var newjsonObject= this.jsonObject["dt_objectTable"];
			newjsonObject[0]["dt_relatedTables"].push({"dt_relatedTable":[{"dt_relfieldCollection":[],"tableName":tableName,"autonumFormat":autonumFormat,"modify_status":modify_status}]});
		}
	}
    this.addItem = function(fieldName,fieldValue,fieldType,fieldKey,flag) {
		if ( typeof fieldName!= "undefined" ) {
			var newjsonObject= this.jsonObject["dt_objectTable"];
			newjsonObject[0]["dt_fieldsCollection"].push({"fieldName":fieldName,"fieldValue":fieldValue,"fieldType":fieldType,"fieldKey":fieldKey,"flag":flag});
		}
    }
	this.addItemRelated = function(tableIndex,fieldName,fieldValue,fieldType,fieldKey,flag) {
		if ( typeof fieldName!= "undefined" ) {
			var newjsonObject= this.jsonObject["dt_objectTable"];
			var getrelation= newjsonObject[0]["dt_relatedTables"];
			var reljsonObject= getrelation[tableIndex]["dt_relatedTable"];
			reljsonObject[0]["dt_relfieldCollection"].push({"fieldName":fieldName,"fieldValue":fieldValue,"fieldType":fieldType,"fieldKey":fieldKey,"flag":flag});
		}
    }
	this.getJSON = function() {
		return this.jsonObject;
	}
	this.getJSONstring = function() {
		return JSON.stringify(this.jsonObject);
	}
	
}; 