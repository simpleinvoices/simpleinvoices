<?php
/*
* Script: details.php
* 	invoice details page
*
* License:
*	 GPL v3 or above
*
* Website:
* 	http://www.simpleinvoices.org
 */
#table

//stop the direct browsing to this file - let index.php handle which files get displayed
checkLogin();

#get the invoice id
$master_invoice_id = (int)$_GET['id']; // Cast to integer to prevent SQL injection

$invoice = getInvoice($master_invoice_id);
si_check_invoice_access($invoice);

$invoiceobj = new invoice();
$invoiceItems = $invoiceobj->getInvoiceItems($master_invoice_id);

//var_dump($invoiceItems);
$customers = getActiveCustomers();
$preference = getPreference($invoice['preference_id']);
$billers = getActiveBillers();
//$taxes = getActiveTaxes(); <--- look into this
$defaults = getSystemDefaults();
$taxes = getTaxes();
$preferences = getActivePreferences();
$products = getActiveProducts();


for($i=1;$i<=4;$i++) {
    $customFields[$i] = show_custom_field("invoice_cf$i",$invoice["custom_field$i"],"write",'',"details_screen",'','','');
}

$current_domain_id = domain_id::get();
foreach($invoiceItems as $key=>$value)
{
    //get list of attributes
    $prod = getProduct($value['product_id']);
    $json_att = json_decode($prod['attribute']);
    if($json_att !== null)
    {
        $html = "<div id='json_html". $key."' class='si-attr-row d-flex flex-wrap gap-2 align-items-end mt-1 mb-1'>";
        foreach($json_att as $k=>$v)
        {
            if($v == 'true')
            {
                $attr_id = (int)$k; // Cast to integer to prevent SQL injection
                $attr_name_sql = 'SELECT
                    a.name as name, a.enabled as enabled, t.name type
                    FROM
                        si_products_attributes as a,
                        si_products_attribute_type as t
                   WHERE
                        a.type_id = t.id
                        AND a.id = :attr_id
                        AND a.domain_id = :domain_id';
                $attr_name = dbQuery($attr_name_sql, ':attr_id', $attr_id, ':domain_id', $current_domain_id);
                $attr_name = $attr_name->fetch();

                $sql2 = 'SELECT
                        a.name as name,
                        v.id as id,
                        v.value as value,
                        v.enabled as enabled
                   FROM
                        si_products_attributes a
                            JOIN si_products_values v
                                ON (v.attribute_id = a.id AND v.domain_id = a.domain_id)
                   WHERE
                        a.id = :attr_id
                        AND a.domain_id = :domain_id';
                $states2 = dbQuery($sql2, ':attr_id', $attr_id, ':domain_id', $current_domain_id);

                if($attr_name['enabled'] =='1' AND $attr_name['type'] == 'list')
                {
                    $html .= "<div><label class='form-label small mb-1'>".htmlspecialchars($attr_name['name'])."</label><select name='attribute[".$key."][".$k."]' class='form-select form-select-sm'>";
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

                            $html .= "<option ". $selected ." value='". $att_val['id']. "'>".htmlspecialchars($att_val['value'])."</option>";
                        }
                    }
                    $html .= "</select></div>";
                }
                if($attr_name['enabled'] =='1' AND $attr_name['type'] == 'free'  )
                {
                            $attribute_value ='';
                            foreach ($value['attribute_decode'] as $a_key => $a_value)
                            {
                                if($k == $a_key){ $attribute_value = $a_value;}
                            }
                    $html .= "<div><label class='form-label small mb-1'>".htmlspecialchars($attr_name['name'])."</label><input class='form-control form-control-sm' name='attribute[".$key."][".$k."]' value='". htmlspecialchars($attribute_value) ."' /></div>";
                }
                if($attr_name['enabled'] =='1' AND $attr_name['type'] == 'decimal' )
                {
                            $attribute_value ='';
                            foreach ($value['attribute_decode'] as $a_key => $a_value)
                            {
                                if($k == $a_key){ $attribute_value = $a_value;}
                            }
                    $html .= "<div><label class='form-label small mb-1'>".htmlspecialchars($attr_name['name'])."</label><input class='form-control form-control-sm' style='width:6rem' name='attribute[".$key."][".$k."]' value='". htmlspecialchars($attribute_value) ."' /></div>";
                }
            }
        }
        $html .= "</div>";
        $invoiceItems[$key]['html'] = $html;
    }
}

//var_dump($invoiceItems);
$bladeView -> assign("invoice",$invoice);
$bladeView -> assign("defaults",$defaults);
$bladeView -> assign("invoiceItems",$invoiceItems);
$bladeView -> assign("customers",$customers);
$bladeView -> assign("preference",$preference);
$bladeView -> assign("billers",$billers);
$bladeView -> assign("taxes",$taxes);
$bladeView -> assign("preferences",$preferences);
$bladeView -> assign("products",$products);
$bladeView -> assign("customFields",$customFields);
$bladeView -> assign("lines",count($invoiceItems));

$bladeView -> assign('pageActive', 'invoice');
$bladeView -> assign('subPageActive', 'invoice_edit');
$bladeView -> assign('active_tab', '#money');
?>
