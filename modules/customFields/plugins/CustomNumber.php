<?php

class CustomNumber extends CustomField {
	
	function CustomNumber() {
		parent::CustomField(2,"CustomNumber");
	}
	
	function printOutput($id) {
		echo rand(0,100);
	}
}

?>
