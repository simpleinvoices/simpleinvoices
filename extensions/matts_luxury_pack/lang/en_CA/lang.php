<?php
/*
* Script: extensions/matts_luxury_pack/lang/en_CA/lang.php
* 	language translations
*
* Authors:
*	 git0matt@gmail.com
*
* Last edited:
* 	 2016-09-14
*
* License:
*	 GPL v2 or above
*
* Website:
* 	http://www.simpleinvoices.org
 */
// @formatter:off
$MYC_LANG = array(
// general
	'rows_per_page'					=> 'Rows per page',
	'simple_invoices' 				=> 'My Invoicing System',
	'simple_invoices_powered_by'	=> 'Powered by SimpleInvoices',
	'Modal'							=> 'Modal Iframe',
	'help_modal'					=> 'Enable a modal iframe window on some links while doing another operation.',
	'into'							=> 'into',
	'another' 						=> 'Add Another',
	'saved'							=> 'Saved',
	'close' 						=> 'Close',
	'redirect' 						=> 'You will be redirected',
// settings
	'price_list'					=> 'Price list',
	'help_price_list'				=> 'Enable up to 4 prices for each product; and each customer can be assigned a price-list number corresponding to the price, so every product purchased by that customner has the price in that price-list number box.',
	'price_lists'					=> 'Multiple Price Lists',
	'default_delnote'				=> 'Default Delivery Note',
//	'def_num_line_items'			=> 'Default number of line items',
	'help_product_lwhw'				=> 'Enable to put boxes for product length, width, height and weight when creating and viewing and editing a product.',
	'default_nrows'					=> 'Default rows-per-page',
	'help_nrows'					=> 'Number of rows initially displayed on the list of customers, invoices or products.',
// customers
	'select'						=> '-select-',
	'jan'							=> 'Jan',
	'feb'							=> 'Feb',
	'mar'							=> 'Mar',
	'apr'							=> 'Apr',
	'may'							=> 'May',
	'jun'							=> 'Jun',
	'jul'							=> 'Jul',
	'aug'							=> 'Aug',
	'sep'							=> 'Sep',
	'oct'							=> 'Oct',
	'nov'							=> 'Nov',
	'dec'							=> 'Dec',
	'credit_card_cvc'				=> 'CVV/CVC/CSC',
	'help_credit_card_cvc'			=> '<img src="./extensions/matts_luxury_pack/images/common/CVC2SampleVisaNew.png" alt="CVC2SampleVisaNew" /><br /> The card security code is located on the back of MasterCard, Visa, Discover, Diners Club, and JCB credit or debit cards and is typically a separate group of 3 digits to the right of the signature strip. <br /><img src="./extensions/matts_luxury_pack/images/common/SampleAmexCVC.png" alt="SampleAmexCVC" /><br /> On American Express cards, the card security code is a printed, not embossed, group of four digits on the front towards the right.',
	'help_credit_card_expiry_year'	=> '<img src="./extensions/matts_luxury_pack/images/common/SampleAmexExpireYear.png" alt="SampleAmexExpireYear" />',
	'help_credit_card_expiry_month'	=> '<img src="./extensions/matts_luxury_pack/images/common/SampleAmexExpireMonth.png" alt="SampleAmexExpireMonth" />',
	'help_credit_card_number'		=> '<img src="./extensions/matts_luxury_pack/images/common/SampleAmexCardNumber.png" alt="SampleAmexCardNumber" />',
	'help_credit_card_name'			=> '<img src="./extensions/matts_luxury_pack/images/common/SampleAmexCardName.png" alt="SampleAmexCardName" />',
	'saved_customer' 				=> 'Customer successfully saved.',
	'redirect_customers' 			=> 'You will be redirected back to the Manage Customers page.',
// invoices
	'ship_to'						=> 'Ship To',
	'help_ship_to'					=> 'Enable a ship to customer selection when creating and viewing and editing an invoice.',
	'deliver_to'					=> 'Deliver To',
	'no_ship_to'					=> 'empty',
	'pro_invoice'					=> 'Proposed Invoice #',
	'contactp'						=> 'Contact Person',
	'terms'							=> 'Terms',
	'help_terms'					=> 'Enable an invoice terms box when creating and viewing and editing an invoice.',
	'help_invoice_Modal' 			=> 'The below buttons will cause the operation to load in a pop-up window. This will allow the user to input other details while having another pending operation at hand.',
	'regenCusts'					=> 'Reload Customers',
	'regenProds'					=> 'Reload Products',
	'load_product'					=> 'Reload Product Select',
	'update'						=> 'Update',
	'in'							=> 'in',
	'with_new_price'				=> 'with New Price',
	'overwrite_product'				=> 'Overwrite Product',
	'saved_invoice' 				=> 'Processed Invoice.',
	'redirect_invoices' 			=> 'You will be redirected Quick View of this invoice.',
	'use_attn' 						=> 'Use a specified attention',
	'help_use_attn' 				=> 'Use this field to specify an attention which is different from (and will overwrite) the default attention that stored with the customer details',
// payment
	'saved_payment' 				=> 'Payment successfully Processed.',
	'redirect_payments' 			=> 'You will be redirected back to the Manage Payments page.',
// products
	'product_weight'				=> 'weight',
	'product_length'				=> 'length',
	'product_width'					=> 'width',
	'product_height'				=> 'height',
	'product_lwhw'					=> 'LxWxH+Weight',
	'saved_product' 				=> 'Product successfully saved.',
	'redirect_products' 			=> 'You will be redirected to the Manage Products page.',
// reports
	'invoices_created' 				=> 'Invoices created',
	'created' 						=> 'created',
	'period' 						=> 'for the period',
	'invoices_modified' 			=> 'Invoices modified',
	'modified'						=> 'modified',
	'payment_processed' 			=> 'Payments processed',
	'user' 							=> 'User',
	'on' 							=> 'on',
// debug
	'debug' 						=> 'debug',
	'cookies' 						=> 'Cookies',
	'request' 						=> 'Request',
	'get' 							=> 'GET',
	'post' 							=> 'POST',
	'key' 							=> 'key',
	'value'							=> 'value',
	'server' 						=> 'Server',
	'session' 						=> 'Session',
	'env' 							=> 'Environment',
	'rewind' 						=> 'rewind'
);
// @formatter:on
global $defaults;
$LANG = array_merge($LANG, $MYC_LANG);
