{php}
/**
* Function: do_tr
* 
* Print a new table row "</tr><tr>" depending on the input, used in printing the custom fields and phone numbers section of the invoice
*
* Arguments:
* number          -      used to count which item the codes upto and depending print the trs 
* class		  - 	 the css class for the tr
**/
function do_tr($number,$class) {
	if ($number == 2 ) {
		$new_tr = "</tr><tr class=\"$class\">";
		return $new_tr;
	}
	
        if ($number == 4 ) {
                $new_tr = "</tr><tr class=\"$class\">";
                return $new_tr;
        }

	
}

/**
* Function: merge_address
* 
* Merges the city, state, and zip info onto one live and takes into account the commas 
*
* Arguments:
* field1          -       normally city
* field2          -       noramlly state
* field3          -       normally zip  
* street1         -      street 1 added print the word "Address:" on the first line of the invoice
* street2         -      street 2 added print the word "Address:" on the first line of the invoice
* class1          -      the css class for the first td
* class2          -      the css class for the second td
* colspan          -      the td colspan of the last td
**/


function merge_address($field1,$field2,$field3,$street1,$street2,$class1,$class2,$colspan) {
        if (($field1 != null OR $field2 != null OR $field3 != null) AND ($street1 ==null AND $street2 ==null)) {
                $ma .=  "<tr><td class='$class1'>$LANG[address]:</td><td class='$class2' colspan=$colspan>";
		$skip_section = 1;
        }
        if (($field1 != null OR $field2 != null OR $field3 != null) AND( $skip_section != 1)) {
                $ma .=  "<tr><td class='$class1'></td><td class='$class2' colspan=$colspan>";
        }
        if ($field1 != null) {
                $ma .=  "$field1";
        }

        if ($field1 != null AND $field2 != null  ) {
                $ma .=  ", ";
        }

        if ($field2 != null) {
                $ma .=  "$field2";
        }

        if (($field1 != null OR $field2 != null) AND ($field3 != null)) {
                $ma .=  ", ";
        }

        if ($field3 != null) {
                $ma .=  "$field3";
        }
		
	$ma .= "</td></tr>";
	return $ma;
}

/**
* Function: print_if_not_null
* 
* Used in the print preview to determine if a row/field gets printed, basically if the field is null dont print it else do
*
* Arguments:
* label		- The name of the field, ie. Custom Field 1, Email, etc..
* field		- The actual value from the db ie, test@test.com for email etc...
* class1	- the css class of the first td
* class2	- the css class of the second td
* colspan	- the colspan of the last td
**/
function print_if_not_null($label,$field,$class1,$class2,$colspan) {
        if ($field != null) {
                $print_if_not_null =  "
                <tr>
                        <td class='$class1'>$label:<td class='$class2' colspan=$colspan>$field</td>
                </tr>";  
		return $print_if_not_null;
        }
}

/**
* Function: inv_itemised_cf
* 
* Prints the custom fields for the product in an itemised invoice
*
* Arguments:
* label		- The name of the field, ie. Custom Field 1, etc..
* field		- The actual value from the db ie, ABN-12-34-66 etc...
**/
function inv_itemised_cf($label,$field) {
        if ($field != null) {
                $print_cf =  "<td width=50%>TEST $label: $field</td>";  
                return $print_cf;
        }
}
{/php}
