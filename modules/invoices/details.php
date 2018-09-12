<?php
/*
 *  Script: details.php
 *      invoice details page
 *
 *  License:
 *      GPL v3 or above
 *
 *  Website:
 *      https://simpleinvoices.group
 */
global $smarty;
// stop the direct browsing to this file - let index.php handle which files get displayed
checkLogin ();

$master_invoice_id = $_GET['id'];

// @formatter:off
$invoice      = Invoice::getInvoice ($master_invoice_id);
$invoiceItems = Invoice::getInvoiceItems ( $master_invoice_id );
$customers    = Customer::get_all(true, $invoice['customer_id']);
$preference   = Preferences::getPreference( $invoice ['preference_id'] );
$billers      = Biller::get_all(true);
$defaults     = getSystemDefaults ();
$taxes        = Taxes::getTaxes ();
$preferences  = Preferences::getActivePreferences ();
$products     = Product::select_all ();
// @formatter:on

$customFields = array ();
for ($i = 1; $i <= 4; $i++) {
    $customFields[$i] = CustomFields::show_custom_field("invoice_cf$i"  , $invoice["custom_field$i"],
                                                        "write"         , ''                        ,
                                                        "details_screen", ''                        ,
                                                        ''              , '');
}

foreach ($invoiceItems as $key => $value) {
    // get list of attributes
    $prod = Product::select ($value['product_id']);
    $json_att = json_decode ($prod['attribute']);
    if ($json_att !== null) {
        $html = "<tr id='json_html" . $key . "'><td></td><td colspan='5'><table><tr>";
        foreach ($json_att as $k => $v) {
            if ($v == 'true') {
                $attr_name_sql = sprintf ('select
                    a.name as name, a.enabled as enabled,  t.name type
                    from
                        si_products_attributes as a,
                        si_products_attribute_type as t
                   where
                        a.type_id = t.id
                        AND a.id = %d', $k);
                $attr_name = dbQuery ($attr_name_sql);
                $attr_name = $attr_name->fetch ();

                $sql2 = sprintf ('select
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
                $states2 = dbQuery ($sql2);

                if ($attr_name['enabled'] == '1' && $attr_name['type'] == 'list') {
                    $html .= "<td>" . $attr_name['name'] . "<select name='attribute[" . $key . "][" . $k . "]'>";
                    $html .= "<option value=''></option>";
                    foreach ($states2 as $att_val) {
                        if ($att_val['enabled'] == '1') {
                            foreach ($value['attribute_decode'] as $a_key => $a_value) {
                                if ($k == $a_key && $a_value == $att_val['id']) {
                                    $selected = "selected";
                                    break;
                                } else {
                                    $selected = "";
                                }
                            }

                            $html .= "<option " . $selected . " value='" . $att_val['id'] . "'>" . $att_val['value'] . "</option>";
                        }
                    }
                    $html .= "</select></td>";
                }
                if ($attr_name['enabled'] == '1' && $attr_name['type'] == 'free') {
                    $attribute_value = '';
                    foreach ($value['attribute_decode'] as $a_key => $a_value) {
                        if ($k == $a_key) {
                            $attribute_value = $a_value;
                        }
                    }
                    $html .= "<td>" . $attr_name['name'] . "<input name='attribute[" . $key . "][" . $k . "]'  value='" . $attribute_value . "' /></td>";
                }
                if ($attr_name['enabled'] == '1' && $attr_name['type'] == 'decimal') {
                    $attribute_value = '';
                    foreach ($value['attribute_decode'] as $a_key => $a_value) {
                        if ($k == $a_key) {
                            $attribute_value = $a_value;
                        }
                    }
                    $html .= "<td>" . $attr_name['name'] . "<input name='attribute[" . $key . "][" . $k . "]' size='5' value='" . $attribute_value . "' /></td>";
                }
            }
        }
        $html .= "</tr></table></td></tr>";
        $invoiceItems[$key]['html'] = $html;
    }
}
// @formatter:on
$smarty->assign ("invoice"     , $invoice);
$smarty->assign ("defaults"    , $defaults);
$smarty->assign ("invoiceItems", $invoiceItems);
$smarty->assign ("customers"   , $customers);
$smarty->assign ("preference"  , $preference);
$smarty->assign ("billers"     , $billers);
$smarty->assign ("taxes"       , $taxes);
$smarty->assign ("preferences" , $preferences);
$smarty->assign ("products"    , $products);
$smarty->assign ("customFields", $customFields);
$smarty->assign ("lines"       , count ($invoiceItems));

$smarty->assign ('pageActive'   , 'invoice');
$smarty->assign ('subPageActive', 'invoice_edit');
$smarty->assign ('active_tab'   , '#money');
// @formatter:off
