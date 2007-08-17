<?php


abstract class CustomField {
	
	var $name;
	var $id;
	var $description;
	var $fieldId;
	
	//array with categorie-id's
	//?var $categories;
	
	public function CustomField($id,$name) {
		$this->id = $id;
		$this->name = $name;
		//echo $id."  ".$name;
	}
	
	function installPlugin() {
	}
	
	function updatePlugin() {
	}
	
	//Please overwrite the following functions
	function printInputField() {
	}
	
	function updateInput($value, $itemId) {
		
		$sql = "UPDATE  `si_customFieldValues` SET  `value` =  '$value' WHERE  customFieldId = $this->fieldId AND itemId = $itemId" ;
		//$sql = "INSERT INTO si_customFieldValues (customFieldId,itemId,value) VALUES('".$this->fieldId."','".$itemId."','".$value."');";
		error_log($sql);
		mysqlQuery($sql);
	}
	
	function getFieldValue($customeFieldId, $itemId) {
		$sql = "SELECT * FROM si_customFieldValues WHERE (customFieldId = $customeFieldId && itemId = $itemId)";
		$query = mysqlQuery($sql);
		
		if($query) {
			$value = mysql_fetch_array($query);
			return $value['value'];
		}
		
		return "";
	}
	
	function getValue($id) {
		$sql = "SELECT * FROM si_customFieldValues WHERE id = $id";
		$query = mysqlQuery($sql);
		
		if($query) {
			$value = mysql_fetch_array($query);
			return $value['value'];
		}
		
		return "";
	}
	
	
	function saveInput($value,$itemId) {
		//error_log($value." aaa".$itemId);
		$sql = "INSERT INTO si_customFieldValues (customFieldId,itemId,value) VALUES('".$this->fieldId."','".$itemId."','".$value."');";
		//error_log($sql);
		mysqlQuery($sql);
		
	}
	
	function showField() {
	}
	//
	
	function setFieldId($id) {
		$this->fieldId = $id;
	}
	
	function getFormName($id) {
		return "cf".$id;
	}
	
	function setDescription($description) {
		$this->description = $description;
	}
	
	function getDescription($id) {
		global $LANG;

		$sql = "SELECT description FROM ".TB_PREFIX."customFields WHERE id = $id";
		$query = mysqlQuery($sql);
		$field = mysql_fetch_array($query);
		
		error_log('return "'.$field['description'].'";');
		return eval('return "'.$field['description'].'";');
	}
	
	/*function setActiveCategories($categories) {
		$this->categories = $categories;
	}?*/
	
	function getCustomFieldValues($id) {
		$sql = "SELECT * FROM ".TB_PREFIX."customFieldValues WHERE id = $id;";
		$query = mysql_query($sql);
		return mysql_fetch_array($query);
	}
}

?>