<?php


abstract class CustomField {
	
	var $name;
	var $id;
	var $description;
	var $fieldId;
	//array with categorie-id's
	var $categories;
	
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
	
	
	function saveInput($value,$itemId) {
		//error_log($value." aaa".$itemId);
		$sql = "INSERT INTO si_customFieldValues (customFieldId,itemId,value) VALUES('".$this->fieldId."','".$itemId."','".$value."');";
		error_log($sql);
		mysqlQuery($sql);
		
	}
	
	function showField() {
	}
	//
	
	function setFieldId($id) {
		$this->fieldId = $id;
	}
	
	public function getFormName($id) {
		return ' name="cf'.$id.'" ';
	}
	
	function setDescription($description) {
		$this->description = $description;
	}
	
	function getDescription() {
		return "";	//Should be an sql querie
	}
	
	function setActiveCategories($categories) {
		$this->categories = $categories;
	}
	
	function getCustomFieldValues($id) {
		$sql = "SELECT * FROM ".TB_PREFIX."customFieldValues WHERE id = $id;";
		$query = mysql_query($sql);
		return mysql_fetch_array($query);
	}
}



/*
class Test extends CustomField {
	
	public function Test() {
		echo "cc";
		$id = 2;
		$name = "hha";
		//$parent = get_parent_class($this);
		//$this->$parent();
		parent::CustomField($id,$name);
	}
	
	public function printOutput() {
		echo "BBB";
		parent::printOutput();
	}
}

$clas = new Test();
$clas->printOutput();*/

?>