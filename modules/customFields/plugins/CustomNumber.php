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
 * 	 https://simpleinvoices.group/doku.php?id=si_wiki:menu */

class CustomNumber extends CustomField {
	
	function __consturct() {
		parent::CustomField(2,"CustomNumber");
	}
	
	function printOutput($id) {
		echo rand(0,100);
	}
	
	function printInputField($id,$itemId) {
		$description = $this->getDescription($id);
		$value = rand();
		$name = $this->getFormName($id);
		
		echo "<tr><input type='hidden' name='".htmlsafe($name)."' value='".htmlsafe($value)."'><td>".htmlsafe($description).":</td><td>".htmlsafe($value)."</td></tr>";
	}
}

?>
