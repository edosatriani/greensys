<?php

class object_Controller {

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

			if ( $shield->shield($_POST['MODIFY_STATUS'])=="INS") {
				$fieldtarget = '{"fieldName":"ROADOFSPK","fieldValue":"0","fieldType":"numeric","fieldKey":"0"}';

				$additionalField = "";
				if ( $shield->shield($_POST['CARA_BAYAR']) == "KREDIT"){
					$additionalField .= '{"fieldName":"SISA_BAYAR","fieldValue":"'.$_POST["DP_SYSTEM"].'","fieldType":"numeric"}';
					$additionalField .= ',{"fieldName":"STATUS_PO","fieldValue":"1. INDENT","fieldType":"string"}';
					$additionalField .= ',{"fieldName":"AR_AMOUNT","fieldValue":"'.$_POST["DP_SYSTEM"].'","fieldType":"numeric"}';
				}else{
					$additionalField .= '{"fieldName":"SISA_BAYAR","fieldValue":"'.$_POST["HARGA"].'","fieldType":"numeric"}';
					$additionalField .= ',{"fieldName":"AR_AMOUNT","fieldValue":"'.$_POST["HARGA"].'","fieldType":"numeric"}';
				}
				$additionalField .= ',{"fieldName":"STATUS_SPK","fieldValue":"1","fieldType":"string"}';
				$additionalField .= ',{"fieldName":"STATUS_JUAL","fieldValue":"1. SPK","fieldType":"string"}';
				$additionalField .= ',{"fieldName":"STATUS_KIRIM","fieldValue":"N","fieldType":"string"}';
				$additionalField .= ',{"fieldName":"STATUS_ARK","fieldValue":"N","fieldType":"string"}';
				$additionalField .= ',{"fieldName":"CUSTOM_NO","fieldValue":"'. $shield->shield($_POST["NO_SPK"]).'","fieldType":"string"}';
				$additionalField .= ',{"fieldName":"ROADOFSPK","fieldValue":"0","fieldType":"numeric"}';


				$json_file = str_replace($fieldtarget,$additionalField,$json_file);
			}

			//echo $json_file;
			// convert the string to a json object

			$jfo = json_decode($json_file);

			$relField = "";
			$relCount = 0;

			$relTables = $jfo->dt_objectTable->dt_relatedTables;
			foreach ($relTables as $relTbl) {
				$myTable =  $relTbl->dt_relatedTable;
				$relTableName = $myTable->tableName;
				$relautonumFormat = $myTable->autonumFormat;
				$relModifyStatus = $myTable->modify_status;
				$dt_relFields = $myTable->dt_relfieldCollection;
				$queryCollection .= $this->generateQuery($dt_relFields,$relTableName,$relautonumFormat,$relModifyStatus,$relCount);
				$relCount = $relCount + 1;
			}

			$tableName = $jfo->dt_objectTable->tableName;
			$autonumFormat = $jfo->dt_objectTable->autonumFormat;
			$modifystatus = $jfo->dt_objectTable->modify_status;
			$dt_fields = $jfo->dt_objectTable->dt_fieldsCollection;

			$queryCollection .= $this->generateQuery($dt_fields,$tableName,$autonumFormat,$modifystatus,0);

			//echo $queryCollection;

			$model = new object_Model;
			$result =$model->executeQuery($queryCollection);

			$weblog = new objectupdate();
			$result = $weblog->record_webaccess_log("DML : ".$$modifystatus."-".$tableName."-".$shield->shield($_POST["NO_SPK"]));

			header("Location: entry-spk");
			
		} catch (Exception $e) {
			throw new Exception($e->getMessage());
		}
	 }

	 public function generateQuery($dt_fields,$tableName,$autonumFormat,$modifystatus,$relCount){
			$dmlQuery = "NO SQL";
			global $relField;
			global $relFieldKey;
			global $relFieldKeyMode;

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
								if (count($dataflagArr)>2) {
									if ($dataflagArr[3]=="999"){
										$relFieldKeyMode = true;
									}
								}

							}
						}

						switch($myfield->fieldType)
						{
							case "string":
								if ($data_key=="true") {
									$model = new object_Model;
									$autonumber = $model->AutoNumberWithFormat($tableName,$myfield->fieldName,$autonumFormat."/".date("ym")."/","4");
									$dmlQuery .=  "'".$autonumber."'";

									if($relField != "") {
										$relField .= ",";
									}
									$relField .= $myfield->fieldName."_".$relCount.":".$autonumber;
									if ($relFieldKeyMode) {
										$relFieldKey = $autonumber;
									}
									$relCount++;
								}else{
									if ($data_flag == "related-field"){
										if ($relFieldKeyMode) {
											$dmlQuery .=  "'".$relFieldKey."'";
										}else{
											$data_rel_fieldArr = explode(",",$relField);
											if (count($data-rel_fieldArr)>0) {
												for($i = 0; $i < count($data_rel_fieldArr); $i++){
													$content_rel_fieldArr = explode(":",$data_rel_fieldArr[$i]);
													if ($content_rel_fieldArr[0] == $data_key) {
														$dmlQuery .=  "'".$content_rel_fieldArr[1]."'";
													}
												}
											}else{
												$dmlQuery .=  "'".$myfield->fieldValue."'";
											}
										}
									}else{
										$dmlQuery .=  "'".$myfield->fieldValue."'";
									}
								}
								break;
							case "numeric":
								$numericvalue =  str_replace(",","",$myfield->fieldValue);
								$numericvalue =  str_replace(".","",$numericvalue);
						                if (	$numericvalue == "" ) {
						                    $numericvalue = 0;
						                }
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
									if (count($dataflagArr)>2) {
										if ($dataflagArr[3]=="999"){
											$relFieldKeyMode = true;
										}
									}
								}
							}

							switch($myfield->fieldType)
							{
								case "string":
									if ($relFieldKeyMode) {
										$dmlQuery .=   $myfield->fieldName."='".$relFieldKey."'";
										$relFieldKeyMode = false;
									}else{
										$dmlQuery .=  $myfield->fieldName."='".$myfield->fieldValue."'";
									}
									break;
								case "numeric":
									$numericvalue =  str_replace(",","",$myfield->fieldValue);
									$numericvalue =  str_replace(".","",$numericvalue);
							                  if (	$numericvalue == "" ) {
							                    	$numericvalue = 0;
							                  }
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
							                  if (	$numericvalue == "" ) {
							                    	$numericvalue = 0;
							                  }
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
							                  if (	$numericvalue == "" ) {
							                    	$numericvalue = 0;
							                  }
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
