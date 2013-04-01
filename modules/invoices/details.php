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
$invoiceItems = invoice::getInvoiceItems($master_invoice_id);
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

foreach($invoiceItems as $key=>$value)
{
    //get list of attributes
    $prod = getProduct($value['product_id']);
    $json_att = json_decode($prod['attribute']);
    if($json_att !== null)
    {
        $html ="<tr id='json_html". $key."'><td></td><td colspan='5'><table><tr>";
        foreach($json_att as $k=>$v)
        {
            if($v == 'true')
            {
                $attr_name_sql = sprintf('select * from si_products_attributes WHERE id = %d', $k);
                $attr_name = dbQuery($attr_name_sql);
                $attr_name = $attr_name->fetch();

                $sql2 = sprintf('select a.name as name, v.id as id, v.value as value, v.enabled as enabled from si_products_attributes a, si_products_values v where a.id = v.attribute_id AND a.id = %d', $k);
                $states2 = dbQuery($sql2);

                if($attr_name['enabled'] =='1')
                {
                    $html .= "<td>".$attr_name['name']."<select name='attribute[".$row_id."][".$k."]'>";
                    $html .= "<option value=''></option>";
                    foreach($states2 as $att_key=>$att_val)
                    {
                        if($att_val['enabled'] == '1')
                        {
                            foreach ($value['attribute_decode'] as $a_key => $a_value)
                            {
                                /*
                                echo "k: $k";
                                echo "key: $a_key";
                                echo "att value: ".$att_val['id'];
                                echo "value: $a_value";
                                 */
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
