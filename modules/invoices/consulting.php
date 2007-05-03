<?php
//stop the direct browsing to this file - let index.php handle which files get displayed
checkLogin();


jsBegin();
jsFormValidationBegin("frmpost");
jsTextValidation("sel_id","Biller Name",1,100);
jsTextValidation("select_customer","Customer Name",1,100);
jsValidateifNumZero("i_quantity0","Quantity");
jsValidateifNum("i_quantity0","Quantity");
jsValidateRequired("select_products0","Product");
jsTextValidation("select_tax","Tax Rate",1,100);
jsPreferenceValidation("select_preferences","Invoice Preference",1,100);
jsFormValidationEnd();
jsEnd();



#biller query
$sql = "SELECT * FROM {$tb_prefix}biller where enabled != 0 ORDER BY name";
$result = mysql_query($sql, $conn) or die(mysql_error());

#customer
$sql_customer = "SELECT * FROM {$tb_prefix}customers where c_enabled != 0 ORDER BY c_name";
$result_customer = mysql_query($sql_customer, $conn) or die(mysql_error());

#productr query
$sql_products = "SELECT * FROM {$tb_prefix}products where prod_enabled != 0 ORDER BY prod_description";
$result_products = mysql_query($sql_products, $conn) or die(mysql_error());


#tax query
$sql_tax = "SELECT * FROM {$tb_prefix}tax where tax_enabled != 0 ORDER BY tax_description";
$result_tax = mysql_query($sql_tax, $conn) or die(mysql_error());

#invoice preference query
$sql_preferences = "SELECT * FROM {$tb_prefix}preferences where pref_enabled != 0 ORDER BY pref_description";
$result_preferences = mysql_query($sql_preferences, $conn) or die(mysql_error());


#defaults query and DEFAULT NUMBER OF LINE ITEMS
//$sql_defaults = "SELECT * FROM {$tb_prefix}defaults";
//$result_defaults = mysql_query($sql_defaults, $conn) or die(mysql_error());

#defaults Array
//$def = mysql_fetch_array($result_defaults);
$defaults = getSystemDefaults();

#Get the names of the defaults from their id -start
#default biller name query
$sql_biller_default = "SELECT name FROM {$tb_prefix}biller where id = $defaults[biller] and enabled != 0";
$result_biller_default = mysql_query($sql_biller_default , $conn) or die(mysql_error());

$biller= mysql_fetch_array($result_biller_default);

#default customer name query
$print_customer = "SELECT * FROM {$tb_prefix}customers WHERE c_id = $defaults[customer] and c_enabled != 0";
$result_print_customer = mysql_query($print_customer, $conn) or die(mysql_error());

$customer = mysql_fetch_array($result_print_customer);


#default tax description query
$print_tax = "SELECT * FROM {$tb_prefix}tax WHERE tax_id = $defaults[tax] and tax_enabled != 0";
$result_print_tax = mysql_query($print_tax, $conn) or die(mysql_error());

$tax = mysql_fetch_array($result_print_tax);

#default invoice preference description query
$print_inv_preference = "SELECT * FROM {$tb_prefix}preferences WHERE pref_id = $defaults[invoice] and pref_enabled != 0";
$result_inv_preference = mysql_query($print_inv_preference, $conn) or die(mysql_error());

$pref =  mysql_fetch_array($result_inv_preference);


#Get the names of the defaults from their id -end
#default biller name query


#biller selector

if (mysql_num_rows($result) == 0) {
        //no records
        $display_block = "<p><em>{$LANG['no_billers']}</em></p>";

} else {
        //has records, so display them
        $display_block = <<<EOD
        <select name="sel_id">
        <option selected value="$defaults[biller]" style="font-weight: bold">$biller[name]</option>
	<option value=""></option>
EOD;
		while ($recs = mysql_fetch_array($result)) {
			$display_block .= "<option value=".$recs['id'].">".$recs['name']."</option>";
		}
}



#customer selector

if (mysql_num_rows($result_customer) == 0) {
        //no records
        $display_block_customer = "<p><em>{$LANG['no_customers']}</em></p>";

} else {
        //has records, so display them
        $display_block_customer = <<<EOD
        <select name="select_customer">
        <option selected value="$defaults[customer]" style="font-weight: bold">$customer[c_name]</option>
        <option value=""></option>
EOD;

	while ($recs_customer = mysql_fetch_array($result_customer)) {
		
                $display_block_customer .= <<<EOD
                <option value=$recs_customer[c_id]">
                        $recs_customer[c_name]</option>
EOD;
        }
}

function line_items($line) {
        #productr query
        include('./config/config.php');
        $conn = mysql_connect("$db_host","$db_user","$db_password");
        mysql_select_db("$db_name",$conn);

        $sql_products = "SELECT * FROM {$tb_prefix}products where prod_enabled != 0 ORDER BY prod_description";
        $result_products = mysql_query($sql_products, $conn) or die(mysql_error());
        
        $string = "";

	if (mysql_num_rows($result_products) == 0) {
	        //no records
	        $display_block_products = "<p><em>{$LANG['no_products']}</em></p>";

	} else {
	        //has records, so display them
	        $display_block_products = <<<EOD
	        <select name="select_products$line">
	        <option value=""></option>
EOD;

        	while ($recs_products = mysql_fetch_array($result_products)) {

	                $display_block_products .= <<<EOD
	                <option value="$recs_products[prod_id]">
        	                $recs_products[prod_description]</option>
EOD;
	        }
	        }
	        
                $string .= <<< EOD
                <tr>
                <td>
			<input type=text name='i_quantity$line' size=5></td><td input type=text name='i_description$line' size=50>$display_block_products 
		</td>

		</tr>
<tr class="text$line hide">
        <td colspan=2 ><textarea input type=text name='line_item_description$line' rows=3 cols=80 WRAP=nowrap></textarea></td>
</tr>
EOD;

return $string;
}


#tax selector

if (mysql_num_rows($result_tax) == 0) {
        //no records
        $display_block_tax = "<p><em>{$LANG['no_tax_rates']}</em></p>";

} else {
        //has records, so display them
        $display_block_tax = <<<EOD
        <select name="select_tax">
        
	<option selected value="$defaults[tax]" style="font-weight: bold">$tax[tax_description]</option>
        
	<option value=""></option>
EOD;

        while ($recs_tax = mysql_fetch_array($result_tax)) {
                $display_block_tax .= <<<EOD
                <option value="$recs_tax[tax_id]">
                        $recs_tax[tax_description]</option>
EOD;
        }
}

#invoice_preference selector

if (mysql_num_rows($result_preferences) == 0) {
        //no records
        $display_block_preferences = "<p><em>{$LANG['no_preferences']}</em></p>";

} else {
        //has records, so display them
        $display_block_preferences = <<<EOD
        <select name="select_preferences">
        
        <option selected value="$defaults[invoice]" style="font-weight: bold">$pref[pref_description]</option>
	
	<option value=""></option>
EOD;

        while ($recs_preferences = mysql_fetch_array($result_preferences)) {
                $display_block_preferences .= <<<EOD
                <option value=" $recs_preferences[pref_id]">
                        $recs_preferences[pref_description]</option>
EOD;
        }
}


#get custom field labels

$show_custom_field_1 = show_custom_field(invoice_cf1,'',write,'',details_screen,'','','');
$show_custom_field_2 = show_custom_field(invoice_cf2,'',write,'',details_screen,'','','');
$show_custom_field_3 = show_custom_field(invoice_cf3,'',write,'',details_screen,'','','');
$show_custom_field_4 = show_custom_field(invoice_cf4,'',write,'',details_screen,'','','');

include('./config/config.php');


/* check the def number of line items and do the print and entry field for that number of items */
   /*Preparation work fro being able to dynamically add line items during an itemised invoice  */
	/*get the number of line items from the GET or if not set from the default in the database */
	if (!empty( $_GET['get_num_line_items'] )) {
		$dynamic_line_items = $_GET['get_num_line_items'];
		} 
	else {
		$dynamic_line_items = $defefaults['items'] ;
	}	

	$num = 0;
	
	$lineitems = "";

        while ($num < $dynamic_line_items ) {
                $lineitems .= line_items($num);
                $lineitems .= "</td></tr>";
                $num++;

        }
        
     

            
	$today = date("Y-m-d");
    
	$temp = file_get_contents('./modules/invoices/consulting.tpl');

	$temp = addslashes($temp);	$content = "";
	
	eval ('$content = "'.$temp.'";');
	
	echo $content;
	
	

?>
