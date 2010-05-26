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
		
		echo "<tr><td>".htmlsafe($description)."</td><td><input name='".htmlsafe($name)."' value='".htmlsafe($value)."' type='hidden'>".htmlsafe($value)."</td></tr>";
	}
	
	function getLastValue() {
		$sql = "SELECT value FROM ".TB_PREFIX."customFieldValues WHERE customFieldId = 7 ORDER BY id DESC LIMIT 1;";
		$sth = dbQuery($sql);
		$result = $sth->fetch();
		error_log($sql);
		return $result['value'];
	}
}

?>
