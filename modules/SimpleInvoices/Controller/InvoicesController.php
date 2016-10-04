<?php
namespace SimpleInvoices\Controller;

/**
 * @author Juan Pedro Gonzalez Gutierrez
 */
class InvoicesController
{
    protected $smarty;

    public function __construct()
    {
        global $smarty;

        $this->smarty = $smarty;
    }
    
    public function addInvoiceItemAction()
    {
        $products = null;
        
        if(isset($_POST['submit'])) {
            insertInvoiceItem(
                $_POST['id'],
                $_POST['quantity1'],
                $_POST['product1'],
                $_POST['tax_id'],
                trim($_POST['description']),
                $_POST['unit_price1']
            );
        } else {
            $products = getActiveProducts();
        }
        
        $type = $_GET[type];
        
        $this->smarty->assign("products", $products);
        $this->smarty->assign("type", $type);
        $this->smarty->assign('pageActive', 'invoice');
        $this->smarty->assign('active_tab', '#money');
    }
    
    public function consultingAction()
    {
        jsBegin();
        jsFormValidationBegin("frmpost");
        jsTextValidation("biller_id","Biller Name",1,1000000);
        jsTextValidation("customer_id","Customer Name",1,1000000);
        jsValidateifNumZero("i_quantity0","Quantity");
        jsValidateifNum("i_quantity0","Quantity");
        jsValidateRequired("select_products0","Product");
        jsTextValidation("select_tax","Tax Rate",1,100);
        jsPreferenceValidation("select_preferences","Invoice Preference",1,1000000);
        jsFormValidationEnd();
        jsEnd();
        
        $this->invoiceAction();
        
        $this->smarty->assign('pageActive', 'invoice_new');
        $this->smarty->assign('active_tab', '#money');
    }
    
    public function deleteAction()
    {
        global $dbh;
        
        $invoice_id   = $_GET['id'];
        $invoice      = getInvoice($invoice_id);
        $preference   = getPreference($invoice['preference_id']);
        $defaults     = getSystemDefaults();
        $invoicePaid  = calc_invoice_paid($invoice_id);
        $invoiceobj   = new \invoice();
        $invoiceItems = $invoiceobj->getInvoiceItems($invoice_id);
        
        /*If delete is disabled - dont allow people to view this page*/
        if ( $defaults['delete'] == 'N' ) {
            die('Invoice deletion has been disabled, you are not supposed to be here');
        }
        
        if ( ($_GET['stage'] == 2 ) AND ($_POST['doDelete'] == 'y') ) {
            $dbh->beginTransaction();
            $error = false;
        
            //delete line item taxes
            $invoiceobj         = new \invoice();
            $invoice_line_items = $invoiceobj->getInvoiceItems($invoice_id);
        
            foreach( $invoice_line_items as $key => $value)
            {
                //echo "line item id: ".$invoice_line_items[$key]['id']."<br />";
                delete('invoice_item_tax','invoice_item_id',$invoice_line_items[$key]['id']);
            }
        
            // Start by deleting the line items
            if (! delete('invoice_items','invoice_id',$invoice_id)) {
                $error = true;
            }
        
            //delete products from products table for total style
            if ($invoice['type_id'] == 1)
            {
                if ($error || ! delete('products','id',$invoiceItems['0']['product']['id'])) {
                    $error = true;
                }
            }
        
            //delete the info from the invoice table
            if ($error || ! delete('invoices','id',$invoice_id)) {
                $error = true;
            }
            if ($error) {
                $dbh->rollBack();
            } else {
                $dbh->commit();
            }
            
            //TODO - what about the stuff in the products table for the total style invoices?
            echo "<meta http-equiv='refresh' content='2;URL=index.php?module=invoices&view=manage' />";        
        }
        
        $this->smarty->assign("invoice",$invoice);
        $this->smarty->assign("preference",$preference);
        $this->smarty->assign("defaults",$defaults);
        $this->smarty->assign("invoicePaid",$invoicePaid);
        $this->smarty->assign("invoiceItems",$invoiceItems);
        $this->smarty->assign('pageActive', 'invoice');
        $this->smarty->assign('active_tab', '#money');
    }
    
    public function detailsAction()
    {
        $master_invoice_id = $_GET['id'];
        $invoice           = getInvoice($master_invoice_id);
        $invoiceobj        = new \invoice();
        $invoiceItems      = $invoiceobj->getInvoiceItems($master_invoice_id);
        $customers         = getActiveCustomers();
        $preference        = getPreference($invoice['preference_id']);
        $billers           = getActiveBillers();
        $defaults          = getSystemDefaults();
        $taxes             = getTaxes();
        $preferences       = getActivePreferences();
        $products          = getActiveProducts();
        
        for($i=1;$i<=4;$i++) {
            $customFields[$i] = show_custom_field("invoice_cf$i",$invoice["custom_field$i"],"write",'',"details_screen",'','','');
        }
        
        foreach($invoiceItems as $key=>$value)
        {
            //get list of attributes
            $prod     = getProduct($value['product_id']);
            $json_att = json_decode($prod['attribute']);
            if($json_att !== null)
            {
                $html = "<tr id='json_html". $key."'><td></td><td colspan='5'><table><tr>";
                foreach($json_att as $k=>$v)
                {
                    if($v == 'true')
                    {
                        $attr_name_sql = sprintf('select
                    a.name as name, a.enabled as enabled,  t.name type
                    from
                        si_products_attributes as a,
                        si_products_attribute_type as t
                   where
                        a.type_id = t.id
                        AND a.id = %d', $k);
                        $attr_name = dbQuery($attr_name_sql);
                        $attr_name = $attr_name->fetch();
        
                        $sql2 = sprintf('select
                        a.name as name,
                        v.id as id,
                        v.value as value,
                        v.enabled as enabled
                   from
                        si_products_attributes a,
                        si_products_values v
                   where
                        a.id = v.attribute_id
                        AND a.id = %d', $k);
                        $states2 = dbQuery($sql2);
        
                        if($attr_name['enabled'] =='1' AND $attr_name['type'] == 'list')
                        {
                            $html .= "<td>".$attr_name['name']."<select name='attribute[".$key."][".$k."]'>";
                            $html .= "<option value=''></option>";
                            foreach($states2 as $att_key=>$att_val)
                            {
                                if($att_val['enabled'] == '1')
                                {
                                    foreach ($value['attribute_decode'] as $a_key => $a_value)
                                    {
                                        if($k == $a_key AND $a_value == $att_val['id'])
                                        {
                                            $selected = "selected";
                                            break;
                                        } else {
                                            $selected = "";
                                        }
                                    }
        
                                    $html .= "<option ". $selected ." value='". $att_val['id']. "'>".$att_val['value']."</option>";
                                }
                            }
                            $html .= "</select></td>";
                        }
                        if($attr_name['enabled'] =='1' AND $attr_name['type'] == 'free'  )
                        {
                            $attribute_value ='';
                            foreach ($value['attribute_decode'] as $a_key => $a_value)
                            {
                                if($k == $a_key){ $attribute_value = $a_value;}
                            }
                            $html .= "<td>".$attr_name['name']."<input name='attribute[".$key."][".$k."]'  value='". $attribute_value ."' /></td>";
                        }
                        if($attr_name['enabled'] =='1' AND $attr_name['type'] == 'decimal' )
                        {
                            $attribute_value ='';
                            foreach ($value['attribute_decode'] as $a_key => $a_value)
                            {
                                if($k == $a_key){ $attribute_value = $a_value;}
                            }
                            $html .= "<td>".$attr_name['name']."<input name='attribute[".$key."][".$k."]' size='5' value='". $attribute_value ."' /></td>";
                        }
                    }
                }
                $html .= "</tr></table></td></tr>";
                $invoiceItems[$key]['html'] = $html;
            }
        }
        
        $this->smarty->assign("invoice",$invoice);
        $this->smarty->assign("defaults",$defaults);
        $this->smarty->assign("invoiceItems",$invoiceItems);
        $this->smarty->assign("customers",$customers);
        $this->smarty->assign("preference",$preference);
        $this->smarty->assign("billers",$billers);
        $this->smarty->assign("taxes",$taxes);
        $this->smarty->assign("preferences",$preferences);
        $this->smarty->assign("products",$products);
        $this->smarty->assign("customFields",$customFields);
        $this->smarty->assign("lines",count($invoiceItems));
        $this->smarty->assign('pageActive', 'invoice');
        $this->smarty->assign('subPageActive', 'invoice_edit');
        $this->smarty->assign('active_tab', '#money');
    }
    
    public function emailAction()
    {
        $invoice_id    = $_GET['id'];
        $invoiceobj    = new \invoice();
        $invoice       = $invoiceobj->select($invoice_id);
        $preference    = getPreference($invoice['preference_id']);
        $biller        = getBiller($invoice['biller_id']);
        $customer      = getCustomer($invoice['customer_id']);
        $invoiceType   = getInvoiceType($invoice['type_id']);
        
        #create PDF name
        $spc2us_pref   = str_replace(" ", "_", $invoice['index_name']);
        $pdf_file_name = $spc2us_pref  . '.pdf';
        
        if ($_GET['stage'] == 2 ) {
            // Create invoice
            $export                = new \export();
            $export->format        = "pdf";
            $export->file_location = 'file';
            $export->module        = 'invoice';
            $export->id            = $invoice_id;
            $export->execute();
        
            #$attachment = file_get_contents('./tmp/cache/' . $pdf_file_name);
        
            $email                = new \email();
            $email->format        = 'invoice';
            $email->notes         = $_POST['email_notes'];
            $email->from          = $_POST['email_from'];
            $email->from_friendly = $biller['name'];
            $email->to            = $_POST['email_to'];
            $email->bcc           = $_POST['email_bcc'];
            $email->subject       = $_POST['email_subject'];
            $email->attachment    = $pdf_file_name;
            $message              = $email -> send ();
        } else if ($_GET['stage'] == 3 ) {
            $message              = "How did you get here :)";
        }
        
        $this->smarty->assign('message', $message);
        $this->smarty->assign('biller',$biller);
        $this->smarty->assign('customer',$customer);
        $this->smarty->assign('invoice',$invoice);
        $this->smarty->assign('preferences',$preference);
        $this->smarty->assign('pageActive', 'invoice');
        $this->smarty->assign('active_tab', '#money');
    }
    
    public function itemisedAction()
    {
        global $logger;
        
        $pageActive = "invoices";
        
        $logger->log('Itemised invoice created', \Zend_Log::INFO);
        
        $this->invoiceAction();
        
        $this->smarty->assign('pageActive', 'invoice_new');
        $this->smarty->assign('subPageActive', 'invoice_new_itemised');
        $this->smarty->assign('active_tab', '#money');
    }
    
    public function productAjaxAction()
    {
        global $auth_session;
        
        $row_id = htmlsafe($_GET['row']);
        
        if($_GET['id'])
        {
            //sleep(2);
            $sql    = sprintf("SELECT unit_price, default_tax_id, default_tax_id_2,attribute,notes,notes_as_description,show_description FROM ".TB_PREFIX."products WHERE id = %d AND domain_id = %d LIMIT 1", $_GET['id'], $auth_session->domain_id);
            $states = dbQuery($sql);
        
            if($states->rowCount() > 0)
            {
                $row      = $states->fetch();
                $json_att = json_decode($row['attribute']);
                
                if($json_att !== null AND $row['attribute'] !== '[]')
                {
                    $html ="<tr id='json_html". $row_id ."'><td></td><td colspan='5'><table><tr>";
                    foreach($json_att as $k=>$v)
                    {
                        if($v == 'true')
                        {
                            $attr_name_sql = sprintf('select
                    a.name as name, a.enabled as enabled,  t.name type
                    from
                        si_products_attributes as a,
                        si_products_attribute_type as t
                   where
                        a.type_id = t.id
                        AND a.id = %d', $k);
                            $attr_name = dbQuery($attr_name_sql);
                            $attr_name = $attr_name->fetch();
        
                            $sql2 = sprintf("select a.name as name, v.id as id, v.value as value, v.enabled as enabled from ".TB_PREFIX."products_attributes a, ".TB_PREFIX."products_values v where a.id = v.attribute_id AND a.id = %d", $k);
                            $states2 = dbQuery($sql2);
        
                            if($attr_name['enabled'] =='1' AND $attr_name['type'] == 'list')
                            {
                                $html .= "<td>".$attr_name['name']."<select name='attribute[".$row_id."][".$k."]'>";
                                foreach($states2 as $att_key=>$att_val)
                                {
                                    if($att_val['enabled'] == '1')
                                    {
                                        $html .= "<option value='". $att_val['id']. "'>".$att_val['value']."</option>";
                                    }
                                }
                                $html .= "</select></td>";
                            }
                            if($attr_name['enabled'] =='1' AND $attr_name['type'] == 'free'  )
                            {
                                $html .= "<td>".$attr_name['name']."<input name='attribute[".$row_id."][".$k."]' /></td>";
                            }
                            if($attr_name['enabled'] =='1' AND $attr_name['type'] == 'decimal' )
                            {
                                $html .= "<td>".$attr_name['name']."<input name='attribute[".$row_id."][".$k."]' size='5'/></td>";
                            }
                        }
                    }
                    $html .= "</tr></table></td></tr>";
                }
                
                /*Format with decimal places with precision as defined in config.php*/
                $output['unit_price']           = \siLocal::number_clean($row['unit_price']);
                $output['default_tax_id']       = $row['default_tax_id'];
                $output['default_tax_id_2']     = $row['default_tax_id_2'];
                $output['attribute']            = $row['attribute'];
                $output['json_attribute']       = $json_att;
                $output['json_html']            = $html;
                $output['notes']                = $row['notes'];
                $output['notes_as_description'] = $row['notes_as_description'];
                $output['show_description']     = $row['show_description'];
            }else {
                $output .= '';
            }
        
            echo json_encode($output);
        
            exit();
        } else {
            echo "";
        }
    }
    
    public function productConsultingAction()
    {
        jsBegin();
        jsFormValidationBegin("frmpost");
        jsTextValidation("biller_id","Biller Name",1,1000000);
        jsTextValidation("customer_id","Customer Name",1,1000000);
        jsValidateifNumZero("i_quantity0","Quantity");
        jsValidateifNum("i_quantity0","Quantity");
        jsValidateRequired("select_products0","Product");
        jsTextValidation("select_tax","Tax Rate",1,100);
        jsPreferenceValidation("select_preferences","Invoice Preference",1,1000000);
        jsFormValidationEnd();
        jsEnd();
        
        $pageActive = "invoices";
        
        $this->invoiceAction();
    }
    
    public function productInventoryAjaxAction()
    {
        global $auth_session;
        
        if($_GET['id'])
        {
            //sleep(2);
            $sql    = sprintf("SELECT cost FROM ".TB_PREFIX."products WHERE id = %d AND domain_id = %d LIMIT 1", $_GET['id'], $auth_session->domain_id);
            $states = dbQuery($sql);
            //	$output = '';
            if($states->rowCount() > 0)
            {
                $row = $states->fetch();
        
                /*Format with decimal places with precision as defined in config.php*/
                $output['cost'] = \siLocal::number_formatted($row['cost']);
            } else {
                $output .= '';
            }
        
            echo json_encode($output);
        
            exit();
        } else {
            echo "";
        }
    }
    
    public function quickViewAction()
    {
        global $LANG;
        
        $invoice_id              = $_GET['id'];
        $invoice                 = getInvoice($invoice_id);
        $invoice_number_of_taxes = numberOfTaxesForInvoice($invoice_id);
        $invoice_type            =  getInvoiceType($invoice['type_id']);
        $customer                = getCustomer($invoice['customer_id']);
        $biller                  = getBiller($invoice['biller_id']);
        $preference              = getPreference($invoice['preference_id']);
        $defaults                = getSystemDefaults();
        
        $invoiceobj              = new \invoice();
        $invoiceItems            = $invoiceobj->getInvoiceItems($invoice_id);
        
        $eway_check              = new \eway();
        $eway_check->invoice     = $invoice;
        $eway_pre_check          = $eway_check->pre_check();
        
        #Invoice Age - number of days - start
        if ($invoice['owing'] > 0 ) {
            $invoice_age_days =  number_format((strtotime(date('Y-m-d')) - strtotime($invoice['calc_date'])) / (60 * 60 * 24),0);
            $invoice_age      = "$invoice_age_days {$LANG['days']}";
        } else {
            $invoice_age ="";
        }
        
        $url_for_pdf            = "./index.php?module=export&view=pdf&id=" . $invoice['id'];
        $invoice['url_for_pdf'] = $url_for_pdf;
        $customFieldLabels      = getCustomFieldLabels();
        
        for($i=1;$i<=4;$i++) {
            $customField[$i] = show_custom_field("invoice_cf$i",$invoice["custom_field$i"],"read",'summary', '','',5,':');
        }
        
        $sql                      = "SELECT * FROM ".TB_PREFIX."products_attributes";
        $sth                      =  dbQuery($sql);
        $attributes               = $sth->fetchAll();
        
        //Customer accounts sections
        $customerAccount          = null;
        $customerAccount['total'] = calc_customer_total($customer['id'],'',true);
        $customerAccount['paid']  = calc_customer_paid($customer['id'],'',true);
        $customerAccount['owing'] = $customerAccount['total'] - $customerAccount['paid'];
        
        $this->smarty->assign("attributes", $attributes);
        $this->smarty->assign('pageActive', 'invoice');
        $this->smarty->assign('subPageActive', 'invoice_view');
        $this->smarty->assign('active_tab', '#money');
        $this->smarty->assign("customField",$customField);
        $this->smarty->assign("customFieldLabels",$customFieldLabels);
        $this->smarty->assign("invoice_age",$invoice_age);
        $this->smarty->assign("invoice_number_of_taxes",$invoice_number_of_taxes);
        $this->smarty->assign("invoiceItems",$invoiceItems);
        $this->smarty->assign("defaults",$defaults);
        $this->smarty->assign("preference",$preference);
        $this->smarty->assign("biller",$biller);
        $this->smarty->assign("customer",$customer);
        $this->smarty->assign("invoice_type",$invoice_type);
        $this->smarty->assign("invoice",$invoice);
        $this->smarty->assign("wordprocessor",$config->export->wordprocessor);
        $this->smarty->assign("spreadsheet",$config->export->spreadsheet);
        $this->smarty->assign("customerAccount",$customerAccount);
        $this->smarty->assign("eway_pre_check",$eway_pre_check);
    }
    
    public function invoiceAction()
    {
        $first_run_wizard = false;
        $billers          = getActiveBillers();
        $customers        = getActiveCustomers();
        $taxes            = getActiveTaxes();
        $products         = getActiveProducts();
        $preferences      = getActivePreferences();
        $defaults         = getSystemDefaults();
        
        if ($billers == null OR $customers == null OR $taxes == null OR $products == null OR $preferences == null)
        {
            $first_run_wizard = true;
        }
        
        $defaults['biller']     = (isset($_GET['biller']))     ? $_GET['biller']     : $defaults['biller'];
        $defaults['customer']   = (isset($_GET['customer']))   ? $_GET['customer']   : $defaults['customer'];
        $defaults['preference'] = (isset($_GET['preference'])) ? $_GET['preference'] : $defaults['preference'];
        $defaultTax             = getDefaultTax();
        $defaultPreference      = getDefaultPreference();
        
        if (!empty( $_GET['line_items'] )) {
            $dynamic_line_items = $_GET['line_items'];
        } else {
            $dynamic_line_items = $defaults['line_items'] ;
        }
        
        for($i=1;$i<=4;$i++) {
            $show_custom_field[$i] = show_custom_field("invoice_cf$i",'',"write",'',"details_screen",'','','');
        }
        
        $sql = "SELECT CONCAT(a.id, '-', v.id) as id
			 , CONCAT(a.name, '-',v.value) AS display
		FROM ".TB_PREFIX."products_attributes a
			LEFT JOIN ".TB_PREFIX."products_values v
				ON (a.id = v.attribute_id);";
        $sth    =  dbQuery($sql);
        $matrix = $sth->fetchAll();
        
        $this->smarty->assign("first_run_wizard",$first_run_wizard);
        $this->smarty->assign("matrix", $matrix);
        $this->smarty->assign("billers",$billers);
        $this->smarty->assign("customers",$customers);
        $this->smarty->assign("taxes",$taxes);
        $this->smarty->assign("products",$products);
        $this->smarty->assign("preferences",$preferences);
        $this->smarty->assign("dynamic_line_items",$dynamic_line_items);
        $this->smarty->assign("show_custom_field",$show_custom_field);
        $this->smarty->assign("defaultCustomerID",$defaultCustomerID['id']);
        $this->smarty->assign("defaults",$defaults);
        $this->smarty->assign('active_tab', '#money');
    }
    
    public function manageAction()
    {
        $sql                 = "SELECT count(*) AS count FROM ".TB_PREFIX."invoices WHERE domain_id = :domain_id";
        $sth                 = dbQuery($sql, ':domain_id',\domain_id::get());
        $number_of_invoices  = $sth->fetch(\PDO::FETCH_ASSOC);
        
        $pageActive = "invoice";
        
        $having = "";
        if(isset($_GET['having']))
        {
            $having = "&having=".$_GET['having'];
        }
        $url = 'index.php?module=invoices&view=xml'.$having;
        
        $this->smarty->assign("number_of_invoices", $number_of_invoices);
        $this->smarty->assign('pageActive', $pageActive);
        $this->smarty->assign('active_tab', '#money');
        $this->smarty->assign('url', $url);
    }
    
    public function saveAction()
    {
        global $auth_session;
        global $logger;
        
        if(!isset( $_POST['type']) && !isset($_POST['action'])) {
            exit("no save action");
        }
        
        $saved = false;
        $type  = $_POST['type'];
        
        if ($_POST['action'] == "insert" ) {
            if(insertInvoice($type)) {
                $id = lastInsertId();
                //saveCustomFieldValues($_POST['categorie'],$invoice_id);
                $saved = true;
            }
        
            /*
             * 1 = Total Invoices
             */
            if($type == total_invoice && $saved) {
        
                $logger->log('Total style invoice created, ID: '.$id, \Zend_Log::INFO);
        
                insertProduct(0,0);
                $product_id = lastInsertId();
        
                insertInvoiceItem($id, 1 , $product_id, 1, $_POST['tax_id'][0], $_POST['description'], $_POST['unit_price']);
            } elseif ($saved) {
        
                $logger->log('Max items:'.$_POST['max_items'], \Zend_Log::INFO);
                $i = 0;
                while ($i <= $_POST['max_items']) {
                    $logger->log('i='.$i, \Zend_Log::INFO);
                    $logger->log('qty='.$_POST["quantity$i"], \Zend_Log::INFO);
                    if($_POST["quantity$i"] != null)
                    {
                        insertInvoiceItem($id, $_POST["quantity$i"], $_POST["products$i"], $i, $_POST["tax_id"][$i], $_POST["description$i"], $_POST["unit_price$i"], $_POST["attribute"][$i]);
                    }
                    $i++;
                }
            }
        } elseif ( $_POST['action'] == "edit") {
            //Get type id - so do add into redirector header
            $id = $_POST['id'];
        
            if (updateInvoice($_POST['id'])) {
                //updateCustomFieldValues($_POST['categorie'],$_POST['invoice_id']);
                $saved = true;
            }
        
            if($type == total_invoice && $saved) {
                $logger->log('Total style invoice updated, product ID: '.$_POST['products0'], \Zend_Log::INFO);
                $sql = "UPDATE ".TB_PREFIX."products SET unit_price = :price, description = :description WHERE id = :id AND domain_id = :domain_id";
                dbQuery($sql,
                    ':price', $_POST['unit_price'],
                    ':description', $_POST['description0'],
                    ':id', $_POST['products0'],
                    ':domain_id', $auth_session->domain_id
                    );
            }
        
        
            $logger->log('Max items:'.$_POST['max_items'], \Zend_Log::INFO);
            $i = 0;
            while ($i <= $_POST['max_items'])
            {
                //	for($i=0;(!empty($_POST["quantity$i"]) && $i < $_POST['max_items']);$i++) {
                $logger->log('i='.$i, \Zend_Log::INFO);
                $logger->log('qty='.$_POST["quantity$i"], \Zend_Log::INFO);
                $logger->log('product='.$_POST["products$i"], \Zend_Log::INFO);
        
                if($_POST["delete$i"] == "yes")
                {
                    delete('invoice_items','id',$_POST["line_item$i"]);
                }
                if($_POST["delete$i"] !== "yes")
                {
                    if($_POST["quantity$i"] != null)
                    {
                        //new line item added in edit page
                        if($_POST["line_item$i"] == "")
                        {
                            insertInvoiceItem($id,$_POST["quantity$i"],$_POST["products$i"],$i,$_POST["tax_id"][$i],$_POST["description$i"], $_POST["unit_price$i"],$_POST["attribute"][$i]);
                        }
        
                        if($_POST["line_item$i"] != "")
                        {
                            updateInvoiceItem($_POST["line_item$i"],$_POST["quantity$i"],$_POST["products$i"],$i,$_POST['tax_id'][$i],$_POST["description$i"],$_POST["unit_price$i"],$_POST["attribute"][$i]);
                            //					$saved;
                            // $saved =  true;
                        }
                    }
                }
        
                $i++;
        
            }
        }
        
        $this->smarty->assign('saved', $saved);
        $this->smarty->assign('id', $id);
        $this->smarty->assign('pageActive', 'invoice_new');
        $this->smarty->assign('active_tab', '#money');
    }
    
    public function searchAction()
    {
        $this->smarty->display("../templates/default/menu.tpl");
        $this->smarty->display("../templates/default/main.tpl");
        
        $startdate = (isset($_POST['startdate'])) ? $_POST['startdate'] : date("Y-m-d",strtotime("last Year"));
        $startdate = htmlsafe($startdate);
        $enddate   = (isset($_POST['enddate']))   ? $_POST['enddate']   : date("Y-m-d",strtotime("now"));
        $enddate   = htmlsafe($enddate);
        
        echo <<<EOD
<div style="text-align:left;">
<b>Search Invoice</b><br />
<br />
<b>Search by biller and customer name</b><br />
<form action="index.php?module=invoices&view=search" method="post">
<table width="18%"  border="0">
  <tr>
    <td width="6%"><div align="right">Biller: </div></td>
    <td width="94%"><input type="text" name="biller"></td>
  </tr>
  <tr>
    <td><div align="right">Customer:</div></td>
    <td><input type="text" name="customer"></td>
  </tr>
  <tr>
    <td><div align="right">
      <input type="submit" value="Search">
    </div></td>
    <td>&nbsp;</td>
  </tr>
</table>
        
</form>
<br />
<br />
        
        
<b>Search by date</b>
<form action="index.php?module=invoices&view=search" method="post">
<input type="text" class="date-picker" name="startdate" id="date1" value='$startdate' /><br /><br />
<input type="text" class="date-picker" name="enddate" id="date2" value='$enddate' /><br /><br />
<input type="submit" value="Search">
</form>
<br />
        
EOD;
        
        $sth = null;
        
        if(isset($_POST['biller']) || isset($_POST['customer'])) {
            $sth = searchBillerAndCustomerInvoice($_POST['biller'],$_POST['customer']);
        }
        
        if(isset($_POST['startdate']) && isset($_POST['enddate'])) {
            $sth = searchInvoiceByDate($startdate, $enddate);
        }
        
        if($sth != null) {
            echo "<b>Result</b>";
            echo "<table border=1 cellpadding=2 cellspacing=2>";
            echo "<tr><td>&nbsp;Invoice Number&nbsp;</td><td>&nbsp;Date</td><td>&nbsp;Biller</td><td>&nbsp;Customer</td><td>&nbsp;Type</td></tr>";
            while($res = $sth->fetch()) {
                echo "<tr>";
                echo "<td>&nbsp;<a href='index.php?module=invoices&view=quick_view&invoice=$res[invoice]'>$res[invoice]</a></td>
                <td>&nbsp; $res[date] &nbsp;</td>
                <td>&nbsp; $res[biller] &nbsp;</td>
                <td>&nbsp; $res[customer] &nbsp;</td>
                <td>&nbsp; $res[type] &nbsp;</td>";
                echo "</tr>";
            }
            echo "</table>";
        }
        
        echo "</div>";
        
        $pageActive = "invoices";
        $this->smarty->assign("invoices",$invoices);
        
        //getMenuStructure();
        // till template is made
        exit();
        
        /*
         "Enhancements to Invoice Manage page
         Initially the invoice manage will display blank screen with only options to search. The search criteria could be on the following:
         1. from and To Date
         2. Customer wise
         3. Biller wise
         4. Type
         5. Owing greater than zero
         6. All"*/
    }
    
    public function templateAction()
    {
        // TODO: The file was empty. Shall we remove this action?
    }
    
    public function totalAction()
    {
        $pageActive = "invoices";
        $smarty->assign('pageActive', $pageActive);
        
        $this->invoiceAction();
        
        $smarty->assign('pageActive', 'invoice_new');
        $smarty->assign('subPageActive', 'invoice_new_total');
        $smarty->assign('active_tab', '#money');
    }
    
    public function xmlAction()
    {
        global $LANG;
        global $auth_session;
        
        header("Content-type: text/xml");
        
        //$start = (isset($_POST['start']))     ? $_POST['start']     : "0" ;
        $dir     = (isset($_POST['sortorder'])) ? $_POST['sortorder'] : "DESC" ;
        $sort    = (isset($_POST['sortname']))  ? $_POST['sortname']  : "id" ;
        $rp      = (isset($_POST['rp']))        ? $_POST['rp']        : "25" ;
        $having  = (isset($_GET['having']))     ? $_GET['having']     : "" ;
        $page    = (isset($_POST['page']))      ? $_POST['page']      : "1" ;
        
        //$sql = "SELECT * FROM ".TB_PREFIX."invoices LIMIT $start, $limit";
        $invoice       = new \invoice();
        $invoice->sort = $sort;
        
        if($auth_session->role_name =='customer') {
            $invoice->customer = $auth_session->user_id;
        } elseif ($auth_session->role_name =='biller') {
            $invoice->biller   = $auth_session->user_id;
        }
        
        $invoice->query = isset($_REQUEST['query']) ? $_REQUEST['query'] : null;
        $invoice->qtype = isset($_REQUEST['qtype']) ? $_REQUEST['qtype'] : null;
        
        $large_dataset = getDefaultLargeDataset();
        if($large_dataset == $LANG['enabled'])
        {
            $sth = $invoice->select_all('large', $dir, $rp, $page, $having);
            $sth_count_rows = $invoice->count();
            $invoice_count  = $sth_count_rows->fetch(\PDO::FETCH_ASSOC);
            $invoice_count  = $invoice_count['count'];
        } else {
            $sth            = $invoice->select_all('', $dir, $rp, $page, $having);
            $sth_count_rows = $invoice->select_all('count',$dir, $rp, $page, $having);
            $invoice_count  = $sth_count_rows->rowCount();
        }
        $invoices = $sth->fetchAll(\PDO::FETCH_ASSOC);
        
        $xml ="";
        $xml .= "<rows>";
        $xml .= "<page>$page</page>";
        $xml .= "<total>". $invoice_count ."</total>";
        
        foreach ($invoices as $row) {
            $xml .= "<row id='".$row['id']."'>";
            $xml .= "<cell>
					<![CDATA[<a class='index_table' title='".$LANG['quick_view_tooltip']." ".$row['preference']." ".$row['index_id']."' href='index.php?module=invoices&view=quick_view&id=".$row['id']."'> <img src='images/common/view.png' class='action' /></a>
		<a class='index_table' title='".$LANG['edit_view_tooltip']." ".$row['preference']." ".$row['index_id']."' href='index.php?module=invoices&view=details&id=".$row['id']."&action=view'><img src='images/common/edit.png' class='action' /></a>
		<!--2 Print View -->
			<a class='index_table' title='".$LANG['print_preview_tooltip']." ".$row['preference']." ".$row['index_id']."' href='index.php?module=export&view=invoice&id=".$row['id']."&format=print'>
				<img src='images/common/printer.png' class='action' /><!-- print -->
			</a>
		<!--3 EXPORT DIALOG -->
			<a title='".$LANG['export_tooltip']." ".$row['preference']." ".$row['index_id']."' class='invoice_export_dialog' href='#' rel='".$row['id']."'>
				<img src='images/common/page_white_acrobat.png' class='action' />
			</a>
        
		<!--3 EXPORT DIALOG  onclick='export_invoice(".$row['id'].", \"".$config->export->spreadsheet."\", \"".$config->export->wordprocessor."\");'> -->
		<!--3 EXPORT TO PDF <a title='".$LANG['export_tooltip']." ".$row['preference']." ".$row['index_id']."' class='index_table' href='pdfmaker.php?id=".$row['id']."'><img src='images/common/page_white_acrobat.png' class='action' /></a> -->
		<!--4 XLS <a title='".$LANG['export_tooltip']." ".$row['preference']." ".$row['index_id']." ".$LANG['export_xls_tooltip'].$config->export->spreadsheet." ".$LANG['format_tooltip']."' class='index_table' href='index.php?module=invoices&view=templates/template&invoice='".$row['id']."&action=view&location=print&export=".$config->export->spreadsheet."'><img src='images/common/page_white_excel.png' class='action' /></a> -->
		";
        
            // Alternatively: The Owing column can have the link on the amount instead of the payment icon code here
            if ($row['status'] && $row['owing'] > 0) {
                // Real Invoice Has Owing - Process payment
                $xml .= "<!--6 Payment --><a title='".$LANG['process_payment_for']." ".$row['preference']." ".$row['index_id']."' class='index_table' href='index.php?module=payments&view=process&id=".$row['id']."&op=pay_selected_invoice'><img src='images/common/money_dollar.png' class='action' /></a>";
            } elseif($row['status']) {
                // Real Invoice Payment Details if not Owing (get different color payment icon)
                $xml .= "<!--6 Payment --><a title='".$LANG['process_payment_for']." ".$row['preference']." ".$row['index_id']."' class='index_table' href='index.php?module=payments&view=details&id=".$row['id']."&action=view'><img src='images/common/money_dollar.png' class='action' /></a>";
            } else {
                // Draft Invoice Just Image to occupy space till blank or greyed out icon becomes available
                $xml .= "<!--6 Payment --><img src='images/common/money_dollar.png' class='action' />";
            }
            $xml .= "<!--7 Email --><a title='".$LANG['email']." ".$row['preference']." ".$row['index_id']."' class='index_table' href='index.php?module=invoices&view=email&stage=1&id=".$row['id']."'><img src='images/common/mail-message-new.png' class='action' /></a>
			]]>
				</cell>";
            $xml .= "<cell><![CDATA[".$row['index_name']."]]></cell>";
            $xml .= "<cell><![CDATA[".$row['biller']."]]></cell>";
            $xml .= "<cell><![CDATA[".$row['customer']."]]></cell>";
            $xml .= "<cell><![CDATA[".\siLocal::date($row['date'])."]]></cell>";
            $xml .= "<cell><![CDATA[".\siLocal::number($row['invoice_total'])."]]></cell>";
            if ($row['status']) {
                $xml .= "<cell><![CDATA[".\siLocal::number($row['owing'])."]]></cell>";
                $xml .= "<cell><![CDATA[".$row['aging']."]]></cell>";
            } else {
                $xml .= "<cell><![CDATA[&nbsp;]]></cell>";
                $xml .= "<cell><![CDATA[&nbsp;]]></cell>";
            }
            $xml .= "<cell><![CDATA[".$row['preference']."]]></cell>";
            $xml .= "</row>";
        }
        $xml .= "</rows>";
        
        echo $xml;
    }
}