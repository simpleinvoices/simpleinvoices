<?php

#all
$title = "Simple Invoices";
$wording_for_enabledField ="Enabled";
$wording_for_disabledField ="Disabled";

#Index.php - front page
$indx_welcome ="Welcome to ";

$indx_shortcut ="Shortcut menu";

$indx_getting_started ="Getting started";
$indx_faqs_what ="What is Simple Invoices?";
$indx_faqs_need ="What do I need to start invoicing?";
$indx_faqs_how ="How do I create invoices?";
$indx_faqs_type ="How are the different types of invoices?";

$indx_create_invoice ="Create an invoice";
$indx_invoice_total ="Total";
$indx_invoice_itemised ="Itemised";
$indx_invoice_consulting ="Consulting";

$indx_manage_existing_invoice ="Manage your existing invoices";
$indx_manage_invoice ="Manage invoices";

$indx_manage_data ="Manage your data";
$indx_insert_customer = "Add Customer";
$indx_insert_biller = "Add Biller";
$indx_insert_product = "Add Product";

$indx_options ="Options";
$indx_options_sys_defaults ="System Defaults";
$indx_options_tax_rates ="Tax Rates";
$indx_options_inv_pref ="Invoice Preferences";
$indx_options_payment_types ="Payment Types";
$indx_options_upgrade ="Database Upgrade Manager";
$indx_options_backup ="Backup Database";

$indx_help ="Help!!!";
$indx_help_install ="Installation";
$indx_help_upgrade ="Upgrading Simple Invoices";
$indx_help_prepare ="Prepare Simple Invoices for use";

$indx_stats =" Quick stats";
$indx_stats_debtor ="Largest debtor";
$indx_stats_customer ="Top Customer - by amount invoiced";
$indx_stats_biller ="Top Biller - by amount invoiced";

#Manage Invoices
$mi_page_title = " - Manage Invoices";
$mi_page_header = "Manage Invoices";
$mi_no_invoices = "There are no invoices in the database";
$mi_table_action = "Action";
$mi_table_id = "ID";
$mi_table_biller = "Biller";
$mi_table_customer = "Customer";
$mi_table_total = "Total";
$mi_table_paid = "Paid";
$mi_table_owing = "Owing";
$mi_table_type = "Type";
$mi_table_date = "Date created";
$mi_actions_quick_view = "view";
$mi_actions_quick_view_tooltip = "Quick View of"; 
$mi_actions_edit_view = "edit";
$mi_actions_edit_view_toolkit = "Edit";
$mi_actions_print_preview_tooltip = "Print Preview of";
$mi_actions_export_tooltip = "Export"; 
$mi_actions_export_pdf_tooltip = "as PDF format";
$mi_actions_format_tooltip = "format";
$mi_actions_export_xls_tooltip = "to a spreadsheet as";
$mi_actions_export_doc_tooltip = "to a word processor as";
$mi_actions_process_payment = "Process payment for";
$mi_action_invoice_total = "Add new Invoice - Total style";
$mi_action_invoice_itemised = "Add new Invoice - Itemised style";
$mi_action_invoice_consulting = "Add new Invoice - Consulting style";


#Manage Products
$mp_page_title = " - Manage Products";
$mp_page_header = "Manage Products";
$mp_no_invoices = "There are no products in the database";
$mp_table_action = "Action";
$mp_table_product_id = "Product ID";
$mp_table_product_desc = "Products description";
$mp_table_unit_price = "Unit price";
$mp_actions_view = "View";
$mp_actions_edit = "Edit";
$mp_actions_new_product = "Add New Product";

#Manage Billers
$mb_page_title = " - Manage Billers";
$mb_page_header = "Manage Billers";
$mb_no_invoices = "There are no billers in the database";
$mb_table_action = "Action";
$mb_table_biller_id = "Biller ID";
$mb_table_biller_name = "Biller name";
$mb_table_phone = "Phone";
$mb_table_mobile_phone = "Mobile Phone";
$mb_table_email = "Email";
$mb_actions_view = "View";
$mb_actions_edit = "Edit";
$mb_actions_new_biller = "Add New Biller";

#Manage Customers
$mc_page_title = " - Manage Customers";
$mc_page_header = "Manage Customers";
$mc_no_invoices = "There are no customers in the database";
$mc_table_action = "Action";
$mc_table_customer_id = "Customer ID";
$mc_table_customer_name = "Customer name";
$mc_table_attention = "Attention";
$mc_table_phone = "Phone";
$mc_table_email = "Email";
$mc_actions_view = "View";
$mc_actions_edit = "Edit";
$mc_actions_new_product = "Add New Customer";

#Manage Preferences
$mip_page_title = " - Manage Preferences";
$mip_page_header = "Manage Preferences";
$mip_no_invoices = "There are no preferences in the database";
$mip_table_action = "Action";
$mip_table_preference_id = "Preference ID";
$mip_table_description = "Description";
$mip_actions_view = "View";
$mip_actions_edit = "Edit";
$mip_actions_new_preference = "Add New Invoice Preference";

#Manage System Defautls manage_system_defaults.php
$msd_default_number_items = "Default number of line items:";
$msd_js_alert_def_inv_template = "Default invoice template";
$msd_def_inv_template = "Default invoice template ";
$msd_no_tax = "Sorry, no tax rate available, please insert one";
$msd_no_payment_type = "Sorry, no payment type available, please insert one";
$msd_tax = "Tax";
$msd_payment_type = "Payment Type";
$msd_invoice_preference = "Invoice Preference";
$msd_no_defaults = "There are no defaults";
$msd_page_title = " - Manage System Defaults";
$msd_heading = "System defaults";
$msd_submit_button  = "Submit defaults";


#Manage Account Payments
$map_page_title = " - Manage Payments";
$map_page_header = "Manage Payments";
$map_no_invoices = "There are no payments in the database";
$map_table_action = "Action";
$map_table_payment_id = "Payment ID";
$map_table_payment_invoice_id = "Inv. ID";
$map_table_biller = "Biller";
$map_table_customer = "Customer";
$map_table_amount = "Amount";
$map_table_notes = "Notes";
$map_table_payment_type = "Payment Type";
$map_table_date = "Date";
$map_actions_view = "View";
$map_actions_process_payment = "Process Payment";
$map_payments_filtered = "Payments filtered by Invoice ID";
$map_payments_filtered_invoice = "Process Payment for this Invoice";
$map_payments_filtered_customer = "Payments filtered by Customer ID";

#Manage Tax Rate
$mtr_page_title = " - Manage Tax Rates";
$mtr_page_header = "Manage Tax Rates";
$mtr_no_invoices = "There are no tax rates in the database";
$mtr_table_action = "Action";
$mtr_table_tax_id = "Tax ID";
$mtr_table_tac_desc = "Tax description";
$mtr_table_percentage = "Tax percentage";
$mtr_actions_view = "View";
$mtr_actions_edit = "Edit";
$mtr_actions_new_tax = "Add New Tax Rate";


#Manage Payment Types
$mpt_page_title = " - Manage Payment Types";
$mpt_page_header = "Manage Payment Types";
$mpt_no_invoices = "There are no payment types in the database";
$mpt_table_action = "Action";
$mpt_table_pt_id = "ID";
$mpt_table_pt_description = "Description";
$mpt_actions_view = "View";
$mpt_actions_edit = "Edit";
$mpt_actions_new_tax = "Add New Payment Type";

#Print Preview - Invoice layout
$pp_invoice_number ="No.";
$pp_invoice_date ="date.";
$pp_invoice_mobile ="Mob.";
$pp_invoice_fax ="Fax";
$pp_invoice_email ="Email";
$pp_invoice_customer ="Customer";
$pp_invoice_attention ="Attn.";
$pp_invoice_phone ="Ph";
$pp_invoice_description ="Description";
$pp_invoice_gross_total ="Gross Total";
$pp_invoice_tax ="Tax";
$pp_invoice_total ="TOTAL";
$pp_invoice_unit_price ="Unit Price";
$pp_invoice_quantity ="Qty";
$pp_invoice_item ="Item";
$pp_invoice_note ="Note";
$pp_invoice_total_tax ="Total tax included";
$pp_invoice_amount ="Amount";
$pp_invoice_summary ="Summary";

?>
