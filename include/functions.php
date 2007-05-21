<?php

include("./include/sql_queries.php");

function checkLogin() {
	if (!defined("BROWSE")) {
		echo "You Cannot Access This Script Directly, Have a Nice Day.";
		exit();
	}
}

function getLogoList() {
	$dirname="./templates/invoices/logos";
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

function getLogo($biller) {
	if(!empty($biller['logo'])) {
		return "./templates/invoices/logos/$biller[logo]";
	}
	else {
		return "./templates/invoices/logos/_default_blank_logo.png";
	}
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
    $result = mysqlQuery($sql) or die(mysql_error());

    $cf = mysql_fetch_array($result);

    //grab the last character of the field variable
    $get_cf_number = $field[strlen($field)-1];    

    //if custom field is blank in db use the one from the LANG files
    if ($cf['cf_custom_label'] == null) {
       	$cf['cf_custom_label'] = $LANG['custom_field'] . $get_cf_number;
    }
        
    return $cf['cf_custom_label'];
}

/* 
 * Function: getCustomFieldLabels
 * 
 * Used to get the names of the custom fields. If custom fields is blank in db then print 'Custom Field' and the ID
 * Arguments:
 * Type 	- is the module your getting the labels of the custom fields for, ie. biller
 *
function getCustomFieldLabels($type) {
	global $LANG;
	global $tb_prefix;
	
	$sql = "SELECT cf_custom_label FROM {$tb_prefix}custom_fields WHERE cf_custom_field LIKE '".$type."_cf_'";
	$result = mysqlQuery($sql) or die(mysql_error());
	
	for($i=1;$row = mysql_fetch_row($result);$i++) {
		$cf[$i]=$row[0];
		if($cf[$i] == null) {
			$cf[$i] = $LANG["custom_field"].' '.$i;
		}
	}

	//TODO: What's the value if null? change in database...
	return $cf;
}
 */
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


function calc_invoice_total($inv_idField) {

	include('./config/config.php');
	ob_start();
	include("./lang/$language.inc.php");
	ob_end_clean();

	$conn = mysql_connect( $db_host, $db_user, $db_password );
	mysql_select_db( $db_name, $conn );


#invoice total total - start
$print_invoice_total ="SELECT sum(total) AS total FROM {$tb_prefix}invoice_items WHERE invoice_id =$inv_idField";
	$result_print_invoice_total = mysqlQuery($print_invoice_total, $conn) or die(mysql_error());

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
	$result_x1 = mysqlQuery($x1, $conn) or die(mysql_error());
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
			IF ( isnull( sum({$tb_prefix}invoice_items.total)) ,  '0', sum({$tb_prefix}invoice_items.total)) as total 
		FROM
			{$tb_prefix}invoice_items, {$tb_prefix}invoices 
		WHERE  
			{$tb_prefix}invoices.customer_id  = $c_idField  
		AND 
			{$tb_prefix}invoices.id = {$tb_prefix}invoice_items.invoice_id
		";
        $result_print_invoice_total_customer = mysqlQuery($print_invoice_total_customer, $conn) or die(mysql_error());

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
			{$tb_prefix}account_payments.ac_inv_id = {$tb_prefix}invoices.id 
		AND 
			{$tb_prefix}invoices.customer_id = $c_idField";  	

        $result_x2 = mysqlQuery($x2, $conn) or die(mysql_error());
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
	$print_invoice_total_tax ="select sum(tax_amount) as total_tax from {$tb_prefix}invoice_items where invoice_id =$master_invoice_id";
	$result_print_invoice_total_tax = mysqlQuery($print_invoice_total_tax, $conn) or die(mysql_error());

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
	global $tb_prefix;
	/*
	*get the last character of the $custom field - used to set the name of the field
	*/
	$custom_field_number =  substr($custom_field, -1, 1);


	#get the label for the custom field

	$display_block = "";

    $get_custom_label ="SELECT cf_custom_label FROM {$tb_prefix}custom_fields WHERE cf_custom_field = '$custom_field'";
	$result_get_custom_label = mysqlQuery($get_custom_label) or die(mysql_error());

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
				<td class="$css_class1">$custom_label_value <a href="docs.php?p=custom_fields&t=help" rel="gb_page_center[400, 400]"><img src="./images/common/help-small.png"></img></a>
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
<link rel="stylesheet" type="text/css" href="./templates/default/css/iehacks.css" media="all"/>
<![endif]-->
EOD;
}

?>
