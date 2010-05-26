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
	
	function printInputField($id,$itemId) {		
		$description = $this->getDescription($id);
		$name = $this->getFormName($id);
		
		if($itemId != "") {
			//Sould be replace by customFieldId and Itemid
			$value = $this->getFieldValue($id,$itemId);
		}
		else {
			$value = "";
		}
		
		echo "<tr><td>".htmlsafe($description)."</td><td><input name='".htmlsafe($name)."' value='".htmlsafe($value)."' type='hidden'>".htmlsafe($value)."</td></tr>";
	}
}

?>
