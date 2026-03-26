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
		
	function __construct() {
		parent::__construct(5, "DropDown");
	}
	
	function printOutput($id) {
		$values = getCustomFieldValues($id);
		echo $name.": ".$this->getList();
	}
	
	function getList($value = "", $name = "") {

		$array = array("hans","john","getrud","jonathan","fabian");

		$t = "<select name='$name' class='form-select'>";
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
	
	function printInputField($id = null, $itemId = null) {
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
