<?php

class CustomNumber extends CustomField {
	
	function CustomNumber() {
		parent::CustomField(2,"CustomNumber");
	}
	
	function printOutput($id) {
		echo rand(0,100);
	}
	
	function printInputField($id) {
		echo "<tr><td>Random Number:</td><td>".rand()."</td></tr>";
	}
}

?>
