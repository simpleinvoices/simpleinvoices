<?php

class TextCustomField extends CustomField {
	
	function TextCustomField() {
		parent::CustomField(3,"TextCustomField");
	}
	
	function printOutput($id) {
		$values = getCustomFieldValues($id);
		echo $name.": ".$values['description'];
	}
}

?>
