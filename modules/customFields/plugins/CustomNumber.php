<?php

class CustomNumber extends CustomField {
	
	function CustomNumber() {
		parent::CustomField(2,"CustomNumber");
	}
	
	function printOutput($id) {
		echo rand(0,100);
	}
	
	function printInputField($id) {
		echo "<tr><input type='hidden' ".$this->getFormName($id)." value='".rand()."'><td>".$this->getDescription().":</td><td>".rand()."</td></tr>";
	}
}

?>
