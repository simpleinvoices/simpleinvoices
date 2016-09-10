<?php
// /simple/extensions/customer_add_tabbed/lang/en_CA
// @formatter:off
$MYC_LANG = array(
	'rows_per_page'		=> "Rows per page",
	'simple_invoices' 	=> "My Invoicing System",
	'price_list'		=> "Price list",
	'price_lists'		=> "Multiple Price Lists",
	'simple_invoices_powered_by'	=> "Powered by SimpleInvoices",
	'select'			=> "-select-",
	'jan'				=> "Jan",
	'feb'				=> "Feb",
	'mar'				=> "Mar",
	'apr'				=> "Apr",
	'may'				=> "May",
	'jun'				=> "Jun",
	'jul'				=> "Jul",
	'aug'				=> "Aug",
	'sep'				=> "Sep",
	'oct'				=> "Oct",
	'nov'				=> "Nov",
	'dec'				=> "Dec",
	'default_delnote'	=> "Default Delivery Note",
	'def_num_line_items'	=> "Default number of line items",
	'credit_card_cvc'	=> "CVV/CVC/CSC",
	'help_credit_card_cvc'	=> '<img src="./extensions/matts_luxury_pack/images/common/CVC2SampleVisaNew.png" alt="CVC2SampleVisaNew" /><br /> The card security code is located on the back of MasterCard, Visa, Discover, Diners Club, and JCB credit or debit cards and is typically a separate group of 3 digits to the right of the signature strip. <br /><img src="./extensions/matts_luxury_pack/images/common/SampleAmexCVC.png" alt="SampleAmexCVC" /><br /> On American Express cards, the card security code is a printed, not embossed, group of four digits on the front towards the right.',
	'help_credit_card_expiry_year'	=> '<img src="./extensions/matts_luxury_pack/images/common/SampleAmexExpireYear.png" alt="SampleAmexExpireYear" />',
	'help_credit_card_expiry_month'	=> '<img src="./extensions/matts_luxury_pack/images/common/SampleAmexExpireMonth.png" alt="SampleAmexExpireMonth" />',
	'help_credit_card_number'	=> '<img src="./extensions/matts_luxury_pack/images/common/SampleAmexCardNumber.png" alt="SampleAmexCardNumber" />',
	'help_credit_card_name'	=> '<img src="./extensions/matts_luxury_pack/images/common/SampleAmexCardName.png" alt="SampleAmexCardName" />'
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
	'help_invoice_Modal' => "The below buttons will cause the operation to load in a pop-up window. This will allow the user to input other details while having another pending operation at hand.",
	'help_modal'	=> "Enable a modal iframe window on some links when creating an invoice.",
	'help_price_list'	=> "Enable up to 4 prices for each product; and each customer can be assigned a price-list number corresponding to the price, so every product purchased by that customner has the price in that price-list number box.",
	'regenCusts'	=> "Reload Customers",
	'regenProds'	=> "Reload Products"
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
