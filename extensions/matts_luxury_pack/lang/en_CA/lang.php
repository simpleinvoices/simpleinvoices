<?php
// /simple/extensions/customer_add_tabbed/lang/en_CA
// @formatter:off
$MYC_LANG = array(
	'rows_per_page'		=> "Rows per page",
	'simple_invoices' 	=> "My Invoicing System",
	'price_list'		=> "Price list"
);

/**************************************************************/

//extensions/invoice_add_display_no/lang/en_CA
$MYI_LANG = array(
	'ship_to'		=> "Ship To",
	'help_ship_to'	=> "Enable a ship to customer selection when creating and viewing and editing an invoice.",
	'deliver_to'	=> "Deliver To",
	'no_ship_to'	=> "empty",
	'pro_invoice'	=> "Proposed Invoice #",
	'contactp'		=> "Contact Person",
	'terms'			=> "Terms",
	'help_terms'	=> "Enable an invoice terms box when creating and viewing and editing an invoice.",
	'Modal'			=> "Modal Iframe",
	'help_modal'	=> "Enable a modal iframe window on some links when creating an invoice.",
	'help_price_list'	=> "Enable up to 4 prices for each product; and each customer can be assigned a price-list number corresponding to the price, so every product purchased by that customner has the price in that price-list number box."
);

/**************************************************************/

// /simple/extensions/product_add_LxWxH_weight/lang/en_CA
$MYP_LANG = array(
	'product_weight'	=> "weight",
	'product_length'	=> "length",
	'product_width'		=> "width",
	'product_height'	=> "height",
	'product_lwhw'		=> "LxWxH+Weight",
	'help_product_lwhw'	=> "Enable to put boxes for product length, width, height and weight when creating and viewing and editing a product.",
	'default_nrows'		=> "Default rows-per-page",
	'help_nrows'		=> "Number of rows initially displayed on the list of customers, invoices or products."
);
// @formatter:on
global $defaults;
$LANG = array_merge($LANG, $MYC_LANG, $MYI_LANG, $MYP_LANG);
