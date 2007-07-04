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
		$description = $this->getDescription($id);
		$name = $this->getFormName($id);
		$value = $this->getValue($id);
		
		echo "<tr><td>$description</td><td><input name='$name' value='$value' type='text'></td></tr>";
	}
}

?>
