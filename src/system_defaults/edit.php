<?php
include('./include/include_main.php');

//stop the direct browsing to this file - let index.php handle which files get displayed
if (!defined("BROWSE")) {
   echo "You Cannot Access This Script Directly, Have a Nice Day.";
   exit();
}

include('./include/validation.php');


$conn = mysql_connect("$db_host","$db_user","$db_password");
mysql_select_db("$db_name",$conn);


#system defaults query
$print_defaults = "SELECT * FROM {$tb_prefix}defaults WHERE def_id = 1";
$result_print_defaults = mysql_query($print_defaults, $conn) or die(mysql_error());


while ($Array_defaults = mysql_fetch_array($result_print_defaults) ) {
                $def_idField = $Array_defaults['def_id'];
                $def_customerField = $Array_defaults['def_customer'];
                $def_billerField = $Array_defaults['def_biller'];
                $def_taxField = $Array_defaults['def_tax'];
                $def_inv_preferenceField = $Array_defaults['def_inv_preference'];
                $def_number_line_itemsField = $Array_defaults['def_number_line_items'];
                $def_inv_templateField = $Array_defaults['def_inv_template'];
                $def_payment_typeField = $Array_defaults['def_payment_type'];
};



if ($_GET[submit] == "line_items") {

	jsBegin();
	jsFormValidationBegin("frmpost");
	jsValidateifNum("def_num_line_items","Default number of line items");
	jsFormValidationEnd();
	jsEnd();

	$default = "line_items";
	$inv_num_line_items = "select def_number_line_items from {$tb_prefix}defaults where def_id=1";
	$result_inv_preferences = mysql_query($inv_num_line_items, $conn) or die(mysql_error());

	while ($Array = mysql_fetch_array($result_inv_preferences) ) {
                $def_number_line_itemsField = $Array['def_number_line_items'];


	$display_block = "
	<tr>
		<td><br></td>
	</tr>
	<tr>
		<td class=\"details_screen\">$msd_default_number_items</td>
		<td><input type=text size=25 name='def_num_line_items' value=$def_number_line_itemsField></td>
	</tr>
	<tr>
		<td><br></td>
	</tr>

";
}
}

else if ($_GET[submit] == "def_inv_template") {


	/*drop down list code for invoice template - only show the folder names in src/invoices/templates*/

	$handle=opendir("./src/invoices/templates/");
		while ($file = readdir($handle)) {
			if ($file != ".." && $file != "." && $file !=".svn" && $file !="template.php" && $file !="template.php~" ) {
				$files[] = $file;
			}
		}
	closedir($handle);	
	
	sort($files);

	$display_block_templates_list = "<select name=\"def_inv_template\">";

	$display_block_templates_list .= "<option selected value='$def_inv_templateField' style=\"font-weight: bold\" >$def_inv_templateField</option>";

	foreach ( $files as $var )
	{
		$display_block_templates_list .= "<option>";
		$display_block_templates_list .= $var;
		$display_block_templates_list .= "</option>";
	}
	
	$display_block_templates_list .= "</select>";

	/*end drop down list section */
	/*start validataion section */

        jsBegin();
        jsFormValidationBegin("frmpost");
        jsValidateRequired("def_inv_template","$msd_js_alert_def_inv_template");
        jsFormValidationEnd();
        jsEnd();
	/*end validataion section */

	$default = "def_inv_template";
	$def_inv_template = "select def_inv_template from {$tb_prefix}defaults where def_id=1";
	$result_def_inv_template = mysql_query($def_inv_template, $conn) or die(mysql_error());

	while ($Array = mysql_fetch_array($result_def_inv_template) ) {
                $def_inv_templateField = $Array['def_inv_template'];


	$display_block = "
	<tr>
		<td><br></td>
	</tr>
	<!--
	<tr>
		<td colspan=2><a href='text/default_invoice_template_text.html' class='lbOn'>Note</a></td>
	</tr>
	-->
	<tr>
		<td class=\"details_screen\">$msd_def_inv_template <a href='./src/documentation/info_pages/default_invoice_template_text.html' rel='ibox&height=400'><img src=\"images/common/help-small.png\"></img></a></td>
		<td>$display_block_templates_list</td>
	</tr>
	<tr>
		<td><br></td>
	</tr>

";
}
}

else if ($_GET[submit] == "biller") {


	#default biller query
	$sql_biller_default = "SELECT b_name FROM {$tb_prefix}biller where b_id = $def_billerField";
	$result_biller_default = mysql_query($sql_biller_default , $conn) or die(mysql_error());

	while ($Array = mysql_fetch_array($result_biller_default) ) {
                $sql_biller_defaultField = $Array['b_name'];
	}
	
	#biller query
	$sql = "SELECT * FROM {$tb_prefix}biller where b_enabled != 0 ORDER BY b_name";
	$result = mysql_query($sql, $conn) or die(mysql_error());

	#biller selector

	if (mysql_num_rows($result) == 0) {
        	//no records
	        $display_block = "<p><em>$mb_no_invoices</em></p>";

	} else {
        	//has records, so display them

		$default = "def_biller";
      

		 $display_block_biller = "
	        <select name=\"default_biller\">
	        <option selected value=\"$def_billerField\" style=\"font-weight: bold\">$sql_biller_defaultField</option>
	        <option value='0'> </option>";

        	while ($recs = mysql_fetch_array($result)) {
                	$id = $recs['b_id'];
	                $display_name = $recs['b_name'];

	                $display_block_biller .= "<option value=\"$id\">
	                        $display_name</option>";
	        }
	}

	$display_block ="
	<tr>
		<td><br></td>
	</tr>
	<tr>
		<td class=\"details_screen\">$mb_table_biller_name</th><td>$display_block_biller</td>
	</tr>
	<tr>
		<td><br></td>
	</tr>
	";

}


else if ($_GET[submit] == "customer") {

	#customer query
	$print_customer = "SELECT * FROM {$tb_prefix}customers WHERE c_id = $def_customerField";
	$result_print_customer = mysql_query($print_customer, $conn) or die(mysql_error());

	
	while ($Array_customer = mysql_fetch_array($result_print_customer)) {
                $c_idField = $Array_customer['c_id'];
                $c_nameField = $Array_customer['c_name'];
	}

	#customer
	$sql_customer = "SELECT * FROM {$tb_prefix}customers where c_enabled != 0 ORDER BY c_name";
	$result_customer = mysql_query($sql_customer, $conn) or die(mysql_error());


	#customer selector

	if (mysql_num_rows($result_customer) == 0) {
	        //no records
	        $display_block_customer = "<p><em>$mc_no_invoices</em></p>";

	} else {
		$default = "def_customer";
	        //has records, so display them
	        $display_block_customer = "
	        <select name=\"default_customer\">
                <option selected value=\"$def_customerField\" style=\"font-weight: bold\">$c_nameField</option>
                <option value='0'> </option>";

        	while ($recs_customer = mysql_fetch_array($result_customer)) {
	                $id_customer = $recs_customer['c_id'];
	                $display_name_customer = $recs_customer['c_name'];

	                $display_block_customer .= "<option value=\"$id_customer\">
                        $display_name_customer</option>";
	        }
	}

	$display_block ="
	<tr>
		<td><br></td>
	</tr>
	<tr>
		<td class=\"details_screen\">$mc_table_customer_name</th><td>$display_block_customer</td>
	</tr>
	<tr>
		<td><br></td>
	</tr>
	";	
}



else if ($_GET[submit] == "tax") {

        #tax query
        $print_tax = "SELECT * FROM {$tb_prefix}tax WHERE tax_id = $def_taxField";
        $result_print_tax = mysql_query($print_tax, $conn) or die(mysql_error());


        while ($Array_tax = mysql_fetch_array($result_print_tax)) {
                $tax_idField = $Array_tax['tax_id'];
                $tax_descriptionField = $Array_tax['tax_description'];
        }


	#tax query
	$sql_tax = "SELECT * FROM {$tb_prefix}tax where tax_enabled != 0 ORDER BY tax_description";
	$result_tax = mysql_query($sql_tax, $conn) or die(mysql_error());


	#tax selector

	if (mysql_num_rows($result_tax) == 0) {
	        //no records
	        $display_block_tax = "<p><em>$msd_no_tax</em></p>";

	} else {
		$default = "def_tax";
	        //has records, so display them
	        $display_block_tax = "
	        <select name=\"default_tax\">

                <option selected value=\"$def_taxField\" style=\"font-weight: bold\">$tax_descriptionField</option>
                <option value='0'> </option>";

	        while ($recs_tax = mysql_fetch_array($result_tax)) {
	                $id_tax = $recs_tax['tax_id'];
	                $display_name_tax = $recs_tax['tax_description'];
	
	                $display_block_tax .= "<option value=\"$id_tax\">
                        $display_name_tax</option>";
	        }
	}

	$display_block = "
	<tr>
		<td><br></td>
	</tr>
	<tr>
	<td class=\"details_screen\">$msd_tax</td><td>$display_block_tax</td>
	</tr>
	<tr>
		<td><br></td>
	</tr>
	";

}

else if ($_GET[submit] == "inv_preference") {

        #invoice preference query
        $print_inv_preference = "SELECT * FROM {$tb_prefix}preferences WHERE pref_id = $def_inv_preferenceField";
        $result_inv_preference = mysql_query($print_inv_preference, $conn) or die(mysql_error());


        while ($Array_inv_preference = mysql_fetch_array($result_inv_preference)) {
                $pref_descriptionField = $Array_inv_preference['pref_description'];
        }



	#invoice preference query
	$sql_preferences = "SELECT * FROM {$tb_prefix}preferences where pref_enabled != 0 ORDER BY pref_description";
	$result_preferences = mysql_query($sql_preferences, $conn) or die(mysql_error());


	#invoice_preference selector

	if (mysql_num_rows($result_preferences) == 0) {
        	//no records
	        $display_block_preferences = "<p><em>$mip_no_invoices</em></p>";

	} else {
		$default = "def_invoice_preference";
        	//has records, so display them
	        $display_block_preferences = "
	        <select name=\"default_inv_preference\">

                <option selected value=\"$def_inv_preferenceField\" style=\"font-weight: bold\">$pref_descriptionField</option>
                <option value='0'> </option>";

        	while ($recs_preferences = mysql_fetch_array($result_preferences)) {
                	$id_preferences = $recs_preferences['pref_id'];
	                $display_name_preferences = $recs_preferences['pref_description'];

	                $display_block_preferences .= "<option value=\"$id_preferences\">
	                        $display_name_preferences</option>";
        	}
	}	

	$display_block = "
	<tr>
		<td><br></td>
	</tr>
	<tr>
		<td class=\"details_screen\">$msd_invoice_preference</td><td>$display_block_preferences</td>
	</tr>
	<tr>
		<td><br></td>
	</tr>
	";

}

else if ($_GET[submit] == "def_payment_type") {

        #payment type query
        $print_payment_type = "SELECT * FROM {$tb_prefix}payment_types WHERE pt_id = $def_payment_typeField";
        $result_print_payment_type = mysql_query($print_payment_type, $conn) or die(mysql_error());


        while ($Array_payment_type = mysql_fetch_array($result_print_payment_type)) {
                $pt_idField = $Array_payment_type['pt_id'];
                $pt_descriptionField = $Array_payment_type['pt_description'];
        }


        #payment type query
        $sql_payment_type = "SELECT * FROM {$tb_prefix}payment_types where pt_enabled != 0 ORDER BY pt_description";
        $result_payment_type = mysql_query($sql_payment_type, $conn) or die(mysql_error());


        #payment type selector

        if (mysql_num_rows($result_payment_type) == 0) {
                //no records
                $display_block_payment_type = "<p><em>$msd_payment_type</em></p>";

        } else {
                $default = "def_payment_type";
                //has records, so display them
                $display_block_payment_type = "
                <select name=\"def_payment_type\">

                <option selected value=\"$def_payment_typeField\" style=\"font-weight: bold\">$pt_descriptionField</option>";

                while ($recs_payment_type = mysql_fetch_array($result_payment_type)) {
                        $id_payment_type = $recs_payment_type['pt_id'];
                        $display_name_payment_type = $recs_payment_type['pt_description'];

                        $display_block_payment_type .= "<option value=\"$id_payment_type\">
                        $display_name_payment_type</option>";
                }
        }

        $display_block = "
        <tr>
                <td><br></td>
        </tr>
        <tr>
        <td class=\"details_screen\">$msd_payment_type</td><td>$display_block_payment_type</td>
        </tr>
        <tr>
                <td><br></td>
        </tr>
        ";

}


else {
	$display_block = "$msd_no_defaults";
}



include('./config/config.php');

echo <<<EOD

<form name="frmpost" action="index.php?module=system_defaults&view=save&sys_default=$default" method="post" onsubmit="return frmpost_Validator(this)">

		<b>$msd_heading</b>
 <hr></hr>

<table align=center>

$display_block

</tr>
</tr>
</table>
<!-- </div> -->

	<input type=submit name="submit" value="$LANG_save_defaults">
	<input type=hidden name="op" value="update_system_defaults">

</FORM>
EOD;
?>
<!-- ./src/include/design/footer.inc.php gets called here by controller srcipt -->
