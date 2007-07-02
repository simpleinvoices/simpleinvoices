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
		$sql = "SELECT * FROM ".TB_PREFIX."customFields WHERE id = $id";
		$query = mysqlQuery($sql);
		$field = mysql_fetch_array($query);
		
		echo "<tr><td>$field[description]:</td><td><input type='text'></td></tr>";
	}
}

?>
