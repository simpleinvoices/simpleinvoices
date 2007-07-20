<?php

/*
* Script: TextCustomField.php
* 	text custom field page
*
* Authors:
*	 Nicolas Ruflin
*
* Last edited:
* 	 2007-07-19
*
* License:
*	 GPL v2 or above
*
* Website:
* 	http://www.simpleinvoices.org
 */

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
