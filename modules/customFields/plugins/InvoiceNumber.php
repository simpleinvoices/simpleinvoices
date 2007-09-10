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

class InvoiceNumber extends CustomField {
	
	function InvoiceNumber() {
		parent::CustomField(4,"InvoiceNumber");
	}
	
	function printOutput($id) {
		$values = getCustomFieldValues($id);
		echo $name.": ".$values['description'];
	}
	
	function printInputField($id,$itemId) {		
		$description = $this->getDescription($id);
		$name = $this->getFormName($id);
		
		if($itemId != "") {
			$value = $this->getFieldValue($id,$itemId);
		}
		else {
			$last = $this->getLastValue();
			$year = date("Y");
			
			if(preg_match("/([0-9]+)-([0-9]{4})/",$last,$match)) {
				if($year == $match[2]) {
					$number = $match[1]+1;
					$value = $number."-".$year;
				}
				else {
					$value = "1-".$year;
				}
			}
			else {
				$value = "1-".$year;
			}
				
		}
		
		echo "<tr><td>$description</td><td><input name='$name' value='$value' type='hidden'>$value</td></tr>";
	}
	
	function getLastValue() {
		$sql = "SELECT value FROM  `si_customFieldValues` WHERE customFieldId =7 ORDER BY id DESC LIMIT 1;";
		$query = mysqlQuery($sql);
		$result = mysql_fetch_array($query);
		error_log($sql);
		return $result['value'];
	}
}

?>
