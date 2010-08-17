<?php

/*
* Script: DropDown.php
* 	dropdown custom field
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

class DropDown extends CustomField {
		
	function DropDown() {
		parent::CustomField(5,"DropDown");
	}
	
	function printOutput($id) {
		$values = getCustomFieldValues($id);
		echo $name.": ".$this->getList();
	}
	
	function getList($value = "",$name) {

		$array = array("hans","john","getrud","jonathan","fabian");

		$t = "<select name='$name'>";
		foreach($array as $item) {
			if($item == $value) {
				$t .= "<option selected >$item</option>";
			}
			else {
				$t .= "<option value='$item'>$item</option>";
			}
		}
		$t .= "</select>";
		
		return $t;
	}
	
	function printInputField($id,$itemId) {
		$name = $this->getFormName($id);

		if($itemId != "") {
			$value = $this->getList($this->getFieldValue($id,$itemId),$name);
		}
		else {
			$value = $this->getList("",$name);
		}
		
		echo "<tr><td>".htmlsafe($description)."</td><td>".htmlsafe($value)."</td></tr>";
	}
}

?>
