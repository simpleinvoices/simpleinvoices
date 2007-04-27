<?php

include("./include/sql_queries.php");

function checkLogin() {
	if (!defined("BROWSE")) {
		echo "You Cannot Access This Script Directly, Have a Nice Day.";
		exit();
	}
}

function getLogoList() {
	$dirname="images/logo";
	$ext = array("jpg", "png", "jpeg", "gif");
	$files = array();
	if($handle = opendir($dirname)) {
		while(false !== ($file = readdir($handle)))
		for($i=0;$i<sizeof($ext);$i++)
		if(stristr($file, ".".$ext[$i])) //NOT case sensitive: OK with JpeG, JPG, ecc.
		$files[] = $file;
		closedir($handle);
	}

	sort($files);
	
	return $files;
}
/*
* Script: functions.php
*	Contain all the functions used in Simple Invoices
*
* Authors:
*	- Justin Kelly
*
* License:
*	GNU GPL2 or above
*
* Date last edited:
*	Fri Feb 16 21:48:02 EST 2007
**/

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


    $sql =  "SELECT cf_custom_label FROM {$tb_prefix}custom_fields WHERE cf_custom_field = '$field'";
    $result = mysql_query($sql) or die(mysql_error());

    $cf = mysql_fetch_array($result);

    //grab the last character of the field variable
    $get_cf_number = $field[strlen($field)-1];    

    //if custom field is blank in db use the one from the LANG files
    if ($cf['cf_custom_label'] == null) {
       	$cf['cf_custom_label'] = ${"LANG['custom_field']" . $get_cf_number};
    }
        
    return $cf['cf_custom_label'];
}

/* 
 * Function: getCustomFieldLabels
 * 
 * Used to get the names of the custom fields. If custom fields is blank in db then print 'Custom Field' and the ID
 * Arguments:
 * Type 	- is the module your getting the labels of the custom fields for, ie. biller
 */
function getCustomFieldLabels($type) {
	include('./config/config.php');
	ob_start();
	include("./lang/$language.inc.php");
	ob_end_clean();
	
	$sql = "SELECT cf_custom_label FROM {$tb_prefix}custom_fields WHERE cf_custom_field LIKE '".$type."_cf_'";
	$result = mysql_query($sql) or die(mysql_error());
	
	for($i=1;$row = mysql_fetch_row($result);$i++) {
		$cf[$i]=$row[0];
		if($cf[$i] == null) {
			$cf[$i] = $LANG["custom_field"].' '.$i;
		}
	}

	//TODO: What's the value if null? change in database...
	return $cf;
}

/**
* Function: get_custom_field_name
* 
* Used by manage_custom_fields to get the name of the custom field and which section it relates to (ie, biller/product/customer)
*
* Arguments:
* field         - The custom field in question
**/


function get_custom_field_name($field) {

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
		$custom_field_name = $LANG['biller'];
	}
	if ($get_cf_letter == "c") {
		$custom_field_name = $LANG['customer'];
	}
	if ($get_cf_letter == "i") {
		$custom_field_name = $LANG['invoice'];
	}
	if ($get_cf_letter == "p") {
		$custom_field_name = $LANG['product'];
	}
	
	$custom_field_name .= " :: " . $LANG["custom_field"] . " " . $get_cf_number ;
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
$print_invoice_total ="SELECT sum(inv_it_total) AS total FROM {$tb_prefix}invoice_items WHERE inv_it_invoice_id =$inv_idField";
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
$x1 = "SELECT IF ( isnull(sum(ac_amount)) , '0', sum(ac_amount)) AS amount FROM {$tb_prefix}account_payments WHERE ac_inv_id = $inv_idField";
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
			{$tb_prefix}invoice_items, {$tb_prefix}invoices 
		WHERE  
			{$tb_prefix}invoices.inv_customer_id  = $c_idField  
		AND 
			{$tb_prefix}invoices.inv_id = {$tb_prefix}invoice_items.inv_it_invoice_id
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
			{$tb_prefix}account_payments, {$tb_prefix}invoices 
		WHERE 
			{$tb_prefix}account_payments.ac_inv_id = {$tb_prefix}invoices.inv_id 
		AND 
			{$tb_prefix}invoices.inv_customer_id = $c_idField";  	

        $result_x2 = mysql_query($x2, $conn) or die(mysql_error());
        while ($result_x2Array = mysql_fetch_array($result_x2)) {
                $invoice_paid_Field_customer = $result_x2Array['amount'];
	}
	return $invoice_paid_Field_customer;
}



/**
* Function: calc_invoice_tax
* 
* Calculates the total tax for a given invoices
*
* Arguments:
* invoice_id		- The name of the field, ie. Custom Field 1, etc..
**/
function calc_invoice_tax($master_invoice_id) {

	include('./config/config.php');
	ob_start();
	include("./lang/$language.inc.php");
	ob_end_clean();

	$conn = mysql_connect( $db_host, $db_user, $db_password );
	mysql_select_db( $db_name, $conn );

	#invoice total tax
	$print_invoice_total_tax ="select sum(inv_it_tax_amount) as total_tax from {$tb_prefix}invoice_items where inv_it_invoice_id =$master_invoice_id";
	$result_print_invoice_total_tax = mysql_query($print_invoice_total_tax, $conn) or die(mysql_error());

	while ($Array_tax = mysql_fetch_array($result_print_invoice_total_tax)) {
                $invoice_total_taxField = $Array_tax['total_tax'];
	}
	return $invoice_total_taxField;
}


/**
* Function: show_custom_field
* 
* If a custom field has been defined then show it in the add,edit, or view invoice screen - This is used for the Invoice Custom Fields - may be used for the others as wll based on the situation
*
* Parameters:
* custom_field		- the db name of the custom field ie invoice_cf1
* custom_field_value	- the value of this custom field for a given invoice
* permission		- the permission level - ie. in a print view its gets a read level, in an edit or add screen its write leve
* css_class_tr		- the css class the the table row (tr)
* css_class1		- the css class of the first td
* css_class2		- the css class of the second td
* td_col_span		- the column span of the right td
* seperator		- used in the print view ie. adding a : between the 2 values
*
* Returns:
* Depending on the permission passed, either a formatted input box and the label of the custom field or a table row and data
**/

function show_custom_field($custom_field,$custom_field_value,$permission,$css_class_tr,$css_class1,$css_class2,$td_col_span,$seperator) {
	
	/*
	*get the last character of the $custom field - used to set the name of the field
	*/
	$custom_field_number =  substr($custom_field, -1, 1);

	include('./config/config.php');


	#get the label for the custom field

        $conn = mysql_connect( $db_host, $db_user, $db_password );
        mysql_select_db( $db_name, $conn );


        $get_custom_label ="SELECT cf_custom_label FROM {$tb_prefix}custom_fields WHERE cf_custom_field = '$custom_field'";
	$result_get_custom_label = mysql_query($get_custom_label, $conn) or die(mysql_error());

	while ($Array_cl = mysql_fetch_array($result_get_custom_label)) {
                $has_custom_label_value = $Array_cl['cf_custom_label'];
	}
	/*if permision is write then coming from a new invoice screen show show only the custom field and have a label
	* if custom_field_value !null coming from existing invoice so show only the cf that they actually have
	*/	
	if ( (($has_custom_label_value != null) AND ( $permission == "write")) OR ($custom_field_value != null)) {

		$custom_label_value = get_custom_field_label($custom_field);

		if ($permission == "read") {
			$display_block = <<<EOD
			<tr class="$css_class_tr" >
				<td class="$css_class1">
					$custom_label_value$seperator
				</td>
				<td class="$css_class2" colspan="$td_col_span" >
					$custom_field_value
				</td>
			</tr>
EOD;
		}

		else if ($permission == "write") {

		$display_block = <<<EOD
			<tr>
				<td class="$css_class1">$custom_label_value <a href="./documentation/info_pages/custom_fields.html" rel="gb_page_center[400, 400]"><img src="./images/common/help-small.png"></img></a>
				</td>
				<td>
					<input type=text name="i_custom_field$custom_field_number" value="$custom_field_value"size=25></input>
				</td>
			</tr>
EOD;
		}
	}
	return $display_block;
}

function getRicoLiveGrid($name, $columnSpecs) {
	
	echo <<<EOD
	<script src="./modules/include/js/lgplus/js/rico.js" type="text/javascript"></script>
	<script type='text/javascript'>
	Rico.loadModule('LiveGrid');
	Rico.loadModule('LiveGridMenu');
EOD;

	setStyle();
	setLang();

	echo <<<EOD
	Rico.onLoad( function() { var opts = {
EOD;

GridSettingsScript();

echo <<<EOD
, columnSpecs : [ , $columnSpecs ] };
var menuopts =
EOD;

GridSettingsMenu();

echo <<<EOD
; new Rico.LiveGrid ('$name', new Rico.GridMenu(menuopts), new
Rico.Buffer.Base($('$name').tBodies[0]), opts); });
</script>

<!--[if gte IE 5.5]>
<link rel="stylesheet" type="text/css" href="./modules/include/css/iehacks.css" media="all"/>
<![endif]-->
EOD;
}

?>
