<?php

/*// 1 means that the variable has been translated and // zero means it hasnt been translated - this is used by a script to calculate how much of each file has been done
regex :%s/;/ /1/;// 1\/\/1/g - remove the spaces
 */
#all
$title = "Simple Invoices";//1
$wording_for_enabledField ="Enabled";//1
$wording_for_disabledField ="Disabled";//1


#New lang file style $lang followed by the word or description - not grouped by page
$LANG_account_info = "Account Info";//1
$LANG_actions = "Actions";//1
$LANG_add_biller = "Add biller";//1
$LANG_add_new_invoice = "Add New Invoice";//1
$LANG_add_new_payment_type = "Add New payment Type";//1
$LANG_add_new_preference = "Add New Invoice Preference";//1
$LANG_add_new_product = "Add New Product";//1
$LANG_add_new_tax_rate = "Add New Tax Rate";//1
$LANG_address = "Address";//1
#might be able to delete adderss: variable - grep files
$LANG_address_city = "Address: City";//1
$LANG_address_country = "Address: Country";//1
$LANG_address_state = "Address: State";//1
$LANG_address_street = "Address: Street";//1
$LANG_address_zip = "Address: Zip";//1
$LANG_age = "Age";//1
$LANG_aging = "Aging";//1
$LANG_amount = "Amount";//1
$LANG_attention_short = "Attn.";//1
$LANG_biller = "Biller";//1
$LANG_biller_details = "Biller details";//1
$LANG_biller_edit = "Edit Biller";//1
$LANG_biller_id = "Biller ID";//1
$LANG_biller_name = "Biller Name";//1
$LANG_biller_to_add = "Biller to add";//1
$LANG_cancel = "Cancel";//1
$LANG_city = "City";//1
$LANG_consulting = "Consulting";//1
$LANG_consulting_style = "Consulting style";//1
$LANG_country = "Country";//1
$LANG_currency_sign = "Currency sign";//1
$LANG_custom_field1 = "Custom field 1";//1
$LANG_custom_field2 = "Custom field 2";//1
$LANG_custom_field3 = "Custom field 3";//1
$LANG_custom_field4 = "Custom field 4";//1
$LANG_custom_field = "Custom field";//1
$LANG_custom_field_db_field_name = "Database field name";//1
$LANG_custom_fields = "Custom fields";//1
$LANG_custom_label = "Custom label";//1
$LANG_customer = "Customer";//1
$LANG_customer_account = "Customer Account";//1
$LANG_customer_add = "Add New Customer";//1
$LANG_customer_contact = "Customer contact (Attn)";//1
$LANG_customer_details = "Customer details";//1
$LANG_customer_edit = "Edit Customer";//1
$LANG_customer_id = "Customer ID";//1
$LANG_customer_name = "Customer name";//1
$LANG_customers = "Customers";//1
$LANG_date= "date";//1
$LANG_date_created = "Date created";//1
$LANG_date_formatted = "Date (YYYY-MM-DD)";//1
$LANG_days = "days";//1
$LANG_description = "Description";//1
$LANG_details = "Details";//1
$LANG_edit = "Edit";//1
$LANG_edit_view_tooltip = "Edit";//1
$LANG_email = "Email";//1
$LANG_email_quick = "Quick Email";//1
$LANG_export_as = "Export as";//1
$LANG_export_doc_tooltip = "to a word processor as";//1
$LANG_export_pdf = "Export to PDF";//1
$LANG_export_pdf_tooltip = "as PDF format";//1
$LANG_export_tooltip = "Export";//1 
$LANG_export_xls_tooltip = "to a spreadsheet as";//1
$LANG_fax = "Fax";//1
$LANG_format_tooltip = "format";//1
$LANG_gross_total = "Gross";//1
$LANG_hide_details = "Hide details";//1
$LANG_id = "ID";//1
$LANG_ie_10_for_10 = "* ie. 10 for 10%";//1
$LANG_included = "included";//1
$LANG_insert_biller = "Insert Biller";//1
$LANG_insert_customer = "Insert Customer";//1
$LANG_insert_payment_type = "Insert Payment Type";//1
$LANG_insert_preference = "Insert Preference";//1
$LANG_insert_product = "Insert Product";//1
$LANG_insert_tax_rate = "Insert Tax Rate";//1
$LANG_inv = "Invoice";//1
$LANG_inv_consulting = " - Consulting";//1
$LANG_inv_itemised = " - Itemised";//1
$LANG_inv_pref = "Invoice Preference";//1
$LANG_inv_total = " - Total";//1
$LANG_invoice_detail_heading = "Invoice detail heading";//1
$LANG_invoice_detail_line = "Invoice detail line";//1
$LANG_invoice_footer = "Invoice footer";//1
$LANG_invoice_heading = "Invoice heading";//1
$LANG_invoice_id = "Invoice ID";//1
$LANG_invoice_listings = "Invoice listing";//1
$LANG_invoice_payment_line_1_name = "Invoice payment line 1 name";//1
$LANG_invoice_payment_line_1_value = "Invoice payment line 1 value";//1
$LANG_invoice_payment_line_2_name = "Invoice payment line 2 name";//1
$LANG_invoice_payment_line_2_value = "Invoice payment line 2 value";//1
$LANG_invoice_payment_method = "Invoice payment method";//1
$LANG_invoice_preference_to_add = "Invoice preference to add";//1
$LANG_invoice_summary = "Invoice Summary";//1
$LANG_invoice_type = "Type";//1
$LANG_invoice_wording = "Invoice wording";//1
$LANG_item = "Item";//1
$LANG_itemised = "Itemised";//1
$LANG_itemised_style = "Itemised style";//1
$LANG_logo_file = "Logo file";//1
$LANG_manage = "Manage";//1
$LANG_manage_custom_fields = "Manage Custom Fields";//1
$LANG_manage_customers = "Manage Customers";//1
$LANG_manage_invoices = "Manage Invoices";//1
$LANG_manage_payment_types = "Manage Payment Types";//1
$LANG_manage_preferences = "Manage Preferences";//1
$LANG_manage_products = "Manage Products";//1
$LANG_manage_tax_rates = "Manage Tax Rates";//1
$LANG_mandatory_fields = "All fields are mandatory";//1
$LANG_mobile_phone = "Mobile Phone";//1
$LANG_mobile_short = "Mob.";//1
$LANG_no_customers = "There are no customers in the database, please add one";//1
$LANG_no_invoices = "There are no invoices in the database";//1
$LANG_no_payment_types = "Sorry, no payment types available, please insert one";//1
$LANG_no_preferences = "There are no invoice preferences in the database, please add one";//1
$LANG_no_tax_rates = "There are no tax rates in the database, please add one";//1
$LANG_note = "Note";//1
$LANG_notes = "Notes";//1
$LANG_notes_opt = "Notes (optional)";//1
$LANG_number_short = "No.";//1
$LANG_owing = "Owing";//1
$LANG_optional = "optional";//1
$LANG_paid = "Paid";//1
$LANG_payment_type = "Payment Type";//1
$LANG_payment_type_description = "Payment type description";//1
$LANG_payment_type_details = "Payment Type Details";//1
$LANG_payment_type_id = "Payment Type ID";//1
$LANG_payment_type_method = "Payment Type/Method";//1
$LANG_payment_type_to_add = "Payment type to add";//1
$LANG_phone = "Phone";//1
$LANG_phone_short = "Ph.";//1
$LANG_preference_id = "Preference ID";//1
$LANG_print_preview = "Print Preview";//1
$LANG_print_preview_tooltip = "Print Preview of";//1
$LANG_process_payment = "Process Payment";//1
$LANG_process_payment_for = "Process Payment for";//1
$LANG_product = "Product";//1
$LANG_product_description = "Product Description";//1
$LANG_product_edit = "Edit Product";//1
$LANG_product_enabled = "Product Enabled";//1
$LANG_product_id = "Product ID";//1
$LANG_product_to_add = "Product to add";//1
$LANG_product_unit_price = "Product Unit Price";//1
$LANG_products = "Products";//1
$LANG_provision_of = "Provision of";//1
$LANG_quick_view_of = "This is a Quick View of";//1
$LANG_quick_view_tooltip = "Quick View of";//1 
$LANG_save = "Save";//1
$LANG_save_custom_field = "Save Custom Field";//1
$LANG_save_payment_type = "Save Payment Type";//1
$LANG_save_product = "Save Product";//1
$LANG_save_tax_rate = "Save Tax Rate";//1
$LANG_select_invoice = "Please select an invoice";//1
$LANG_show_details = "Show details";//1
$LANG_state = "State";//1
$LANG_street = "Street";//1
$LANG_street2 = "Street address 2";//1
$LANG_sub_total = "Sub Total";//1
$LANG_sum = "Sum";//1
$LANG_summary = "Summary";//1
$LANG_summary_of_accounts = "Summary of accounts";//1
$LANG_tax = "Tax";//1
$LANG_tax_description = "Tax description";//1
$LANG_tax_id = "Tax ID";//1
$LANG_tax_percentage = "Tax Percentage";//1
$LANG_tax_rate = "Tax Rate";//1
$LANG_tax_rate_details = "Tax rate details";//1
$LANG_tax_rate_id = "Tax Rate ID";//1
$LANG_tax_rate_to_add = "Tax rate to add";//1
$LANG_tax_total = "Total tax included";//1
$LANG_telephone_short = "Tel";//1
$LANG_total = "Total";//1
$LANG_total_amount = "Total amount";//1
$LANG_total_invoices = "Total Invoices";//1
$LANG_total_owing = "Total Owing";//1
$LANG_total_paid = "Total Paid";//1
$LANG_total_style = "Total style";//1
$LANG_total_uppercase = "TOTAL";//1
$LANG_totals = "Totals";//1
$LANG_unit_price = "Unit Price";//1
$LANG_view = "View";//1
$LANG_quantity = "Quantity";//1
$LANG_quantity_short = "Qty";//1
$LANG_welcome = "Welcome to ";//1
$LANG_zip = "Zip code";//1


#Index.php - front page

$indx_welcome ="Welcome to ";//1

$indx_shortcut ="Shortcut menu";//1
$LANG_shortcut ="Shortcut menu";//1

$indx_getting_started ="Getting started";//1
$LANG_getting_started ="Getting started";//1
$indx_faqs_what ="What is Simple Invoices?";//1
$LANG_faqs_what ="What is Simple Invoices?";//1
$indx_faqs_need ="What do I need to start invoicing?";//1
$LANG_faqs_need ="What do I need to start invoicing?";//1
$indx_faqs_how ="How do I create invoices?";//1
$LANG_faqs_how ="How do I create invoices?";//1
$indx_faqs_type ="What are the different types of invoices?";//1
$LANG_faqs_type ="What are the different types of invoices?";//1

$LANG_create_invoice ="Create an invoice";//1
$indx_invoice_total ="Total";//1
$indx_invoice_itemised ="Itemised";//1
$indx_invoice_consulting ="Consulting";//1

$indx_manage_existing_invoice ="Manage your existing invoices";//1
$LANG_manage_existing_invoice ="Manage your existing invoices";//1
$indx_manage_invoices ="Manage invoices";//1
$LANG_manage_invoices ="Manage invoices";//1

$indx_manage_data ="Manage your data";//1
$LANG_manage_data ="Manage your data";//1
$indx_insert_customer = "Add Customer";//1
$indx_insert_biller = "Add Biller";//1
$indx_insert_product = "Add Product";//1

$indx_options ="Options";//1
$indx_options_sys_defaults ="System Defaults";//1
$indx_options_tax_rates ="Tax Rates";//1
$indx_options_inv_pref ="Invoice Preferences";//1
$indx_options_payment_types ="Payment Types";//1
$indx_options_upgrade ="Database Upgrade Manager";//1
$indx_options_backup ="Backup Database";//1

$indx_help ="Help!!!";//1
$indx_help_install ="Installation";//1
$indx_help_upgrade ="Upgrading Simple Invoices";//1
$indx_help_prepare ="Prepare Simple Invoices for use";//1

$indx_stats =" Quick stats";//1
$LANG_stats =" Quick reports";//1
$indx_stats_debtor ="Largest debtor";//1
$LANG_stats_debtor ="Largest debtor";//1
$indx_stats_customer ="Top Customer - by amount invoiced";//1
$LANG_stats_customer ="Top Customer - by amount invoiced";//1
$indx_stats_biller ="Top Biller - by amount invoiced";//1
$LANG_stats_biller ="Top Biller - by amount invoiced";//1

#Manage Invoices
$mi_page_title = " - Manage Invoices";//1
$mi_page_header = "Manage Invoices";//1
$mi_no_invoices = "There are no invoices in the database";//1
$mi_table_action = "Action";//1
$mi_table_id = "ID";//1
$mi_table_biller = "Biller";//1
$mi_table_customer = "Customer";//1
$mi_table_total = "Total";//1
$mi_table_paid = "Paid";//1
$mi_table_owing = "Owing";//1
$mi_table_type = "Type";//1
$mi_table_date = "Date created";//1
$mi_actions_quick_view = "view";//1
$mi_actions_quick_view_tooltip = "Quick View of";//1 
$mi_actions_edit_view = "edit";//1
$mi_actions_edit_view_toolkit = "Edit";//1
$mi_actions_print_preview_tooltip = "Print Preview of";//1
$mi_actions_export_tooltip = "Export";//1 
$mi_actions_export_pdf_tooltip = "as PDF format";//1
$mi_actions_format_tooltip = "format";//1
$mi_actions_export_xls_tooltip = "to a spreadsheet as";//1
$mi_actions_export_doc_tooltip = "to a word processor as";//1
$mi_actions_process_payment = "Process payment for";//1
$mi_action_invoice_total = "Add new Invoice - Total style";//1
$mi_action_invoice_itemised = "Add new Invoice - Itemised style";//1
$mi_action_invoice_consulting = "Add new Invoice - Consulting style";//1

#Manage Products
$mp_page_title = " - Manage Products";//1
$mp_page_header = "Manage Products";//1
$mp_no_invoices = "There are no products in the database, please add one";//1
$mp_table_action = "Action";//1
$mp_table_product_id = "Product ID";//1
$mp_table_product_desc = "Products description";//1
$mp_table_unit_price = "Unit Price";//1
$mp_actions_view = "View";//1
$mp_actions_edit = "Edit";//1
$mp_actions_new_product = "Add New Product";//1

#Manage Billers
$mb_page_title = " - Manage Billers";//1
$mb_page_header = "Manage Billers";//1
$mb_no_invoices = "There are no billers in the database, please add one";//1
$mb_table_action = "Action";//1
$mb_table_biller_id = "Biller ID";//1
$mb_table_biller_name = "Biller Name";//1
$mb_table_phone = "Phone";//1
$mb_table_mobile_phone = "Mobile Phone";//1
$mb_table_email = "Email";//1
$mb_actions_view = "View";//1
$mb_actions_edit = "Edit";//1
$mb_actions_new_biller = "Add New Biller";//1

#Manage Customers
$mc_page_title = " - Manage Customers";//1
$mc_page_header = "Manage Customers";//1
$mc_no_invoices = "There are no customers in the database, please add one";//1
$mc_table_action = "Action";//1
$mc_table_customer_id = "Customer ID";//1
$mc_table_customer_name = "Customer Name";//1
$mc_table_attention = "Attention";//1
$mc_table_phone = "Phone";//1
$mc_table_email = "Email";//1
$mc_actions_view = "View";//1
$mc_actions_edit = "Edit";//1
$mc_actions_new_product = "Add New Customer";//1

#Manage Preferences
$mip_page_title = " - Manage Preferences";//1
$mip_page_header = "Manage Preferences";//1
$mip_no_invoices = "There are no invoice preferences in the database, please add one";//1
$mip_table_action = "Action";//1
$mip_table_preference_id = "Preference ID";//1
$mip_table_description = "Description";//1
$mip_actions_view = "View";//1
$mip_actions_edit = "Edit";//1
$mip_actions_new_preference = "Add New Invoice Preference";//1

#Manage System Defautls manage_system_defaults.php
$msd_default_number_items = "Default number of line items:";//1
$msd_js_alert_def_inv_template = "Default invoice template";//1
$msd_def_inv_template = "Default invoice template ";//1
$msd_no_tax = "Sorry, no tax rate available, please insert one";//1
$msd_no_payment_type = "Sorry, no payment type available, please insert one";//1
$msd_tax = "Tax";//1
$msd_payment_type = "Payment Type";//1
$msd_invoice_preference = "Invoice Preference";//1
$msd_no_defaults = "There are no defaults";//1
$msd_page_title = " - Manage System Defaults";//1
$msd_heading = "System defaults";//1
$msd_submit_button  = "Submit defaults";//1


#Manage Account Payments
$map_page_title = " - Manage Payments";//1
$map_page_header = "Manage Payments";//1
$map_no_invoices = "There are no payments in the database";//1
$map_table_action = "Action";//1
$map_table_payment_id = "Payment ID";//1
$map_table_payment_invoice_id = "Inv. ID";//1
$map_table_biller = "Biller";//1
$map_table_customer = "Customer";//1
$map_table_amount = "Amount";//1
$map_table_notes = "Notes";//1
$map_table_payment_type = "Payment Type";//1
$map_table_date = "Date";//1
$map_actions_view = "View";//1
$map_actions_process_payment = "Process Payment";//1
$map_payments_filtered = "Payments filtered by Invoice ID";//1
$map_payments_filtered_invoice = "Process Payment for this Invoice";//1
$map_payments_filtered_customer = "Payments filtered by Customer ID";//1

#Manage Tax Rate
$mtr_page_title = " - Manage Tax Rates";//1
$mtr_page_header = "Manage Tax Rates";//1
$mtr_no_invoices = "There are no tax rates in the database, please add one";//1
$mtr_table_action = "Action";//1
$mtr_table_tax_id = "Tax ID";//1
$mtr_table_tac_desc = "Tax description";//1
$mtr_table_percentage = "Tax percentage";//1
$mtr_actions_view = "View";//1
$mtr_actions_edit = "Edit";//1
$mtr_actions_new_tax = "Add New Tax Rate";//1

#Manage Payment Types
$mpt_page_title = " - Manage Payment Types";//1
$mpt_page_header = "Manage Payment Types";//1
$mpt_no_invoices = "There are no payment types in the database";//1
$mpt_table_action = "Action";//1
$mpt_table_pt_id = "ID";//1
$mpt_table_pt_description = "Description";//1
$mpt_actions_view = "View";//1
$mpt_actions_edit = "Edit";//1
$mpt_actions_new_tax = "Add New Payment Type";//1


?>
