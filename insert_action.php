<?php
include('./include/include_main.php');
$conn = mysql_connect( $db_host, $db_user, $db_password);
mysql_select_db( $db_name, $conn);

# Deal with op and add some basic sanity checking

$op = !empty( $_POST['op'] ) ? addslashes( $_POST['op'] ) : NULL;

#insert customer

if ($op === "insert_customer") {


/* old code
$sql = "INSERT into si_customers values ('','$_POST[c_attention]','$_POST[c_name]','$_POST[c_street_address]','$_POST[c_city]','$_POST[c_state]','$_POST[c_zip_code]','$_POST[c_country]','$_POST[c_phone]','$_POST[c_fax]','$_POST[c_email]')";
*/

extract( $_POST );

$sql ='INSERT INTO 
		si_customers 
	VALUES 
		("","' . $c_attention . '", "' . $c_name . '", "' . $c_street_address . '", "' . $c_city . '", "' . $c_state . '", "' . $c_zip_code . '", "' . $c_country . '", "' . $c_phone . '", "' . $c_fax . '", "' . $c_email . '", "' . $c_enabled . '")';


if (mysql_query($sql, $conn)) {
        $display_block =  "Customer successfully added,<br> you will be redirected back to the Manager Customers page";
} else {
        $display_block =  "Something went wrong, please try adding the customer again";
} 
	header( 'refresh: 2; url=manage_customers.php' );

}

#edit customer
else if ( $op === 'edit_customer' ) {

        if ($_POST['action'] === "Save Customer") {
                $sql = "
			UPDATE 
				si_customers 
			SET 
				c_name = '$_POST[c_name]', 
				c_attention = '$_POST[c_attention]',
				c_street_address = '$_POST[c_street_address]',
				c_city = '$_POST[c_city]',
				c_state = '$_POST[c_state]',
				c_zip_code = '$_POST[c_zip_code]',
				c_country = '$_POST[c_country]', 
				c_phone = '$_POST[c_phone]', 
				c_fax = '$_POST[c_fax]', 
				c_email = '$_POST[c_email]', 
				c_enabled = '$_POST[c_enabled]'  
			WHERE  
				c_id = " . $_GET['submit'];

                if (mysql_query($sql, $conn)) {
                        $display_block =  "Customer successfully edited, <br> you will be redirected back to the Manage Customers";
                } else {
                        $display_block =  "Something went wrong, please try editing the customer again";
                }

                header( 'refresh: 2; url=manage_customers.php' );

                }

        else if ($_POST['action'] === "Cancel") {

                header( 'refresh: 0; url=manage_customers.php' );
        }


}


#insert - process payment
#op=pay_selected_invoice means the user came from the print_view or manage_invoces 
#op=pay_invoice means the user came from the process_paymen page 

else if ( $op === 'pay_invoice' OR $op === 'pay_selected_invoice' ) {

	$sql = "INSERT into 
			si_account_payments 
		VALUES 
			(	
				'',
				'$_POST[ac_inv_id]',
				'$_POST[ac_amount]',
				'$_POST[ac_notes]',
				'$_POST[ac_date]',
				'$_POST[ac_payment_type]'
			)";

	if (mysql_query($sql, $conn)) {
		if ( $op === 'pay_selected_invoice' ) {
		        $display_block =  "Payment successfully processed, <br> you will be redirected to the Manage Invoices page";
		}
		if ( $op === 'pay_invoice' ) {
		        $display_block =  "Payment successfully processed, <br> you will be redirected to the Process Payments page";
		}
		

	} else {
	        $display_block =  "Something went wrong, please try processing the payment again<br>$sql";
	}

	if ( $op === 'pay_selected_invoice' ) {
		header( 'refresh: 2; url=manage_invoices.php' );
	}
	else if ( $op === 'pay_invoice' ) {
		header( 'refresh: 2; url=process_payment.php?op=pay_invoice' );
	}

}

#insert biller
 	
else if ( $op === 'insert_biller') {
	
 	$sql = "INSERT into 
			si_biller
		VALUES
			(
				'',
				'$_POST[b_name]',
				'$_POST[b_street_address]',
				'$_POST[b_city]',
				'$_POST[b_state]',
				'$_POST[b_zip_code]',
				'$_POST[b_country]',
				'$_POST[b_phone]',
				'$_POST[b_mobile_phone]',
				'$_POST[b_fax]',
				'$_POST[b_email]',
				'$_POST[b_co_logo]',
				'$_POST[b_co_footer]',
				'$_POST[b_enabled]'
			 )";
 	
 	if (mysql_query($sql, $conn)) {
 	        $display_block =  "Biller successfully added, <br> you will be redirected to the Manage Billers page";
 	} else {
 	        $display_block =  "Something went wrong, please try adding the biller again<br>$sql";
 	}
 	
 	header( 'refresh: 2; url=manage_billers.php' );
 	
}

#edit biller

else if (  $op === 'edit_biller' ) {

        if ($_POST[action] == "Save Biller") {
                $sql = "UPDATE 
				si_biller 
			SET 
				b_name = '$_POST[b_name]', 
				b_street_address = '$_POST[b_street_address]', 
				b_city = '$_POST[b_city]',b_state = '$_POST[b_state]',
				b_zip_code = '$_POST[b_zip_code]',
				b_country = '$_POST[b_country]', 
				b_phone = '$_POST[b_phone]', 
				b_mobile_phone = '$_POST[b_mobile_phone]', 
				b_fax = '$_POST[b_fax]', 
				b_email = '$_POST[b_email]', 
				b_co_logo = '$_POST[b_co_logo]', 
				b_co_footer = '$_POST[b_co_footer]', 
				b_enabled = '$_POST[b_enabled]'   
			WHERE  
				b_id = '$_GET[submit]'";
                if (mysql_query($sql, $conn)) {
                        $display_block =  "Biller successfully edited, <br> you will be redirected back to the Manage Billers";
                } else {
                        $display_block =  "Something went wrong, please try editing the product again";
                }

                header( 'refresh: 2; url=manage_billers.php' );

                }

	else if ($_POST[action] == "Cancel") {

                header( 'refresh: 0; url=manage_billers.php' );
        }


}



#insert product

else if (  $op === 'insert_product' ) {

$sql = "INSERT into 
		si_products 
	VALUES 
		(	
			'',
			'$_POST[prod_description]',
			'$_POST[prod_unit_price]',
			'$_POST[prod_enabled]'
		)"; 

if (mysql_query($sql, $conn)) {
        $display_block =  "Product successfully added, <br> you will be redirected to the Manage Products page";
} else {
        $display_block =  "Something went wrong, please try adding the biller again";
}

header( 'refresh: 2; url=manage_products.php' );

}



#edit product

else if (  $op === 'edit_product' ) {

$conn = mysql_connect("$db_host","$db_user","$db_password");
mysql_select_db("$db_name",$conn);

	if ($_POST[action] == "Save Product") {
		$sql = "UPDATE 
				si_products 
			SET 
				prod_description = '$_POST[prod_description]', 
				prod_enabled = '$_POST[prod_enabled]', 
				prod_unit_price = '$_POST[prod_unit_price]'
			WHERE  
				prod_id = '$_GET[submit]'";

		if (mysql_query($sql, $conn)) {
        		$display_block =  "Product successfully edited, <br> you will be redirected back to the Manage Products";
		} else {
        		$display_block =  "Something went wrong, please try editing the product again";
		}

		header( 'refresh: 2; url=manage_products.php' );

		}

	else if ($_POST[action] == "Cancel") {
	
		header( 'refresh: 0; url=manage_products.php' );
	}


}


#insert tax rate

else if (  $op === 'insert_tax_rate' ) {

$conn = mysql_connect("$db_host","$db_user","$db_password");
mysql_select_db("$db_name",$conn);

/*Raymond - what about the '', bit doesnt seem to do an insert in me environment when i exclude it
$sql = "INSERT INTO si_tax VALUES ('$_POST[tax_description]','$_POST[tax_percentage]')";
*/

$sql = "INSERT into 
		si_tax 
	VALUES 
		(	
			'',
			'$_POST[tax_description]',
			'$_POST[tax_percentage]',	
			'$_POST[tax_enabled]'
		)";

if (mysql_query($sql, $conn)) {
        $display_block =  "Tax rate successfully added, <br> you will be redirected to the Manage Tax Rates page";
} else {
        $display_block =  'Something went wrong, please try adding the tax rate again';
}

header( 'refresh: 2; url=manage_tax_rates.php' );

}



#edit tax rate

else if (  $op === 'edit_tax_rate' ) {

$conn = mysql_connect("$db_host","$db_user","$db_password");
mysql_select_db("$db_name",$conn);

        if ( $_POST['action'] === 'Save Tax Rate' ) {
                $sql = "UPDATE 
				si_tax 
			SET 
				tax_description = '$_POST[tax_description]', 
				tax_percentage = '$_POST[tax_percentage]',
				tax_enabled = '$_POST[tax_enabled]'   
			WHERE  
				tax_id = " . $_GET['submit'];

                if (mysql_query($sql, $conn)) {
                        $display_block =  "Tax Rate successfully edited, <br> you will be redirected back to the Manage Tax Rates";
                } else {
                        $display_block =  'Something went wrong, please try editing the tax rate again';
                }

                header( 'refresh: 2; url=manage_tax_rates.php' );

                }

        else if ($_POST[action] == "Cancel") {

                header( 'refresh: 0; url=manage_tax_rates.php' );
        }
}

#insert payment type

else if (  $op === 'insert_payment_type' ) {

$conn = mysql_connect("$db_host","$db_user","$db_password");
mysql_select_db("$db_name",$conn);

/*Raymond - what about the '', bit doesnt seem to do an insert in me environment when i exclude it
$sql = "INSERT INTO si_tax VALUES ('$_POST[tax_description]','$_POST[tax_percentage]')";
*/

$sql = "INSERT into 
		si_payment_types 
	VALUES 
		(	
			'',
			'$_POST[pt_description]',
			'$_POST[pt_enabled]'
		)";

if (mysql_query($sql, $conn)) {
        $display_block =  "Payment Type successfully added, <br> you will be redirected to the Manage Payment Types page";
} else {
        $display_block =  'Something went wrong, please try adding the tax rate again';
}

header( 'refresh: 2; url=manage_payment_types.php' );

}


#edit payment type

else if (  $op === 'edit_payment_type' ) {

$conn = mysql_connect("$db_host","$db_user","$db_password");
mysql_select_db("$db_name",$conn);

        if ( $_POST['action'] === 'Save Payment Type' ) {
                $sql = "UPDATE 
				si_payment_types 
			SET 
				pt_description = '$_POST[pt_description]',
				pt_enabled = '$_POST[pt_enabled]'  
			WHERE  
				pt_id = " . $_GET['submit'];

                if (mysql_query($sql, $conn)) {
                        $display_block =  "Payment Type successfully edited, <br> you will be redirected back to the Manage Payment Types";
                } else {
                        $display_block =  'Something went wrong, please try editing the tax rate again';
                }

                header( 'refresh: 2; url=manage_payment_types.php' );

                }

        else if ($_POST[action] == "Cancel") {

                header( 'refresh: 0; url=manage_payment_types.php' );
        }
}


#insert invoice_preference
if (  $op === 'insert_preference' ) {

$sql = "INSERT into 
		si_preferences 
	VALUES 
		(
			'',
			'$_POST[p_description]',
			'$_POST[p_currency_sign]',
			'$_POST[p_inv_heading]',
			'$_POST[p_inv_wording]',
			'$_POST[p_inv_detail_heading]',
			'$_POST[p_inv_detail_line]',
			'$_POST[p_inv_payment_method]',
			'$_POST[p_inv_payment_line1_name]',
			'$_POST[p_inv_payment_line1_value]',
			'$_POST[p_inv_payment_line2_name]',
			'$_POST[p_inv_payment_line2_value]',
			'$_POST[pref_enabled]'
		 )";

if (mysql_query($sql, $conn)) {
$display_block =  "Invoice preference successfully added,<br> you will be redirected to Manage Preferences page";
} else {
        $display_block =  'Something went wrong, please try adding the invoice preference again';
}

header( 'refresh: 2; url=manage_preferences.php' );

}

#edit preference

else if (  $op === 'edit_preference' ) {

        if (  $_POST['action'] === 'Save Preference' ) {
                $sql = "UPDATE 
				si_preferences 
			SET 
				pref_description = '$_POST[pref_description]', 
				pref_currency_sign = '$_POST[pref_currency_sign]', 
				pref_inv_heading = '$_POST[pref_inv_heading]', 
				pref_inv_wording = '$_POST[pref_inv_wording]', 
				pref_inv_detail_heading = '$_POST[pref_inv_detail_heading]', 
				pref_inv_detail_line = '$_POST[pref_inv_detail_line]', 
				pref_inv_payment_method = '$_POST[pref_inv_payment_method]', 
				pref_inv_payment_line1_name = '$_POST[pref_inv_payment_line1_name]', 
				pref_inv_payment_line1_value = '$_POST[pref_inv_payment_line1_value]', 
				pref_inv_payment_line2_name = '$_POST[pref_inv_payment_line2_name]',
				pref_inv_payment_line2_value = '$_POST[pref_inv_payment_line2_value]', 
				pref_enabled = '$_POST[pref_enabled]'   
			WHERE  
				pref_id = '$_GET[submit]'";

                if (mysql_query($sql, $conn)) {
                        $display_block =  "Invoice Preference successfully edited, <br> you will be redirected back to Manage Invoice Preferences";
                } else {
                        $display_block =  "Something went wrong, please try editing the invoice preference again";
                }

                header( 'refresh: 2; url=manage_preferences.php' );

                }

        else if ($_POST[action] == "Cancel") {

                header( 'refresh: 0; url=manage_preferences.php' );
        }
}




#update system defaults
if (  $op == 'update_system_defaults' ) {

#get defaultsr query
$print_defaults = "SELECT * FROM si_defaults WHERE def_id = 1";
$result_print_defaults = mysql_query($print_defaults, $conn) or die(mysql_error());


while ($Array = mysql_fetch_array($result_print_defaults) ) {
                $def_idField = $Array['def_id'];
                $def_customerField = $Array['def_customer'];
                $def_billerField = $Array['def_biller'];
                $def_taxField = $Array['def_tax'];
                $def_inv_preferenceField = $Array['def_inv_preference'];
                $def_number_line_itemsField = $Array['def_number_line_items'];
                $def_inv_templateField = $Array['def_inv_template'];
                $def_payment_typeField = $Array['def_payment_type'];
};

$default_biller = $_POST['default_biller'];
$default_customer = $_POST['default_customer'];
$default_tax = $_POST['default_tax'];
$default_inv_preference = $_POST['default_inv_preference'];
$default_num_line_items = $_POST['def_num_line_items'];
$def_inv_template = $_POST['def_inv_template'];
$default_payment_type = $_POST['def_payment_type'];


	#UPDATE the default number of line items

	if ($_GET[sys_default] == "line_items") {
	
		$sql = "REPLACE INTO 
				si_defaults 
			VALUES 
				(
					1,
					$def_billerField,
					$def_customerField,
					$def_taxField,	
					$def_inv_preferenceField,
					$default_num_line_items,	
					'$def_inv_templateField',
					$def_payment_typeField
				)";

		if (mysql_query($sql, $conn)) {
	        	$display_block =  "System default: Number of line items successfully update,<br> you will be redirected back to System Defaults page";
		} else {
		        $display_block =  "Something went wrong, please try setting the system defaults again<br><<br>$sql";
}

	header( 'refresh: 2; url=system_default_details.php' );
}



	#UPDATE the default invoice template field

	else if ($_GET[sys_default] == "def_inv_template") {

		$sql = "REPLACE INTO 
				si_defaults 
			VALUES 
				(
					1,
					$def_billerField,
					$def_customerField,
					$def_taxField,
					$def_inv_preferenceField,
					$def_number_line_itemsField,
					'$def_inv_template',
					$def_payment_typeField
				)";

		if (mysql_query($sql, $conn)) {
	        	$display_block =  "System default: Default invoice template successfully update,<br> you will be redirected back to System Defaults page";
		} else {
		        $display_block =  "Something went wrong, please try setting the default invoice template again<br>$_POST[def_inv_template]
<br>$sql<br><br>
<br>(1,$def_customerField,$def_billerField,$def_taxField,$def_inv_preferenceField,$def_number_line_itemsField,$def_inv_template)
 ";
}

	header( 'refresh: 2; url=system_default_details.php' );
}

        #UPDATE the default biller field

        else if ($_GET[sys_default] == "def_biller") {

                $sql = "REPLACE INTO 
				si_defaults 
			VALUES
				 (
					1,
					$default_biller,
					$def_customerField,
					$def_taxField,
					$def_inv_preferenceField,
					$def_number_line_itemsField,
					'$def_inv_templateField',
					'$def_payment_typeField'
					)";

                if (mysql_query($sql, $conn)) {
                        $display_block =  "System default: Default biller successfully updated,<br> you will be redirected back to System Defaults page";
                } else {
                        $display_block =  "Something went wrong, please try setting the default biller again<br>$_POST[def_inv_template]
<br>$sql<br><br>
<br>(1,$def_customerField,$def_billerField,$def_taxField,$def_inv_preferenceField,$def_number_line_itemsField,$def_inv_template)
 ";
}

        header( 'refresh: 2; url=system_default_details.php' );
}

        #UPDATE the default customer field

        else if ($_GET[sys_default] == "def_customer") {

                $sql = "REPLACE INTO 
				si_defaults 
			VALUES 
				(
					1,
					$def_billerField,
					$default_customer,
					$def_taxField,
					$def_inv_preferenceField,
					$def_number_line_itemsField,
					'$def_inv_templateField',
					$def_payment_typeField
				)";

                if (mysql_query($sql, $conn)) {
                        $display_block =  "System default: Default customer successfully updated,<br> you will be redirected back to System Defaults page";
                } else {
                        $display_block =  "Something went wrong, please try setting the default customer again<br>$_POST[def_inv_template]
<br>$sql<br><br>
<br>(1,$def_customerField,$def_billerField,$def_taxField,$def_inv_preferenceField,$def_number_line_itemsField,$def_inv_template)
 ";
}

        header( 'refresh: 2; url=system_default_details.php' );
}


        #UPDATE the default tax field

        else if ($_GET[sys_default] == "def_tax") {

                $sql = "REPLACE INTO 
				si_defaults 
			VALUES 
				(
					1,
					$def_billerField,
					$def_customerField,
					$default_tax,
					$def_inv_preferenceField,
					$def_number_line_itemsField,
					'$def_inv_templateField',
					$def_payment_typeField
				)";

                if (mysql_query($sql, $conn)) {
                        $display_block =  "System default: Default tax updated,<br> you will be redirected back to System Defaults page";
                } else {
                        $display_block =  "Something went wrong, please try setting the default tax again<br>$_POST[def_inv_template]
<br>$sql<br><br>
<br>(1,$def_customerField,$def_billerField,$def_taxField,$def_inv_preferenceField,$def_number_line_itemsField,$def_inv_template)
 ";
}

        header( 'refresh: 2; url=system_default_details.php' );
}


        #UPDATE the default invoice preference field

        else if ($_GET[sys_default] == "def_invoice_preference") {

                $sql = "REPLACE INTO 
				si_defaults 
			VALUES 
				(
					1,
					$def_billerField,
					$def_customerField,
					$def_taxField,
					$default_inv_preference,
					$def_number_line_itemsField,
					'$def_inv_templateField',
					'$def_payment_typeField'
				)";

                if (mysql_query($sql, $conn)) {
                        $display_block =  "System default: Default invoice preference updated,<br> you will be redirected back to System Defaults page";
                } else {
                        $display_block =  "Something went wrong, please try setting the default invoice preference again<br>$_POST[def_inv_template]
<br>$sql<br><br>
<br>(1,$def_customerField,$def_billerField,$def_taxField,$def_inv_preferenceField,$def_number_line_itemsField,$def_inv_template)
 ";
}

        header( 'refresh: 2; url=system_default_details.php' );
}

        #UPDATE the default payment_type field

        else if ($_GET[sys_default] == "def_payment_type") {

                $sql = "REPLACE INTO 
				si_defaults
			VALUES 
				(
					1,
					$def_billerField,
					$def_customerField,
					$def_taxField,
					$def_inv_preferenceField,
					$def_number_line_itemsField,
					'$def_inv_templateField',
					$default_payment_type
				)";

                if (mysql_query($sql, $conn)) {
                        $display_block =  "System default: Default payment_type updated,<br> you will be redirected back to System Defaults page";
                } else {
                        $display_block =  "Something went wrong, please try setting the default tax again<br>$_POST[def_inv_template]
<br>$sql<br><br>
<br>(1,$def_customerField,$def_billerField,$def_taxField,$def_inv_preferenceField,$def_number_line_itemsField,$def_inv_template)
 ";
}

        header( 'refresh: 2; url=system_default_details.php' );
}

}
#end system default section


#insert invoice_total - start
else if ( isset( $_POST['invoice_style'] ) && $_POST['invoice_style'] === 'insert_invoice_total' ) {

	$sql = "INSERT into 
			si_invoices 
		VALUES 
			(
				'',
				'$_POST[sel_id]',
				'$_POST[select_customer]',
				'1',
				'$_POST[select_preferences]',
				now(),
				'$_POST[invoice_total_note]'
			)";

	if (mysql_query($sql)) {
        	$display_block =  "Processing invoice, <br> you will be redirected Quick View of this invoice";
	} else {
        	$display_block =  "Something went wrong, please try adding the invoice again";
}

	#get the invoice id from the insert
	$invoice_id = mysql_insert_id();


	#tax percentage query
	$print_tax_percentage = "SELECT * FROM si_tax WHERE tax_id ='$_POST[select_tax]'";
	$result_print_tax_percentage = mysql_query($print_tax_percentage, $conn) or die(mysql_error());


	while ($Array_tax = mysql_fetch_array($result_print_tax_percentage)) {
                $tax_idField = $Array_tax['tax_id'];
                $tax_descriptionField = $Array_tax['tax_description'];
                $tax_percentageField = $Array_tax['tax_percentage'];

	};

	$actual_tax = $tax_percentageField / 100;
	$total_invoice_total_tax = $_POST[inv_it_gross_total] * $actual_tax ;
	$total_invoice_total = $total_invoice_total_tax + $_POST[inv_it_gross_total] ;	
		

	$sql_items = "INSERT into 
				si_invoice_items 
			VALUES 
				(
					'',
					$invoice_id,
					'1',
					'00',
					'00',
					'$_POST[select_tax]',
					$tax_percentageField,
					$total_invoice_total_tax,		
					'$_POST[inv_it_gross_total]',
					'$_POST[i_description]',
					$total_invoice_total
				)
			";


	if (mysql_query($sql_items)) {
        	$display_block_items =  "Processing invoice items<br> you will be redirected back to the Quick View of this invoice";
	} else { die(mysql_error());
}

	$refresh_total = "<META HTTP-EQUIV=REFRESH CONTENT=1;URL=print_quick_view.php?submit=$invoice_id&invoice_style=Total>";

}
#insert invoice_total - end

#EDIT invoice_total
else if ( isset( $_POST['invoice_style'] ) && $_POST['invoice_style'] === 'edit_invoice_total' ) {

	$invoice_id = $_POST[invoice_id];

	#update the si_invoices table with customer etc  stuff - start
        $sql = "UPDATE 
			si_invoices 
		SET 
			inv_biller_id = '$_POST[sel_id]',
			inv_customer_id = '$_POST[select_customer]',
			inv_preference = '$_POST[select_preferences]' 
		WHERE 
			inv_id = $invoice_id"; 

        if (mysql_query($sql)) {
                $display_block =  "Processing invoice, <br> you will be redirected Quick View of this invoice";
        } else {
                $display_block =  "Something went wrong, please try adding the invoice again"; }
	
	#update the si_invoices table with customer etc  stuff - end
	
        #tax percentage query -start
        $print_tax_percentage = "SELECT * FROM si_tax WHERE tax_id ='$_POST[select_tax]'";
        $result_print_tax_percentage = mysql_query($print_tax_percentage, $conn) or die(mysql_error());


        while ($Array_tax = mysql_fetch_array($result_print_tax_percentage)) {
                $tax_idField = $Array_tax['tax_id'];
                $tax_descriptionField = $Array_tax['tax_description'];
                $tax_percentageField = $Array_tax['tax_percentage'];

        };
	#tax info - end	
	
	#calcultate the invoice total - start
        $actual_tax = $tax_percentageField / 100;
        $total_invoice_total_tax = $_POST[inv_it_gross_total] * $actual_tax ;
        $total_invoice_total = $total_invoice_total_tax + $_POST[inv_it_gross_total] ;
	#calcultate the invoice total - end

	#update the si_invoice_items table - which tax,description etc.. - start
	$sql_items = "UPDATE 
				si_invoice_items 
			SET 
				inv_it_tax_id = '$_POST[select_tax]',  
				inv_it_tax = $tax_percentageField,
				inv_it_tax_amount = $total_invoice_total_tax,
	                        inv_it_gross_total = '$_POST[inv_it_gross_total]',
				inv_it_description = '$_POST[i_description]',
				inv_it_total = $total_invoice_total 
			WHERE 
				inv_it_invoice_id = $invoice_id"; 


        if (mysql_query($sql_items)) {
                $display_block_items =  "Processing invoice items<br> you will be redirected back to the Quick View of this invoice";
        } else { die(mysql_error());
}

        $refresh_total = "<META HTTP-EQUIV=REFRESH CONTENT=1;URL=print_quick_view.php?submit=$invoice_id&invoice_style=Total>";

}

#EDIT invoce total - end


#insert invoice_itemised

else if ( isset( $_POST['invoice_style'] ) && $_POST['invoice_style'] === 'insert_invoice_itemised' ) {

	$invoice_itemised_note_field = $_POST[invoice_itemised_note];
	$sql = "INSERT into si_invoices values ('','$_POST[sel_id]','$_POST[select_customer]', 2,'$_POST[select_preferences]',now(),'$invoice_itemised_note_field')";

	if (mysql_query($sql)) {
        	$display_block =  "Processing invoice, <br> you will be redirected back to the Quick View of this invoice";
	} else {
        	$display_block =  "Something went wrong, please try adding the invoice again";
}

	#get the invoice id from the insert
	$invoice_id = mysql_insert_id();


	#tax percentage query
	$print_tax_percentage = "SELECT * FROM si_tax WHERE tax_id ='$_POST[select_tax]'";
	$result_print_tax_percentage = mysql_query($print_tax_percentage, $conn) or die(mysql_error());


	while ($Array_tax = mysql_fetch_array($result_print_tax_percentage)) {
                $tax_idField = $Array_tax['tax_id'];
                $tax_descriptionField = $Array_tax['tax_description'];
                $tax_percentageField = $Array_tax['tax_percentage'];

	};
/*
        #product info query
        $print_products_info = "SELECT * FROM si_products WHERE prod_id ='$_POST[select_products]'";
        $result_print_products_info = mysql_query($print_products_info , $conn) or die(mysql_error());


        while ($Array_tax = mysql_fetch_array($result_print_products_info )) {
                $prod_idField = $Array_tax['tax_id'];
                $prod_descriptionField = $Array_tax['prod_description'];
                $prod_unit_priceField = $Array_tax['prod_unit_price'];

        };
*/
	$num = $_POST[max_items];
        $items = 0;
        while ($items < $num) :
 
	       /* echo "<b>$items</b><br>"; */
                $qty = $_POST["i_quantity$items"];
                $product_line_item = $_POST["select_products$items"];
               /* echo "Qty: $qty<br> "; */
               /*  echo "Prod ID: $product_line_item<br> "; */
	
		
		#break out of the while if no QUANTITY
		if (empty($_POST["i_quantity$items"])) {
			/*echo "continue"; */
			break;
		}
			

	        $print_products_info = "SELECT * FROM si_products WHERE prod_id =$product_line_item";
       		$result_print_products_info = mysql_query($print_products_info , $conn) or die(mysql_error());


	        while ($Array_tax = mysql_fetch_array($result_print_products_info )) {
        	        $prod_idField = $Array_tax['tax_id'];
                	$prod_descriptionField = $Array_tax['prod_description'];
	                $prod_unit_priceField = $Array_tax['prod_unit_price'];

        	};

		$actual_tax = $tax_percentageField  / 100 ;
		$total_invoice_item_tax = $prod_unit_priceField * $actual_tax;
		$total_invoice_tax_amount = $total_invoice_item_tax * $_POST["i_quantity$items"];
		$total_invoice_item = $total_invoice_item_tax + $prod_unit_priceField ;	
		$total_invoice_item_total = $total_invoice_item * $_POST["i_quantity$items"];
		$total_invoice_item_gross = $prod_unit_priceField  * $_POST["i_quantity$items"];
		

		$sql_items = "INSERT into si_invoice_items values ('',$invoice_id,$qty,$product_line_item,$prod_unit_priceField,'$_POST[select_tax]',$tax_percentageField,$total_invoice_tax_amount,$total_invoice_item_gross,'00',$total_invoice_item_total)";
	
		/*
		mysql_query($sql_items);
		*/

		
		if (mysql_query($sql_items)) {
        		$display_block_items =  "Processing invoice items<br> you will be redirected back to Quick View of this invoice";
		} else { die(mysql_error());
		}
		
                /* echo "$sql_items <br>";  */
		$items++ ;
         endwhile;


	$refresh_total = "<META HTTP-EQUIV=REFRESH CONTENT=1;URL=print_quick_view.php?submit=$invoice_id&invoice_style=Itemised>";


}




#EDIT INVOICE ITEMISED - START

else if ( isset( $_POST['invoice_style'] ) && $_POST['invoice_style'] === 'edit_invoice_itemised' ) {

        $invoice_id = $_POST[invoice_id];

        #update the si_invoices table with customer etc  stuff - start
        $sql = "UPDATE
                        si_invoices
                SET
                        inv_biller_id = '$_POST[sel_id]',
                        inv_customer_id = '$_POST[select_customer]',
                        inv_preference = '$_POST[select_preferences]',
			inv_note = '$_POST[invoice_itemised_note]'
                WHERE
                        inv_id = $invoice_id";

      if (mysql_query($sql)) {
                $display_block =  "Processing invoice, <br> you will be redirected back to the Quick View of this invoice";
        } else {
                $display_block =  "Something went wrong, please try adding the invoice again";
}


	#$display_block .= "step 2 - 1";
        #tax percentage query
        $print_tax_percentage = "SELECT * FROM si_tax WHERE tax_id ='$_POST[select_tax]'";
        $result_print_tax_percentage = mysql_query($print_tax_percentage, $conn) or die(mysql_error());


        while ($Array_tax = mysql_fetch_array($result_print_tax_percentage)) {
                $tax_idField = $Array_tax['tax_id'];
                $tax_descriptionField = $Array_tax['tax_description'];
                $tax_percentageField = $Array_tax['tax_percentage'];

        };
/*
        #product info query
        $print_products_info = "SELECT * FROM si_products WHERE prod_id ='$_POST[select_products]'";
        $result_print_products_info = mysql_query($print_products_info , $conn) or die(mysql_error());


        while ($Array_tax = mysql_fetch_array($result_print_products_info )) {
                $prod_idField = $Array_tax['tax_id'];
                $prod_descriptionField = $Array_tax['prod_description'];
                $prod_unit_priceField = $Array_tax['prod_unit_price'];

        };
*/
	#$display_block .= "step 2 - 2";
        $num = $_POST[max_items];
        $items = 1;
	$product_id_items = 1;	
        while ($items < $num) :	

	$display_block_qty =$_POST["i_quantity$items"];
	#$display_block .= "step 2 - 3  - qty $display_block_qty!! ";
               /* echo "<b>$items</b><br>"; */
                $qty = $_POST["i_quantity$items"];
                $product_line_item = $_POST["select_products$product_id_items"];
               /* echo "Qty: $qty<br> "; */
               /*  echo "Prod ID: $product_line_item<br> "; */
		
		#$display_block .= "step 2 - 4 : qty $qty :: PLI=$product_line_item MAX-- $_POST[max_items];";
                #break out of the while if no QUANTITY
		
                if (empty($_POST["i_quantity$items"])) {
                       /*echo "continue"; */
                       break;
                }
		

                $print_products_info = "SELECT * FROM si_products WHERE prod_id =$product_line_item";
                $result_print_products_info = mysql_query($print_products_info , $conn) or die(mysql_error());
		
		#$display_block .= "step 2 - 5";
		
                while ($Array_tax = mysql_fetch_array($result_print_products_info )) {
                        $prod_idField = $Array_tax['tax_id'];
                        $prod_descriptionField = $Array_tax['prod_description'];
                        $prod_unit_priceField = $Array_tax['prod_unit_price'];

                };
                
		$actual_tax = $tax_percentageField  / 100 ;
                $total_invoice_item_tax = $prod_unit_priceField * $actual_tax;
                $total_invoice_tax_amount = $total_invoice_item_tax * $_POST["i_quantity$items"];
                $total_invoice_item = $total_invoice_item_tax + $prod_unit_priceField ;
                $total_invoice_item_total = $total_invoice_item * $_POST["i_quantity$items"];
                $total_invoice_item_gross = $prod_unit_priceField  * $_POST["i_quantity$items"];
		

		$invoice_id_item = $_POST["inv_it_id$items"];
		

		$sql_items = "REPLACE into 
					si_invoice_items 
				VALUES 
					(
						$invoice_id_item,
						$invoice_id,
						$qty,
						$product_line_item,
						$prod_unit_priceField,
						'$_POST[select_tax]',
						$tax_percentageField,
						$total_invoice_tax_amount,
						$total_invoice_item_gross,
						'00',
						$total_invoice_item_total
					)";


                if (mysql_query($sql_items)) {
                        $display_block_items =  "Processing invoice items<br> you will be redirected back to Quick View of this invoice";
                } else { die(mysql_error());
                }

                /* echo "$sql_items <br>";  */
                $items++ ;
		$product_id_items++;
         endwhile;



        $refresh_total = "<META HTTP-EQUIV=REFRESH CONTENT=1;URL=print_quick_view.php?submit=$invoice_id&invoice_style=Itemised>";

}


#EDIT Invoice Itemised - End


#Insert - INVOICE CONSULTING


else if ( isset( $_POST['invoice_style'] ) && $_POST['invoice_style'] === 'insert_invoice_consulting' ) {

	$sql = "INSERT into si_invoices values ('','$_POST[sel_id]','$_POST[select_customer]', 3,'$_POST[select_preferences]',now(),'$_POST[invoice_consulting_note]')";

	if (mysql_query($sql)) {
        	$display_block =  "Processing invoice, <br> you will be redirected back to the Quick View of this invoice";
	} else {
        	$display_block =  "Something went wrong, please try adding the invoice again";
}

	#get the invoice id from the insert
	$invoice_id = mysql_insert_id();


	#tax percentage query
	$print_tax_percentage = "SELECT * FROM si_tax WHERE tax_id ='$_POST[select_tax]'";
	$result_print_tax_percentage = mysql_query($print_tax_percentage, $conn) or die(mysql_error());


	while ($Array_tax = mysql_fetch_array($result_print_tax_percentage)) {
                $tax_idField = $Array_tax['tax_id'];
                $tax_descriptionField = $Array_tax['tax_description'];
                $tax_percentageField = $Array_tax['tax_percentage'];

	};
/*
        #product info query
        $print_products_info = "SELECT * FROM si_products WHERE prod_id ='$_POST[select_products]'";
        $result_print_products_info = mysql_query($print_products_info , $conn) or die(mysql_error());


        while ($Array_tax = mysql_fetch_array($result_print_products_info )) {
                $prod_idField = $Array_tax['tax_id'];
                $prod_descriptionField = $Array_tax['prod_description'];
                $prod_unit_priceField = $Array_tax['prod_unit_price'];

        };
*/
        $num = $_GET[num];
        $items = 0;
        while ($items < $num) :
 
			
	       /* echo "<b>$items</b><br>"; */
                $qty = $_POST["i_quantity$items"];
                $product_line_item = $_POST["select_products$items"];
                $line_item_description = $_POST["line_item_description$items"];
               /* echo "Qty: $qty<br> "; */
               /*  echo "Prod ID: $product_line_item<br> "; */
	
		#break out of the while if no QUANTITY
		if (empty($_POST["i_quantity$items"])) {
			/*echo "break"; */
			break;
		}

	        $print_products_info = "SELECT * FROM si_products WHERE prod_id =$product_line_item";
       		$result_print_products_info = mysql_query($print_products_info , $conn) or die(mysql_error());


	        while ($Array_tax = mysql_fetch_array($result_print_products_info )) {
        	        $prod_idField = $Array_tax['tax_id'];
                	$prod_descriptionField = $Array_tax['prod_description'];
	                $prod_unit_priceField = $Array_tax['prod_unit_price'];

        	};

		$actual_tax = $tax_percentageField  / 100 ;
		$total_invoice_item_tax = $prod_unit_priceField * $actual_tax;
		$total_invoice_tax_amount = $total_invoice_item_tax * $_POST["i_quantity$items"];
		$total_invoice_item = $total_invoice_item_tax + $prod_unit_priceField ;	
		$total_invoice_item_total = $total_invoice_item * $_POST["i_quantity$items"];
		$total_invoice_item_gross = $prod_unit_priceField  * $_POST["i_quantity$items"];
		

		$sql_items = "INSERT into si_invoice_items values ('',$invoice_id,$qty,$product_line_item,$prod_unit_priceField,'$_POST[select_tax]',$tax_percentageField,$total_invoice_tax_amount,$total_invoice_item_gross,'$line_item_description',$total_invoice_item_total)";
	
		/*
		mysql_query($sql_items);
		*/

		
		if (mysql_query($sql_items)) {
        		$display_block_items =  "Processing invoice items<br> you will be redirected back to Quick View of this invoice";
		} else { die(mysql_error());
		}
		
                /* echo "$sql_items <br>";  */
		$items++ ;
         endwhile;


	$refresh_total = "<META HTTP-EQUIV=REFRESH CONTENT=1;URL=print_quick_view.php?submit=$invoice_id&invoice_style=Consulting>";


}


#EDIT INVOICE CONSULTING - START

else if ( isset( $_POST['invoice_style'] ) && $_POST['invoice_style'] === 'edit_invoice_consulting' ) {

        $invoice_id = $_POST[invoice_id];

        #update the si_invoices table with customer etc  stuff - start
        $sql = "UPDATE
                        si_invoices
                SET
                        inv_biller_id = '$_POST[sel_id]',
                        inv_customer_id = '$_POST[select_customer]',
                        inv_preference = '$_POST[select_preferences]',
                        inv_note = '$_POST[invoice_itemised_note]'
                WHERE
                        inv_id = $invoice_id";

      if (mysql_query($sql)) {
                $display_block =  "Processing invoice, <br> you will be redirected back to the Quick View of this invoice";
        } else {
                $display_block =  "Something went wrong, please try adding the invoice again";
}


        #$display_block .= "step 2 - 1";
        #tax percentage query
        $print_tax_percentage = "SELECT * FROM si_tax WHERE tax_id ='$_POST[select_tax]'";
        $result_print_tax_percentage = mysql_query($print_tax_percentage, $conn) or die(mysql_error());

        while ($Array_tax = mysql_fetch_array($result_print_tax_percentage)) {
                $tax_idField = $Array_tax['tax_id'];
                $tax_descriptionField = $Array_tax['tax_description'];
                $tax_percentageField = $Array_tax['tax_percentage'];

        };
/*
        #product info query
        $print_products_info = "SELECT * FROM si_products WHERE prod_id ='$_POST[select_products]'";
        $result_print_products_info = mysql_query($print_products_info , $conn) or die(mysql_error());


        while ($Array_tax = mysql_fetch_array($result_print_products_info )) {
                $prod_idField = $Array_tax['tax_id'];
                $prod_descriptionField = $Array_tax['prod_description'];
                $prod_unit_priceField = $Array_tax['prod_unit_price'];

        };
*/
        #$display_block .= "step 2 - 2";
        $num = $_POST[max_items];
        $items = 1;
        $product_id_items = 1;
        while ($items < $num) :
	
        
        $consulting_item_note = $_POST["consulting_item_note$items"];
	$display_block_qty =$_POST["i_quantity$items"];
        #$display_block .= "step 2 - 3  - qty $display_block_qty!! ";
               /* echo "<b>$items</b><br>"; */
                $qty = $_POST["i_quantity$items"];
                $product_line_item = $_POST["select_products$product_id_items"];
				
               /* echo "Qty: $qty<br> "; */
               /*  echo "Prod ID: $product_line_item<br> "; */

                #$display_block .= "step 2 - 4 : qty $qty :: PLI=$product_line_item MAX-- $_POST[max_items];";
                #break out of the while if no QUANTITY
                if (empty($_POST["i_quantity$items"])) {
                        /*echo "break"; */
                       /* break;*/
                }

                $print_products_info = "SELECT * FROM si_products WHERE prod_id =$product_line_item";
                $result_print_products_info = mysql_query($print_products_info , $conn) or die(mysql_error());

                #$display_block .= "step 2 - 5  <br> $consulting_item_note ";

                while ($Array_tax = mysql_fetch_array($result_print_products_info )) {
                        $prod_idField = $Array_tax['tax_id'];
                        $prod_descriptionField = $Array_tax['prod_description'];
                        $prod_unit_priceField = $Array_tax['prod_unit_price'];

                };

                $actual_tax = $tax_percentageField  / 100 ;
                $total_invoice_item_tax = $prod_unit_priceField * $actual_tax;
                $total_invoice_tax_amount = $total_invoice_item_tax * $_POST["i_quantity$items"];
                $total_invoice_item = $total_invoice_item_tax + $prod_unit_priceField ;
                $total_invoice_item_total = $total_invoice_item * $_POST["i_quantity$items"];
                $total_invoice_item_gross = $prod_unit_priceField  * $_POST["i_quantity$items"];


                $invoice_id_item = $_POST["inv_it_id$items"];

                $sql_items = "REPLACE into
                                        si_invoice_items
                                VALUES
                                        (
                                                $invoice_id_item,
                                                $invoice_id,
                                                $qty,
                                                $product_line_item,
                                                $prod_unit_priceField,
                                                '$_POST[select_tax]',
                                                $tax_percentageField,
                                                $total_invoice_tax_amount,
                                                $total_invoice_item_gross,
                                                '$consulting_item_note',
                                                $total_invoice_item_total
                                        )";


                if (mysql_query($sql_items)) {
                        $display_block_items =  "Processing invoice items<br> you will be redirected back to Quick View of this invoice";
                } else { die(mysql_error());
                }

                /* echo "$sql_items <br>";  */
                $items++ ;
                $product_id_items++;
         endwhile;



        $refresh_total = "<META HTTP-EQUIV=REFRESH CONTENT=1;URL=print_quick_view.php?submit=$invoice_id&invoice_style=Consulting>";

}










?>

<html>
<head>
<HEAD>
<?php include('./config/config.php'); ?>
<?php include('./include/menu.php'); ?>
<?php include("./lang/$language.inc.php"); ?>

<?php echo isset( $refresh_total ) ? $refresh_total : '&nbsp'; ?>
<title>Simple Invoices</title>
</HEAD>

<link rel="stylesheet" type="text/css" href="themes/<?php echo $theme; ?>/tables.css">
<BODY>
<?php
$mid->printMenu('hormenu1');
$mid->printFooter();
?>
<br>
<br>
<?php echo $display_block; ?>
<br><br>
<?php echo isset( $display_block_items ) ? $display_block_items : '&nbsp;'; ?>

</body>
</html>
