<?php


abstract class CustomField {
	
	var $name;
	var $id;
	var $description;
	//array with categorie-id's
	var $categories;
	
	public function CustomField($id,$name) {
		$this->id = $id;
		$this->name = $name;
		//echo $id."  ".$name;
	}
	
	public function printOutput() {
	}
	
	function installPlugin() {
	}
	
	function updatePlugin() {
	}
	
	function setDescription($description) {
		$this->description = $description;
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