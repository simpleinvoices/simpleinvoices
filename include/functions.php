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

function calc_invoice_total($inv_idField) {

	include('./config/config.php');
	ob_start();
	include("./lang/$language.inc.php");
	ob_end_clean();

	$conn = mysql_connect( $db_host, $db_user, $db_password );
	mysql_select_db( $db_name, $conn );


#invoice total total - start
	$print_invoice_total ="select sum(inv_it_total) as total from si_invoice_items where inv_it_invoice_id =$inv_idField";
	$result_print_invoice_total = mysql_query($print_invoice_total, $conn) or die(mysql_error());

	while ($Array = mysql_fetch_array($result_print_invoice_total)) {
                $invoice_total_Field = $Array['total'];
#invoice total total - end
	
	}
	return $invoice_total_Field;
}

function calc_invoice_paid($inv_idField) {

	include('./config/config.php');
	ob_start();
	include("./lang/$language.inc.php");
	ob_end_clean();

	$conn = mysql_connect( $db_host, $db_user, $db_password );
	mysql_select_db( $db_name, $conn );

#amount paid calc - start
	$x1 = "select IF ( isnull(sum(ac_amount)) , '0', sum(ac_amount)) as amount from si_account_payments where ac_inv_id = $inv_idField";
	$result_x1 = mysql_query($x1, $conn) or die(mysql_error());
	while ($result_x1Array = mysql_fetch_array($result_x1)) {
		$invoice_paid_Field = $result_x1Array['amount'];
		$invoice_paid_Field_format = number_format($result_x1Array['amount'],2);
#amount paid calc - end
	return $invoice_paid_Field;
	}
}

function calc_customer_total($c_idField) {

	include('./config/config.php');
	ob_start();
	include("./lang/$language.inc.php");
	ob_end_clean();

	$conn = mysql_connect( $db_host, $db_user, $db_password );
	mysql_select_db( $db_name, $conn );


#invoice total calc - start
        $print_invoice_total_customer ="
		SELECT
			IF ( isnull( sum(inv_it_total)) ,  '0', sum(inv_it_total)) as total 
		FROM
			si_invoice_items, si_invoices 
		WHERE  
			si_invoices.inv_customer_id  = $c_idField  
		AND 
			si_invoices.inv_id = si_invoice_items.inv_it_invoice_id
		";
        $result_print_invoice_total_customer = mysql_query($print_invoice_total_customer, $conn) or die(mysql_error());

        while ($Array_customer = mysql_fetch_array($result_print_invoice_total_customer)) {
                $invoice_total_Field_customer = $Array_customer['total'];
#invoice total calc - end
	}
	return $invoice_total_Field_customer;
}

function calc_customer_paid($c_idField) {

	include('./config/config.php');
	ob_start();
	include("./lang/$language.inc.php");
	ob_end_clean();

	$conn = mysql_connect( $db_host, $db_user, $db_password );
	mysql_select_db( $db_name, $conn );


#amount paid calc - start
        $x2 = "
		SELECT  
			IF ( isnull( sum(ac_amount)) ,  '0', sum(ac_amount)) as amount 
		FROM 
			si_account_payments, si_invoices 
		WHERE 
			si_account_payments.ac_inv_id = si_invoices.inv_id 
		AND 
			si_invoices.inv_customer_id = $c_idField";  	

        $result_x2 = mysql_query($x2, $conn) or die(mysql_error());
        while ($result_x2Array = mysql_fetch_array($result_x2)) {
                $invoice_paid_Field_customer = $result_x2Array['amount'];
	}
	return $invoice_paid_Field_customer;
}



function calc_invoice_tax($master_invoice_id) {

	include('./config/config.php');
	ob_start();
	include("./lang/$language.inc.php");
	ob_end_clean();

	$conn = mysql_connect( $db_host, $db_user, $db_password );
	mysql_select_db( $db_name, $conn );

	#invoice total tax
	$print_invoice_total_tax ="select sum(inv_it_tax_amount) as total_tax from si_invoice_items where inv_it_invoice_id =$master_invoice_id"; 
	$result_print_invoice_total_tax = mysql_query($print_invoice_total_tax, $conn) or die(mysql_error());

	while ($Array_tax = mysql_fetch_array($result_print_invoice_total_tax)) {
                $invoice_total_taxField = $Array_tax['total_tax'];
	}
	return $invoice_total_taxField;
}

function show_custom_field($custom_field) {

	include('./config/config.php');

	$conn = mysql_connect( $db_host, $db_user, $db_password );
	mysql_select_db( $db_name, $conn );

	#invoice total tax
	$get_custom_label ="select cf_custom_label from si_custom_fields where cf_custom_field = '$custom_field'"; 
	$result_get_custom_label = mysql_query($get_custom_label, $conn) or die(mysql_error());

	while ($Array_cl = mysql_fetch_array($result_get_custom_label)) {
                $custom_label_value = $Array_cl['cf_custom_label'];
	}
	if ($custom_label_value != null) {
	
		$display_block ="
			<tr>
				<td class=\"details_screen\">$custom_label_value <a href=\"./documentation/info_pages/custom_fields.html?keepThis=true&TB_iframe=true&height=300&width=500\" title=\"Info :: Custom fields\" class=\"thickbox\"><img src=\"./images/common/help-small.png\"></img></a>
				</td>
				<td>
					<input type=text name=\"i_custom_field\" size=25>
				</td>
			</tr>
			";
	}
	return $display_block;
}

?>
