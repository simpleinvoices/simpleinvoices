<?php

class TextCustomField extends CustomField {
	
	function TextCustomField() {
		parent::CustomField(3,"TextCustomField");
	}
	
	function printOutput($id) {
		$values = getCustomFieldValues($id);
		echo $name.": ".$values['description'];
	}
	
	function printInputField($id) {
		global $LANG;
		
		$sql = "SELECT * FROM ".TB_PREFIX."customFields WHERE id = $id";
		$query = mysqlQuery($sql);
		$field = mysql_fetch_array($query);
		$a = 2;
		
		error_log($field['description']);
				
		echo "<tr><td>".eval("return ".$field['description'].";")."</td><td><input ".$this->getFormName($id)." type='text'></td></tr>";
	}
}

?>
