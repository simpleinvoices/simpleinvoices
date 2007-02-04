<?php
/*
include('./config/config.php');
ob_start();
include("./lang/$language.inc.php");
ob_end_clean();

$conn = mysql_connect( $db_host, $db_user, $db_password );
mysql_select_db( $db_name, $conn );
*/

/**
* Function: get_custom_field_label
* 
* Prints the name of the custom field based on the input. If the custom field has not been defined by the user than use the default in the lang files
*
* Arguments:
* field		- The custom field in question
**/
function get_custom_field_label($field)         {

	include('./config/config.php');
	ob_start();
	include("./lang/$language.inc.php");
	ob_end_clean();

	$conn = mysql_connect( $db_host, $db_user, $db_password );
	mysql_select_db( $db_name, $conn );

        $sql =  "select cf_custom_label from si_custom_fields where cf_custom_field = '$field'";
        $result = mysql_query($sql,$conn) or die(mysql_error());

        while ($Array = mysql_fetch_array($result)) {
                $custom_field_label = $Array['cf_custom_label'];
                $cf_display = $Array['cf_display'];
        };

        //grab the last character of the field variable
        $get_cf_number = $field[strlen($field)-1];    

        //if custom field is blank in db use the one from the LANG files
        if ($custom_field_label == null) {
                $custom_field_label = ${"LANG_custom_field" . $get_cf_number};
        }
 	return $custom_field_label;
}

/**
* Function: get_custom_field_name
* 
* Used by manage_custom_fields to get the name of the custom field and which section it relates to (ie, biller/product/customer)
*
* Arguments:
* field         - The custom field in question
**/


function get_custom_field_name($field)
        {

        include('./config/config.php');
        ob_start();
        include("./lang/$language.inc.php");
        ob_end_clean();

        $conn = mysql_connect( $db_host, $db_user, $db_password );
        mysql_select_db( $db_name, $conn );
        

	//grab the last character of the field variable
        $get_cf_letter = $field[0];
        //grab the last character of the field variable
        $get_cf_number = $field[strlen($field)-1];
	
	if ($get_cf_letter == "b") {
		$custom_field_name = $LANG_biller;
	}
	if ($get_cf_letter == "c") {
		$custom_field_name = $LANG_customer;
	}
	if ($get_cf_letter == "i") {
		$custom_field_name = $LANG_invoice;
	}
	if ($get_cf_letter == "p") {
		$custom_field_name = $LANG_product;
	}
	
	$custom_field_name .= " :: " . $LANG_custom_field . " $get_cf_number" ;
        return $custom_field_name;
}

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
        ob_start();
        include('../config/config.php');
        include("../lang/$language.inc.php");
        ob_end_clean();

        if (($field1 != null OR $field2 != null OR $field3 != null) AND ($street1 ==null AND $street2 ==null)) {
                $ma .=  "<tr><td class='$class1'>$LANG_address:</td><td class='$class2' colspan=$colspan>";
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
                $print_cf =  "<td width=50%>$label: $field</td>";  
                return $print_cf;
        }
}


?>
