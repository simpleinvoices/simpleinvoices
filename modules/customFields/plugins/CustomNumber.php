<?php
/*
 * Script: CustomNumber.php
 * 	test custom field page
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
 * 	 http://www.simpleinvoices.org
 */

class CustomNumber extends CustomField {
	
	function CustomNumber() {
		parent::CustomField(2,"CustomNumber");
	}
	
	function printOutput($id) {
		echo rand(0,100);
	}
	
	function printInputField($id) {
		$description = $this->getDescription($id);
		$value = rand();
		$name = $this->getFormName($id);
		
		echo "<tr><input type='hidden' name='$name' value='$value'><td>$description:</td><td>$value</td></tr>";
	}
}

?>
