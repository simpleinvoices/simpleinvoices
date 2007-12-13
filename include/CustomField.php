<?php
/**
 * Script: CustomField.php
 *
 * Abstract class that should extend each custom field.
 * It defines basic function for all plugins that should be used.
 * So we can change CustomField a bit without changing each CustomField.
 * Some function need to be overwritten otherwise their is no output.
 *
 * The $id of a CustomField have to be unique. It's hardcoded into the plugin,
 * so before setting an id, please check the other plugins.
 * The id's from 0-99 are reserved for core plug-ins. So please use an id > 100.
 *
 * Authors:
 *	 Nicolas Ruflin
 *
 * Last edited:
 * 	 2007-09-10
 *
 * License:
 *	 GPL v2 or above
 */
abstract class CustomField {
	
	var $name;
	var $id;
	var $description;
	var $fieldId;	//to differentiate between the instances of a plug-in
	
	/* Constructor: name and id for each CustomField needed. */
	public function CustomField($id,$name) {
		$this->id = $id;
		$this->name = $name;
	}
	
	function installPlugin() {
	}
	
	function updatePlugin() {
	}
	
	/***** Please overwrite the following functions *****/
	function printInputField() {
	}
	/***** Please overwrite the above functions *****/
	
	/* Updates the custom field value */
	function updateInput($value, $itemId) {
		$sql = "SELECT * FROM ".TB_PREFIX."customFieldValues WHERE customFieldID = $this->fieldId AND itemID = $itemId";
		
		error_log($sql);
		$query = mysql_query($sql);
		$result = mysql_fetch_array($query);
		
		if($result == null) {
			//error_log("no value -> set value");
			$this->saveInput($value,$itemId);
		}
		else {
			$sql = "UPDATE  `".TB_PREFIX."customFieldValues` SET  `value` =  '$value' WHERE  customFieldId = $this->fieldId AND itemId = $itemId" ;
			mysqlQuery($sql);
		}
	}
	
	/* Returns the value for a choosen field and item. Should be unique, because the itemId for each categorie is unique. */
	function getFieldValue($customeFieldId, $itemId) {
		$sql = "SELECT * FROM ".TB_PREFIX."customFieldValues WHERE (customFieldId = $customeFieldId && itemId = $itemId)";
		$query = mysqlQuery($sql);
		
		if($query) {
			$value = mysql_fetch_array($query);
			return $value['value'];
		}
		
		return "";
	}
	
	function getValue($id) {
		$sql = "SELECT * FROM ".TB_PREFIX."customFieldValues WHERE id = $id";
		$query = mysqlQuery($sql);
		
		if($query) {
			$value = mysql_fetch_array($query);
			return $value['value'];
		}
		
		return "";
	}
	
	/* Stores the input into the database */
	function saveInput($value,$itemId) {
		//error_log($value." aaa".$itemId);
		$sql = "INSERT INTO ".TB_PREFIX."customFieldValues (customFieldId,itemId,value) VALUES('".$this->fieldId."','".$itemId."','".$value."');";
		//error_log($sql);
		mysqlQuery($sql);
	}
	
	function showField() {
	}
	
	function setFieldId($id) {
		$this->fieldId = $id;
	}
	
	function getFormName($id) {
		return "cf".$id;
	}
	
	function setDescription($description) {
		$this->description = $description;
	}
	
	/**
	 * Reads the description for a customField out of the database.
	 * If it's a language string it's translated to the choosen language.
	 * The language string have to be in the following format:
	 * $LANG[name] or {$LANG['name']}
	 */
	function getDescription($id) {
		global $LANG;

		$sql = "SELECT description FROM ".TB_PREFIX."customFields WHERE id = $id";
		$query = mysqlQuery($sql);
		$field = mysql_fetch_array($query);
		
		return eval('return "'.$field['description'].'";');
	}
	
	//TODO: activate and deactivate plugins... What happens if you delete a plug-in?
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