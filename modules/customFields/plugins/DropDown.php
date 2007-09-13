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
	
	function getList($value = "") {
		$array = array("hans","john","getrud","jonathan","fabian");

		$t = "<select>";
		foreach($array as $item) {
			if($item == $value) {
				$t .= "<option selected >$item</option>";
			}
			else {
				$t .= "<option>$item</option>";
			}
		}
		$t .= "</select>";
		
		return $t;
	}
	
	function printInputField($id,$itemId) {
		
		if($itemId != "") {
			//Sould be replace by customFieldId and Itemid
			$value = $this->getList($this->getFieldValue($id,$itemId));
		}
		else {
			$value = $this->getList("");
		}
		
		echo "<tr><td>$description</td><td>".$value."</td></tr>";
	}
}

?>
