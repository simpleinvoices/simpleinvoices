<?php
include_once('./include/include_main.php');
include_once("./include/validation.php");

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


$conn = mysql_connect("$db_host","$db_user","$db_password");
mysql_select_db("$db_name",$conn);

#biller query
$sql = "SELECT * FROM si_biller where b_enabled != 0 ORDER BY b_name";
$result = mysql_query($sql, $conn) or die(mysql_error());

#customer
$sql_customer = "SELECT * FROM si_customers where c_enabled != 0 ORDER BY c_name";
$result_customer = mysql_query($sql_customer, $conn) or die(mysql_error());

#productr query
$sql_products = "SELECT * FROM si_products where prod_enabled != 0 ORDER BY prod_description";
$result_products = mysql_query($sql_products, $conn) or die(mysql_error());


#tax query
$sql_tax = "SELECT * FROM si_tax where tax_enabled != 0 ORDER BY tax_description";
$result_tax = mysql_query($sql_tax, $conn) or die(mysql_error());

#invoice preference query
$sql_preferences = "SELECT * FROM si_preferences where pref_enabled != 0 ORDER BY pref_description";
$result_preferences = mysql_query($sql_preferences, $conn) or die(mysql_error());


#defaults query and DEFAULT NUMBER OF LINE ITEMS
$sql_defaults = "SELECT * FROM si_defaults";
$result_defaults = mysql_query($sql_defaults, $conn) or die(mysql_error());

$def = mysql_fetch_array($result_defaults);

#Get the names of the defaults from their id -start
#default biller name query
$sql_biller_default = "SELECT b_name FROM si_biller where b_id = $def[def_biller] and b_enabled != 0";
$result_biller_default = mysql_query($sql_biller_default , $conn) or die(mysql_error());

while ($Array = mysql_fetch_array($result_biller_default) ) {
                $sql_biller_defaultField = $Array['b_name'];
}

#default customer name query
$print_customer = "SELECT * FROM si_customers WHERE c_id = $def[def_customer] and c_enabled != 0";
$result_print_customer = mysql_query($print_customer, $conn) or die(mysql_error());

while ($Array_customer = mysql_fetch_array($result_print_customer)) {
       $c_nameField = $Array_customer['c_name'];
}

#default tax description query
$print_tax = "SELECT * FROM si_tax WHERE tax_id = $def[def_tax] and tax_enabled != 0";
$result_print_tax = mysql_query($print_tax, $conn) or die(mysql_error());

while ($Array_tax = mysql_fetch_array($result_print_tax)) {
       $tax_descriptionField = $Array_tax['tax_description'];
}

#default invoice preference description query
$print_inv_preference = "SELECT * FROM si_preferences WHERE pref_id = $def[def_inv_preference] and pref_enabled != 0";
$result_inv_preference = mysql_query($print_inv_preference, $conn) or die(mysql_error());

while ($Array_inv_preference = mysql_fetch_array($result_inv_preference)) {
       $pref_descriptionField = $Array_inv_preference['pref_description'];
}

#Get the names of the defaults from their id -end
#default biller name query



#biller selector

if (mysql_num_rows($result) == 0) {
        //no records
        $display_block = "<p><em>$mb_no_invoices</em></p>";

} else {
        //has records, so display them
        $display_block = <<<EOD
        <select name="sel_id">
        <option selected value="$def[def_biller]" style="font-weight: bold">$sql_biller_defaultField</option>
        <option value=""></option>
EOD;

        while ($recs = mysql_fetch_array($result)) {
                $id = $recs['b_id'];
                $display_name = $recs['b_name'];

                $display_block .= <<<EOD
                <option value="$id">
                        $display_name</option>
EOD;
        }
}

#customer selector

if (mysql_num_rows($result_customer) == 0) {
        //no records
        $display_block_customer = "<p><em>$mc_no_invoices</em></p>";

} else {
        //has records, so display them
        $display_block_customer = <<<EOD
        <select name="select_customer">
        <option selected value="$der[def_customer]" style="font-weight: bold">$c_nameField</option>
        <option value=""></option>
EOD;

        while ($recs_customer = mysql_fetch_array($result_customer)) {
                $id_customer = $recs_customer['c_id'];
                $display_name_customer = $recs_customer['c_name'];

                $display_block_customer .= <<<EOD
                <option value=$id_customer">
                        $display_name_customer</option>
EOD;
        }
}

function line_items($line) {
        #productr query
        include('./config/config.php');
        $conn = mysql_connect("$db_host","$db_user","$db_password");
        mysql_select_db("$db_name",$conn);

        $sql_products = "SELECT * FROM si_products where prod_enabled != 0 ORDER BY prod_description";
        $result_products = mysql_query($sql_products, $conn) or die(mysql_error());

	if (mysql_num_rows($result_products) == 0) {
	        //no records
	        $display_block_products = "<p><em>$mp_no_invoices</em></p>";

	} else {
	        //has records, so display them
	        $display_block_products = <<<EOD
	        <select name="select_products$line">
	        <option value=""></option>
EOD;

        	while ($recs_products = mysql_fetch_array($result_products)) {
    	               $id_products = $recs_products['prod_id'];
       		       $display_name_products = $recs_products['prod_description'];

	                $display_block_products .= <<<EOD
	                <option value="$id_products">
        	                $display_name_products</option>
EOD;
	        }
	        }
                echo <<< EOD
                <tr>
                <td>
			<input type=text name='i_quantity$line' size=5></td><td input type=text name='i_description$line' size=50>$display_block_products 
		</td>
		<!--
		<td>
			<a href='#' class="show-text$line" onClick="$('.text$line').show();$('.show-text$line').hide();">$LANG_show_details Show note</a><a href='#' class="text hide" onClick="$('.text$line').hide();$('.show-text$line').show();">$LANG_hide_details Hide note</a>
		</td>
		-->
		</tr>
<tr class="text$line hide">
        <td colspan=2 ><textarea input type=text name='line_item_description$line' rows=3 cols=80 WRAP=nowrap></textarea></td>
</tr>
EOD;
}


#tax selector

if (mysql_num_rows($result_tax) == 0) {
        //no records
        $display_block_tax = "<p><em>$mtr_no_invoices</em></p>";

} else {
        //has records, so display them
        $display_block_tax = <<<EOD
        <select name="select_tax">
        
	<option selected value="$def[def_tax]" style="font-weight: bold">$tax_descriptionField</option>
        
	<option value=""></option>
EOD;

        while ($recs_tax = mysql_fetch_array($result_tax)) {
                $id_tax = $recs_tax['tax_id'];
                $display_name_tax = $recs_tax['tax_description'];

                $display_block_tax .= <<<EOD
                <option value="$id_tax">
                        $display_name_tax</option>
EOD;
        }
}

#invoice_preference selector

if (mysql_num_rows($result_preferences) == 0) {
        //no records
        $display_block_preferences = "<p><em>$mip_no_invoices</em></p>";

} else {
        //has records, so display them
        $display_block_preferences = <<<EOD
        <select name="select_preferences">
        
        <option selected value="$def[def_inv_preference]" style="font-weight: bold">$pref_descriptionField</option>
	
	<option value=""></option>
EOD;

        while ($recs_preferences = mysql_fetch_array($result_preferences)) {
                $id_preferences = $recs_preferences['pref_id'];
                $display_name_preferences = $recs_preferences['pref_description'];

                $display_block_preferences .= <<<EOD
                <option value="$id_preferences">
                        $display_name_preferences</option>
EOD;
        }
}


#get custom field labels

$show_custom_field_1 = show_custom_field(invoice_cf1,'',write,'',details_screen,'','','');
$show_custom_field_2 = show_custom_field(invoice_cf2,'',write,'',details_screen,'','','');
$show_custom_field_3 = show_custom_field(invoice_cf3,'',write,'',details_screen,'','','');
$show_custom_field_4 = show_custom_field(invoice_cf4,'',write,'',details_screen,'','','');


?>



<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<script language="javascript" type="text/javascript" src="include/tiny_mce/tiny_mce_src.js"></script>
<script language="javascript" type="text/javascript" src="include/tiny-mce.conf.js"></script>

<link rel="stylesheet" type="text/css" href="include/jquery.datePicker.css" title="default" media="screen" />
<link rel="stylesheet" href="./src/include/css/ibox.css" type="text/css"  media="screen"/>

<script type="text/javascript" src="include/jquery.js"></script>
<script type="text/javascript" src="include/jquery.dom_creator.js"></script>
<script type="text/javascript" src="include/jquery.datePicker.js"></script>
<script type="text/javascript" src="include/jquery.datePicker.conf.js"></script>
<script type="text/javascript" src="./src/include/js/ibox.js"></script>

<!--
    <script type="text/javascript">
	$(document).ready(function() {
	 // hides the customer and biller details as soon as the DOM is ready (a little sooner that page load)
	  $('.hide').hide();
  	});
    </script>
-->

</head>
	<title><?php echo $title; echo " :: "; echo $LANG_inv; echo $LANG_inv_consulting; ?></title>
<?php include('./config/config.php'); ?>
<BODY>

<FORM name="frmpost" ACTION="index.php?module=invoices&view=save" METHOD=POST onsubmit="return frmpost_Validator(this)">

<b><?php echo $LANG_inv; echo $LANG_inv_consulting; ?></b>
<hr></hr>

<table align=center>
<tr>
	<td class="details_screen">
		<?php echo $mb_table_biller_name; ?>
	</td>
	<td input type=text name="biller_block" size=25>
		<?php echo $display_block; ?>
	</td>
</tr>
<tr>
	<td class="details_screen">
		<?php echo $mc_table_customer_name; ?>
	</td>
	<td input type=text name="customer_block" size=25 >
		<?php echo $display_block_customer; ?>
	</td>
</tr>
<tr>
        <td class="details_screen"><?php echo $LANG_date_formatted; ?></td>
        <td>
                        <input type="text" class="date-picker" name="select_date" id="date1" value='<?php echo $today = date("Y-m-d"); ?>'></input>
        </td>
</tr>


<tr>
	<td class="details_screen">
		<?php echo $LANG_quantity;?>
	</td>
	<td class="details_screen">
		<?php echo $LANG_description;?>
	</td>
</tr>
<?php
/* check the def number of line items and do the print and entry field for that number of items */
   /*Preparation work fro being able to dynamically add line items during an itemised invoice  */
	/*get the number of line items from the GET or if not set from the default in the database */
	if (!empty( $_GET['get_num_line_items'] )) {
		$dynamic_line_items = $_GET['get_num_line_items'];
		} 
	else {
		$dynamic_line_items = $def['def_number_line_items'] ;
	}	

	$num = 0;

        while ($num < $dynamic_line_items ) :
                echo line_items($num);
                echo "<a/td></tr>";
                        $num++;

                        endwhile;
?>


<?php 
	echo $show_custom_field_1;
	echo $show_custom_field_2;
	echo $show_custom_field_3;
	echo $show_custom_field_4;
?>
<tr>
        <td colspan=2 class="details_screen"><?php echo $LANG_notes;?></td>
</tr>

<tr>
        <td colspan=2 ><textarea input type=text name="invoice_consulting_note" rows=5 cols=80 WRAP=nowrap></textarea></td>
</tr>

<tr>
	<td class="details_screen"><?php echo $LANG_tax;?></td><td input type=text name="inv_it_tax" size=15> <?php echo $display_block_tax; ?></td>
</tr>

<tr>
	<td class="details_screen"><?php echo $LANG_inv_pref;?></td><td input type=text name="inv_preferences"><?php echo $display_block_preferences; ?></td>
</tr>
<tr>
	<td align=left colspan=2> 
		<a href="./documentation/info_pages/invoice_custom_fields.html" rel="ibox&height=400"><?php echo $LANG_want_more_fields; ?><img src="./images/common/help-small.png"></img></a>

	</td>
	
</tr>
<!--Add more line items while in an itemeised invoice - Get style - has problems- wipes the current values of the existing rows - not good
<tr>
<td>
<a href="?get_num_line_items=10">Add 5 more line items<a>
</tr>
-->
</table>
<!-- </div> -->
<hr></hr>
		<input type=hidden name="max_items" value="<?php echo $num; ?>">
		<input type=submit name="submit" value="<?php echo $LANG_save;echo " "; echo $LANG_inv;?>">
		<input type=hidden name="invoice_style" value="insert_invoice_consulting">

</FORM>
<!-- ./src/include/design/footer.inc.php gets called here by controller srcipt -->
