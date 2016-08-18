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
$master_invoice_id = $_GET['id'];

$invoice = getInvoice($master_invoice_id);
$domain_id = $invoice['domain_id'];
$invoiceobj = new invoice();
/**/
if ($invoice['preference_id']==5) {
	$sql = "SELECT * FROM ".TB_PREFIX."invoices WHERE index_id = :inv_id AND preference_id = 1 AND domain_id = :domain_id";
	$sth = dbQuery($sql, ':domain_id', $domain_id, ':inv_id', $invoice['index_id']);
	$master_invoice = $sth->fetch(PDO::FETCH_ASSOC);
//	echo '<script>alert(":domain_id'. $domain_id. 'master_invoice='. print_r ($master_invoice, true). '")</script>';
	$master_invoice_id = $master_invoice['id'];
}
/**/
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


for ($i=1;$i<=4;$i++) {
    $customFields[$i] = show_custom_field("invoice_cf$i",$invoice["custom_field$i"],"write",'',"details_screen",'','','');
}

foreach ($invoiceItems as $key=>$value)
{
    //get list of attributes
    $prod = getProduct($value['product_id']);
    $json_att = json_decode($prod['attribute']);
    if ($json_att !== null)
    {
        $html ="<tr id='json_html". $key."'><td></td><td colspan='5'><table><tr>";
        foreach ($json_att as $k=>$v)
        {
            if ($v == 'true')
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

                if ($attr_name['enabled'] =='1' AND $attr_name['type'] == 'list')
                {
                    $html .= "<td>".$attr_name['name']."<select name='attribute[".$key."][".$k."]'>";
                    $html .= "<option value=''></option>";
                    foreach ($states2 as $att_key=>$att_val)
                    {
                        if ($att_val['enabled'] == '1')
                        {
                            foreach ($value['attribute_decode'] as $a_key => $a_value)
                            {
                                if ($k == $a_key AND $a_value == $att_val['id'])
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
                if ($attr_name['enabled'] =='1' AND $attr_name['type'] == 'free'  )
                {
                            $attribute_value ='';
                            foreach ($value['attribute_decode'] as $a_key => $a_value)
                            {
                                if ($k == $a_key){ $attribute_value = $a_value;}
                            }
                    $html .= "<td>".$attr_name['name']."<input name='attribute[".$key."][".$k."]'  value='". $attribute_value ."' /></td>";
                }
                if ($attr_name['enabled'] =='1' AND $attr_name['type'] == 'decimal' )
                {
                            $attribute_value ='';
                            foreach ($value['attribute_decode'] as $a_key => $a_value)
                            {
                                if ($k == $a_key){ $attribute_value = $a_value;}
                            }
                    $html .= "<td>".$attr_name['name']."<input name='attribute[".$key."][".$k."]' size='5' value='". $attribute_value ."' /></td>";
                }
            }
        }
        $html .= "</tr></table></td></tr>";
        $invoiceItems[$key]['html'] = $html;
    }
}

//var_dump($invoiceItems);
$smarty -> assign("invoice",$invoice);
$smarty -> assign("defaults",$defaults);
$smarty -> assign("invoiceItems",$invoiceItems);
$smarty -> assign("customers",$customers);
$smarty -> assign("preference",$preference);
$smarty -> assign("billers",$billers);
$smarty -> assign("taxes",$taxes);
$smarty -> assign("preferences",$preferences);
$smarty -> assign("products",$products);
$smarty -> assign("customFields",$customFields);
$smarty -> assign("lines",count($invoiceItems));

$smarty -> assign('pageActive', 'invoice');
$smarty -> assign('subPageActive', 'invoice_edit');
$smarty -> assign('active_tab', '#money');
?>
