<?php
 
class ob_update_Controller {
	
	 public function __construct() {
	 	try {

	 	} catch (Exception $e) {
			throw new Exception($e->getMessage());
		}
		
	 }

	 public function breakJSONObject(){
	 	try {
			$queryCollection = "";
			
			$shield = new Security();

			$json_file = $_POST['jsonobject'];
			$json_file = str_replace('{"dt_objectTable":[','{"dt_objectTable":',$json_file);
			$json_file = str_replace('{"dt_relatedTable":[','{"dt_relatedTable":',$json_file);
			$json_file = str_replace('}]}','}}',$json_file);

			

			//echo $json_file;
			// convert the string to a json object

			$jfo = json_decode($json_file);

			$relField = "";
			$relCount = 0;

			$relTables = $jfo->dt_objectTable->dt_relatedTables;
			foreach ($relTables as $relTbl) {
				$myTable =  $relTbl->dt_relatedTable;
				$relTableName = $myTable->tableName;
				$relModifyStatus = $myTable->modify_status;
				$dt_relFields = $myTable->dt_relfieldCollection;
				$queryCollection .= $this->generateQuery($dt_relFields,$relTableName,$relModifyStatus,$relCount);
				$relCount = $relCount + 1;
			}

			$tableName = $jfo->dt_objectTable->tableName;
			$modifystatus = $jfo->dt_objectTable->modify_status;
			$dt_fields = $jfo->dt_objectTable->dt_fieldsCollection;

			$queryCollection .= $this->generateQuery($dt_fields,$tableName,$modifystatus,0);
			
			//echo $queryCollection;			

			$model = new ob_update_Model;
			$result =$model->executeQuery($queryCollection); 
			header("Location: index.php?app=test");
		} catch (Exception $e) {
			throw new Exception($e->getMessage());
		}
	 }
	 
	 public function generateQuery($dt_fields,$tableName,$modifystatus,$relCount){
			$dmlQuery = "NO SQL";
			global $relField;
			switch($modifystatus)
			{
				case "INS":
					$dmlQuery= "INSERT INTO ".$tableName." (";
					$firstField = true;
					$fieldCounter=1;
					foreach ($dt_fields as $myfield) {
						if (!$firstField) {
							$dmlQuery .= ",";
						}
						$firstField = false;
						$dmlQuery .=  $myfield->fieldName;
						$fieldCounter++;
					}
					$dmlQuery .= ") VALUES (";
					$firstField = true;
					
					$valueCounter=1;
					foreach ($dt_fields as $myfield) {
						if (!$firstField) {
							$dmlQuery .= ",";
						}
						$firstField = false;
						
						switch($myfield->fieldType)
						{
							case "string":								
								if ($data_key=="true") {
									$model = new ob_update_Model;
									$autonumber = $model->AutoNumberWithFormat("EP0101","NO_MC",$autonumberformat.date("ym"),"4");
									$dmlQuery .=  "'".$autonumber."'";
								}else{									
									$dmlQuery .=  "'".$myfield->fieldValue."'";									
								}
								break;
							case "numeric":
								$numericvalue =  str_replace(",","",$myfield->fieldValue);
								$numericvalue =  str_replace(".","",$numericvalue);
								$dmlQuery .= $numericvalue ;
								break;
							case "date":
								$dateVal = strtotime($myfield->fieldValue);
								$dmlQuery .=  "CONVERT(datetime, '".date('Y/m/d',$dateVal)."', 102)";
								break;
							
						}
					}
					$dmlQuery .= ");";
					break;
				case "UPD":
					$dmlQuery = "UPDATE ".$tableName." SET ";
					$firstField = true;
					
					$valueCounter=1;
					foreach ($dt_fields as $myfield) {
						if ($myfield->fieldKey=="0"){
							if (!$firstField) {
								$dmlQuery .= ",";
							}
							$firstField = false;
							$valueCounter++;
							$dataflag= $myfield->flag;
							$data_flag = "";
							$data_key = "";
							
							$dataflagArr = explode(":",$dataflag);
							if (count($dataflagArr)>0) {
								$data_flag = $dataflagArr[0];
								if (count($dataflagArr)>1) {
									$data_key= $dataflagArr[1];
									if (count($dataflagArr)>1) {
										$custom= $dataflagArr[2];
									}
								}
							}
							
							switch($myfield->fieldType)
							{
								case "string":								
									$dmlQuery .=  $myfield->fieldName."='".$myfield->fieldValue."'";
									break;
								case "numeric":
									$numericvalue =  str_replace(",","",$myfield->fieldValue);
									$numericvalue =  str_replace(".","",$numericvalue);
									$dmlQuery .= $myfield->fieldName."=".$numericvalue ;
									break;
								case "date":
									$dateVal = strtotime($myfield->fieldValue);
									$dmlQuery .=  $myfield->fieldName."= CONVERT(datetime, '".date('Y/m/d',$dateVal)."', 102)";
									break;
								
							}
						}
					}

					$condition = "";
					$firstField = true;
					foreach ($dt_fields as $myfield) {
						if ($myfield->fieldKey=="1"){

							if (!$firstField) {
								$condition .= "  AND ";
							}else{
								$condition .= "  WHERE ";
							}
							$firstField = false;
							switch($myfield->fieldType)
							{
								case "string":								
									$condition .=  $myfield->fieldName."='".$myfield->fieldValue."'";
									break;
								case "numeric":
									$numericvalue =  str_replace(",","",$myfield->fieldValue);
									$numericvalue =  str_replace(".","",$numericvalue);
									$condition .= $myfield->fieldName."=".$numericvalue ;
									break;
								case "date":
									$dateVal = strtotime($myfield->fieldValue);
									$condition .=  $myfield->fieldName."= CONVERT(datetime, '".date('Y/m/d',$dateVal)."', 102)";
									break;
								
							}
						}
					}
					if($condition != ""){
						$dmlQuery .= $condition.";";
					}else{
						$dmlQuery ="";
					}
					break;
				case "DEL":
					$dmlQuery = "DELETE FROM ".$tableName."  ";
					$condition = "";
					$firstField = true;
					foreach ($dt_fields as $myfield) {
						if ($myfield->fieldKey=="1"){

							if (!$firstField) {
								$condition .= "  AND ";
							}else{
								$condition .= "  WHERE ";
							}
							$firstField = false;
							switch($myfield->fieldType)
							{
								case "string":								
									$condition .=  $myfield->fieldName."='".$myfield->fieldValue."'";
									break;
								case "numeric":
									$numericvalue =  str_replace(",","",$myfield->fieldValue);
									$numericvalue =  str_replace(".","",$numericvalue);
									$condition .= $myfield->fieldName."=".$numericvalue ;
									break;
								case "date":
									$dateVal = strtotime($myfield->fieldValue);
									$condition .=  $myfield->fieldName."= CONVERT(datetime, '".date('Y/m/d',$dateVal)."', 102)";
									break;
								
							}
						}
					}
					if($condition != ""){
						$dmlQuery .= $condition.";";
					}else{
						$dmlQuery ="";
					}

					/* turn off for temporary security reason*/
					$dmlQuery = "";

					break;
			}

			return $dmlQuery;
		}
}
?>