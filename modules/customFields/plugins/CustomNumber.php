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
	
	function __construct() {
		parent::__construct(2, "CustomNumber");
	}
	
	function printOutput($id) {
		echo rand(0,100);
	}
	
	function printInputField($id = null, $itemId = null) {
		$description = $this->getDescription($id);
		$value = rand();
		$name = $this->getFormName($id);
		
		echo "<tr><input type='hidden' name='".htmlsafe($name)."' value='".htmlsafe($value)."'><td>".htmlsafe($description).":</td><td>".htmlsafe($value)."</td></tr>";
	}
}

?>
