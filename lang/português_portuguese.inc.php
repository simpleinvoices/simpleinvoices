<?php

#all
$title = "Facturas Simples";
$wording_for_enabledField ="Enabled";
$wording_for_disabledField ="Disabled";



#Index.php - front page
$indx_manage_invoices = "Edite as Facturas";
$indx_invoice_itemised = "Factura - Especificado";
$indx_invoice_total = "Factura - Total";
$indx_invoice_consulting = "Factura - Consultar";
$indx_insert_customer = "Adicione um Cliente";
$indx_insert_biller = "Adicione um Pagamento";
$indx_insert_product = "Adicione um Produto";
$indx_manage_customers = "Edite os Clientes";
$indx_manage_billers = "Edite os Pagamentos";
$indx_manage_products = "Edite os Produtos";

#Manage Invoices
$mi_page_title = " - Edite os Facturas";
$mi_page_header = "Edite os Facturas";
$mi_no_invoices = "Não há nenhum factura nos registros";
$mi_table_action = "Ação";
$mi_table_id = "Nome";
$mi_table_biller = "o Pagamento";
$mi_table_customer = "o Cliente";
$mi_table_total = "o Total";
$mi_table_paid = "Paid";
$mi_table_owing = "Owing";
$mi_table_type = "o Tipo";
$mi_table_date = "Data da criação";
$mi_actions_quick_view = "Inspecção Prévia";
$mi_actions_quick_view_tooltip = "Inspecção Prévia de"; 
$mi_actions_edit_view = "edit";
$mi_actions_edit_view_toolkit = "Edit";
$mi_actions_print_preview_tooltip = "Inspecção Prévia de Cópia de";
$mi_actions_export_tooltip = "Exportação"; 
$mi_actions_export_pdf_tooltip = "no formato do PDF";
$mi_actions_format_tooltip = "formato";
$mi_actions_export_xls_tooltip = "no formato do grade";
$mi_actions_export_doc_tooltip = "to formato do editor de texto";
$mi_actions_process_payment = "Process payment for";
$mi_action_invoice_total = "Add new Invoice - Total style";
$mi_action_invoice_itemised = "Add new Invoice - Itemised style";
$mi_action_invoice_consulting = "Add new Invoice - Consulting style";


#Manage Products
$mp_page_title = " - Edite os Produtos";
$mp_page_header = "Edite os Produtos";
$mp_no_invoices = "Não há nenhum produto nos registros";
$mp_table_action = "Ação";
$mp_table_product_id = "Número do Produto";
$mp_table_product_desc = "Descrição do Produto";
$mp_table_unit_price = "o Preço";
$mp_actions_view = "Veja";
$mp_actions_edit = "Edite";
$mp_actions_new_product = "Add New Product";

#Manage Billers
$mb_page_title = " - Edite os Pagamentos";
$mb_page_header = "Edite os Pagamentos";
$mb_no_invoices = "Não há nenhum produto nos registros";
$mb_table_action = "Ação";
$mb_table_biller_id = "Número de Pagamento";
$mb_table_biller_name = "Nome de Pagamento";
$mb_table_phone = "Número de Telefone";
$mb_table_mobile_phone = "Número de Telefone Celular";
$mb_table_email = "E-mail";
$mb_actions_view = "Veja";
$mb_actions_edit = "Edite";
$mb_actions_new_biller = "Add New Biller";

#Manage Customers
$mc_page_title = " - Edite os Cilentes";
$mc_page_header = "Edite os Cilentes";
$mc_no_invoices = "Não há nenhum cliente nos registros";
$mc_table_action = "Ação";
$mc_table_customer_id = "Número de Cliente";
$mc_table_customer_name = "Nome de Cliente";
$mc_table_attention = "Atenção";
$mc_table_phone = "Número de Telefone";
$mc_table_email = "E-mail";
$mc_actions_view = "Veja";
$mc_actions_edit = "Edite";
$mc_actions_new_product = "Add New Customer";

#Manage Preferences
$mip_page_title = " - Edite os Opções";
$mip_page_header = "Edite os Opções";
$mip_no_invoices = "Não há nenhum opção nos registros";
$mip_table_action = "Ação";
$mip_table_preference_id = "Número do Opção";
$mip_table_description = "Descrição";
$mip_actions_view = "Veja";
$mip_actions_edit = "Edite";
$mip_actions_new_preference = "Add New Invoice Preference";

#Manage System Defautls.php
$msd_default_number_items = "Número normal dos artigos na factura:";
$msd_js_alert_def_inv_template = "Seu Molde do factura ";
$msd_def_inv_template = "Seu Molde do factura ";
$msd_no_tax = "Não há um imposto nos registros.  Adicione um imposto.";
$msd_no_payment_type = "Sorry, no payment type available, please insert one";
$msd_tax = "Imposto";
$msd_payment_type = "Payment Type";
$msd_invoice_preference = "Opção da Factura";
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
$pp_invoice_items ="Items";
$pp_invoice_note ="Note";
$pp_invoice_total_tax ="Total tax included";
$pp_invoice_amount ="Amount";
$pp_invoice_summary ="Summary";



?>


