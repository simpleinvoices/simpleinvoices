<?php

/*
@author: 
@last_edited: 
*/

/*// 1 means that the variable has been translated and // zero means it hasnt been translated - this is used by a script to calculate how much of each file has been done
regex :%s/;/ /1/;// 1\/\/1/g - remove the spaces
 */

#all
$title = "Facturas Simples";//1
$wording_for_enabledField ="Enabled";//0
$wording_for_disabledField ="Disabled";//0


#New lang file style $lang followed by the word or description - not grouped by page
$LANG_about = "About";//0
$LANG_account_info = "Account Info";//0
$LANG_actions = "Actions";//0
$LANG_add_biller = "Add biller";//0
$LANG_add_customer = "Add Customer";//0
$LANG_add_invoice_preference = "Add Invoice Preference";//0
$LANG_add_new_invoice = "Add New Invoice";//0
$LANG_add_new_payment_type = "Add New Payment Type";//0
$LANG_add_new_preference = "Add New Invoice Preference";//0
$LANG_add_new_product = "Add New Product";//0
$LANG_add_new_tax_rate = "Add New Tax Rate";//0
$LANG_add_tax_rate = "Add Tax Rate";//0
$LANG_add_payment_type = "Add Payment Type";//0
$LANG_add_product = "Add Product";//0
$LANG_address = "Address";//0
#might be able to delete adderss: variable - grep files
$LANG_address_city = "Address: City";//0
$LANG_address_country = "Address: Country";//0
$LANG_address_state = "Address: State";//0
$LANG_address_street = "Address: Street";//0
$LANG_address_zip = "Address: Zip";//0
$LANG_age = "Age";//0
$LANG_aging = "Aging";//0
$LANG_amount = "Amount";//0
$LANG_attention_short = "Attn.";//0
$LANG_backup_database = "Backup Database";//0
$LANG_biller = "Biller";//0
$LANG_biller_details = "Biller details";//0
$LANG_biller_edit = "Edit Biller";//0
$LANG_biller_id = "Biller ID";//0
$LANG_biller_name = "Biller Name";//0
$LANG_biller_to_add = "Biller to add";//0
$LANG_biller_sales = "Biller sales";//0
$LANG_biller_sales_total = "Biller sales - Total";//0
$LANG_biller_sales_by_customer_totals = "Biller sales by Customer - Totals"; //0
$LANG_billers = "Billers";//0
$LANG_cancel = "Cancel";//0
$LANG_change_log = "Change Log";//0
$LANG_city = "City";//0
$LANG_consulting = "Consulting";//0
$LANG_consulting_style = "Consulting style";//0
$LANG_country = "Country";//0
$LANG_credits = "Credits";//0
$LANG_currency_sign = "Currency sign";//0
$LANG_custom_field1 = "Custom field 1";//0
$LANG_custom_field2 = "Custom field 2";//0
$LANG_custom_field3 = "Custom field 3";//0
$LANG_custom_field4 = "Custom field 4";//0
$LANG_custom_field = "Custom field";//0
$LANG_custom_field_db_field_name = "Database field name";//0
$LANG_custom_fields = "Custom fields";//0
$LANG_custom_fields_upper = "Custom Fields";//0
$LANG_custom_label = "Custom label";//0
$LANG_customer = "Customer";//0
$LANG_customer_account = "Customer Account";//0
$LANG_customer_add = "Add New Customer";//0
$LANG_customer_contact = "Customer contact (Attn)";//0
$LANG_customer_details = "Customer details";//0 
$LANG_customer_edit = "Edit Customer";//0
$LANG_customer_id = "Número de Cliente";//1
$LANG_customer_name = "Customer name";//0
$LANG_customers = "Customers";//0 
$LANG_database_upgrade_manager= "Database Upgrade Manager";//0
$LANG_date= "date";//0
$LANG_date_created = "Data da criação";//1
$LANG_date_formatted = "Date (YYYY-MM-DD)";//0
$LANG_days = "days";//0
$LANG_debtors = "Debtors";//0
$LANG_debtors_by_amount_owed = "Debtors by amount owed";//0
$LANG_debtors_by_aging_periods = "Debtors by Aging periods";//0
$LANG_description = "Description";//0 
$LANG_details = "Details";//0
$LANG_edit = "Edit";//0
$LANG_edit_view_tooltip = "Edit";//0
$LANG_email = "Email";//0
$LANG_email_quick = "Quick Email";//0
$LANG_export_as = "Export as";//0
$LANG_export_doc_tooltip = "to formato do editor de texto";//1
$LANG_export_pdf = "Export to PDF";//0
$LANG_export_pdf_tooltip = "no formato do PDF";//1
$LANG_export_tooltip = "Exportação";//1 
$LANG_export_xls_tooltip = "no formato do grade";//1
$LANG_faqs = "Frequently Asked Questions";//0
$LANG_fax = "Fax";//1
$LANG_format_tooltip = "formato";//1
$LANG_get_help = "Get Help";//0
$LANG_gross_total = "Gross Total";//1
$LANG_help = "Help";//0
$LANG_hide_details = "Hide details";//0
$LANG_home = "Home";//0
$LANG_id = "ID";//1
$LANG_ie_10_for_10 = "* ie. 10 for 10%";//0
$LANG_included = "included";//0
$LANG_insert_biller = "Insert Biller";//0 
$LANG_insert_customer = "Insert Customer";//0
$LANG_insert_payment_type = "Insert Payment Type";//0
$LANG_insert_preference = "Insert Preference";// 0
$LANG_insert_product = "Insert Product";//0
$LANG_insert_tax_rate = "Insert Tax Rate";//0
$LANG_installation = "Installation";//0
$LANG_inv = "Invoice";//0
$LANG_inv_consulting = " - Consulting";//0 
$LANG_inv_itemised = " - Itemised";//0
$LANG_inv_pref = "Invoice Preference";//0
$LANG_inv_total = " - Total";//0
$LANG_invoice = "Invoice";//0
$LANG_invoice_detail_heading = "Invoice detail heading";//0
$LANG_invoice_detail_line = "Invoice detail line";//0
$LANG_invoice_footer = "Invoice footer";//0
$LANG_invoice_heading = "Invoice heading";//0
$LANG_invoice_id = "Invoice ID";//0
$LANG_invoice_listings = "Invoice listing";//0
$LANG_invoice_payment_line_1_name = "Invoice payment line 1 name";//0
$LANG_invoice_payment_line_1_value = "Invoice payment line 1 value";//0
$LANG_invoice_payment_line_2_name = "Invoice payment line 2 name";//0
$LANG_invoice_payment_line_2_value = "Invoice payment line 2 value";//0
$LANG_invoice_payment_method = "Invoice payment method";//0
$LANG_invoice_preference_to_add = "Invoice preference to add";//0
$LANG_invoice_preferences = "Invoice Preferences";//0
$LANG_invoice_summary = "Invoice Summary";//0
$LANG_invoice_type = "o Tipo";//1
$LANG_invoice_wording = "Invoice wording";//0
$LANG_invoices = "Invoices";//0
$LANG_item = "Item";//0
$LANG_itemised = "Itemised";//0
$LANG_itemised_style = "Itemised style";//0
$LANG_license = "License";//0
$LANG_login = "Log in";//0
$LANG_logo_file = "Logo file";//0
$LANG_logout = "Log out";//0
$LANG_manage = "Manage";//0
$LANG_manage_billers = "Manage Billers";//0
$LANG_manage_custom_fields = "Manage Custom Fields";//0
$LANG_manage_customers = "Edite os Cilentes";//1
$LANG_manage_invoices = "Edite os Facturas";//1
$LANG_manage_invoice_preferences = "Manage Invoice Preferences";//0
$LANG_manage_payment_types = "Manage Payment Types";//0 
$LANG_manage_preferences = "Edite os Opções";//1
$LANG_manage_payments = "Manage Payments";//1
$LANG_manage_products = "Edite os Produtos";//1
$LANG_manage_tax_rates = "Manage Tax Rates";//0
$LANG_mandatory_fields = "All fields are mandatory";//0
$LANG_mobile_phone = "Mobile Phone";//0
$LANG_mobile_short = "Mob.";//0 
$LANG_new_invoice_consulting = "New Invoices - Consulting";//0
$LANG_new_invoice_itemised = "New Invoices - Itemised";//0
$LANG_new_invoice_total = "New Invoices - Total";//0
$LANG_no_customers = "Não há nenhum cliente nos registros";//1
$LANG_no_invoices = "Não há nenhum factura nos registros";//1
$LANG_no_payment_types = "Sorry, no payment types available, please insert one";//0
$LANG_no_preferences = "Não há nenhum opção nos registros";//1
$LANG_no_tax_rates = "There are no tax rates in the database";//1
$LANG_note = "Note";//0
$LANG_notes = "Notes";//0
$LANG_notes_opt = "Notes (optional)";//0
$LANG_number_short = "No.";//0 
$LANG_owing = "Owing";//0
$LANG_optional = "optional";//0
$LANG_options = "Options";//0
$LANG_paid = "Paid";//0
$LANG_payment_type = "Payment Type";//0
$LANG_payment_type_description = "Payment type description";//0
$LANG_payment_type_details = "Payment Type Details";//0
$LANG_payment_type_edit = "Edit Payment Type";//0
$LANG_payment_type_id = "Payment Type ID";//0
$LANG_payment_type_method = "Payment Type/Method";//0
$LANG_payment_type_to_add = "Payment type to add";//0
$LANG_payment_types = "Payment Types";//0
$LANG_payments = "Payments";//0
$LANG_phone = "Phone";//0
$LANG_phone_short = "Ph.";//0
$LANG_preference_id = "Número do Opção";//1
$LANG_prepare_simple_invoices = "Prepare Simple Invoices for use";//0
$LANG_print_preview = "Print Preview";//0
$LANG_print_preview_tooltip = "Inspecção Prévia de Cópia de";//1
$LANG_process_payment = "Process Payment";//0
$LANG_process_payment_for = "Process Payment for";//0
$LANG_product = "Product";//0 
$LANG_product_description = "Product Description";//0
$LANG_product_edit = "Edit Product";//0
$LANG_product_enabled = "Product Enabled";//0
$LANG_product_id = "Product ID";//0
$LANG_product_sales = "Product sales";//0
$LANG_product_to_add = "Product to add";//0
$LANG_product_unit_price = "Product Unit Price";//0
$LANG_products = "Products";//0
$LANG_products_by_customer = "Products by customer";//0
$LANG_products_sold_customer_total = "Products sold - Customer - Total";//0
$LANG_products_sold_total = "Products sold - total";//0
$LANG_provision_of = "Provision of";//0
$LANG_quick_view_of = "This is a Quick View of";//0
$LANG_quick_view_tooltip = "Inspecção Prévia de";//1 
$LANG_reports = "Reports";//1
$LANG_sales = "Sales";//0
$LANG_sales_by_customers = "Sales by customers";//0
$LANG_sanity_check = "Sanity check of invoices";//0
$LANG_save = "Save";//0
$LANG_save_custom_field = "Save Custom Field";//0
$LANG_save_payment_type = "Save Payment Type";//0
$LANG_save_product = "Save Product";//0
$LANG_save_tax_rate = "Save Tax Rate";//0
$LANG_select_invoice = "Please select an invoice";//0
$LANG_show_details = "Show details";//0
$LANG_state = "State";//0
$LANG_street = "Street";//0
$LANG_street2 = "Street address 2";//0
$LANG_sub_total = "Sub Total";//0
$LANG_sum = "Sum";//0
$LANG_summary = "Summary";//0
$LANG_summary_of_accounts = "Summary of accounts";//0
$LANG_system_defaults = "System Defaults";//0
$LANG_tax = "Tax";//0
$LANG_tax_description = "Tax description";// 0
$LANG_tax_id = "Tax ID";// 0 
$LANG_tax_percentage = "Tax Percentage";//0
$LANG_tax_rate = "Tax Rate";//0
$LANG_tax_rate_details = "Tax rate details";//0
$LANG_tax_rate_id = "Tax Rate ID";//0
$LANG_tax_rate_to_add = "Tax rate to add";//0
$LANG_tax_rates = "Tax Rates";//0
$LANG_tax_total = "Total tax included";//0
$LANG_telephone_short = "Tel";//0
$LANG_total = "Total";//0
$LANG_total_amount = "Total amount";//0
$LANG_total_by_aging_periods = "Total by Aging periods";//0
$LANG_total_invoices = "Total Invoices";//0
$LANG_total_owed_per_customer = "Total owed per customer"; //0
$LANG_total_owing = "Total Owing";//0
$LANG_total_paid = "Total Paid";//0
$LANG_total_sales = "Total Sales";//0
$LANG_total_sales_by_customer = "Total Sales by Customer";//0
$LANG_total_style = "Total style";//0
$LANG_total_taxes = "Total taxes";//0
$LANG_total_uppercase = "TOTAL";//0
$LANG_totals = "Totals";//0
$LANG_unit_price = "Unit Price";//0
$LANG_upgrading_simple_invoices = "Upgrading Simple Invoices";//0
$LANG_using_simple_invoices = "Using Simple Invoices";//0
$LANG_view = "View";//0
$LANG_quantity = "Quantity";//0
$LANG_quantity_short = "Qty";//0
$LANG_want_more_fields = "want more fields";//0
$LANG_welcome = "Welcome to ";//0
$LANG_zip = "Zip code";//0


#Index.php - front page


$LANG_shortcut ="Shortcut menu";//0

$LANG_getting_started ="Getting started";//0
$LANG_faqs_what ="What is Simple Invoices?";//0
$LANG_faqs_need ="What do I need to start invoicing?";//0
$LANG_faqs_how ="How do I create invoices?";//0
$LANG_faqs_type ="What are the different types of invoices?";//0

$LANG_create_invoice ="Create an invoice";//0

$LANG_manage_existing_invoice ="Manage your existing invoices";//0
$LANG_manage_invoices ="Manage invoices";//0

$LANG_manage_data ="Manage your data";//0
$LANG_insert_customer = "Add Customer";//0
$LANG_insert_biller = "Add Biller";//0
$LANG_insert_product = "Add Product";//1

$LANG_options ="Options";//0

$LANG_stats =" Quick reports";//0
$LANG_stats_debtor ="Largest debtor";//0
$LANG_stats_customer ="Top Customer - by amount invoiced";//0
$LANG_stats_biller ="Top Biller - by amount invoiced";//0

/* Dont translate anything below here */




#Manage Invoices
$mi_page_title = " - Edite os Facturas";//1
$mi_page_header = "Edite os Facturas";//1
$mi_no_invoices = "Não há nenhum factura nos registros";//1
$mi_table_action = "Ação";//1
$mi_table_id = "Nome";//1
$mi_table_biller = "o Pagamento";//1
$mi_table_customer = "o Cliente";//1
$mi_table_total = "o Total";//1
$mi_table_paid = "Paid";//1
$mi_table_owing = "Owing";//1
$mi_table_type = "o Tipo";//1
$mi_table_date = "Data da criação";//1
$mi_actions_quick_view = "Inspecção Prévia";//1
$mi_actions_quick_view_tooltip = "Inspecção Prévia de";//1 
$mi_actions_edit_view = "edit";//1
$mi_actions_edit_view_toolkit = "Edit";//1
$mi_actions_print_preview_tooltip = "Inspecção Prévia de Cópia de";//1
$mi_actions_export_tooltip = "Exportação";//1 
$mi_actions_export_pdf_tooltip = "no formato do PDF";//1
$mi_actions_format_tooltip = "formato";//1
$mi_actions_export_xls_tooltip = "no formato do grade";//1
$mi_actions_export_doc_tooltip = "to formato do editor de texto";//1
$mi_actions_process_payment = "Process payment for";//1
$mi_action_invoice_total = "Add new Invoice - Total style";//1
$mi_action_invoice_itemised = "Add new Invoice - Itemised style";//1
$mi_action_invoice_consulting = "Add new Invoice - Consulting style";//1

#Manage Products
$mp_page_title = " - Edite os Produtos";//1
$mp_page_header = "Edite os Produtos";//1
$mp_no_invoices = "Não há nenhum produto nos registros";//1
$mp_table_action = "Ação";//1
$mp_table_product_id = "Número do Produto";//1
$mp_table_product_desc = "Descrição do Produto";//1
$mp_table_unit_price = "o Preço";//1
$mp_actions_view = "Veja";//1
$mp_actions_edit = "Edite";//1
$mp_actions_new_product = "Add New Product";//1

#Manage Billers
$mb_page_title = " - Edite os Pagamentos";//1
$mb_page_header = "Edite os Pagamentos";//1
$mb_no_invoices = "Não há nenhum produto nos registros";//1
$mb_table_action = "Ação";//1
$mb_table_biller_id = "Número de Pagamento";//1
$mb_table_biller_name = "Nome de Pagamento";//1
$mb_table_phone = "Número de Telefone";//1
$mb_table_mobile_phone = "Número de Telefone Celular";//1
$mb_table_email = "E-mail";//1
$mb_actions_view = "Veja";//1
$mb_actions_edit = "Edite";//1
$mb_actions_new_biller = "Add New Biller";//1

#Manage Customers
$mc_page_title = " - Edite os Cilentes";//1
$mc_page_header = "Edite os Cilentes";//1
$mc_no_invoices = "Não há nenhum cliente nos registros";//1
$mc_table_action = "Ação";//1
$mc_table_customer_id = "Número de Cliente";//1
$mc_table_customer_name = "Nome de Cliente";//1
$mc_table_attention = "Atenção";//1
$mc_table_phone = "Número de Telefone";//1
$mc_table_email = "E-mail";//1
$mc_actions_view = "Veja";//1
$mc_actions_edit = "Edite";//1
$mc_actions_new_product = "Add New Customer";//1

#Manage Preferences
$mip_page_title = " - Edite os Opções";//1
$mip_page_header = "Edite os Opções";//1
$mip_no_invoices = "Não há nenhum opção nos registros";//1
$mip_table_action = "Ação";//1
$mip_table_preference_id = "Número do Opção";//1
$mip_table_description = "Descrição";//1
$mip_actions_view = "Veja";//1
$mip_actions_edit = "Edite";//1
$mip_actions_new_preference = "Add New Invoice Preference";//1

#Manage System Defautls.php
$msd_default_number_items = "Número normal dos artigos na factura:";//1
$msd_js_alert_def_inv_template = "Seu Molde do factura ";//1
$msd_def_inv_template = "Seu Molde do factura ";//1
$msd_no_tax = "Não há um imposto nos registros.  Adicione um imposto.";//1
$msd_no_payment_type = "Sorry, no payment type available, please insert one";//1
$msd_tax = "Imposto";//1
$msd_payment_type = "Payment Type";//1
$msd_invoice_preference = "Opção da Factura";//1
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

#Manage Tax Rates
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
