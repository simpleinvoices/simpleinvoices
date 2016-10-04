<?php
/*
 *  Script: usedefault.php
 *      page which chooses an empty page or another invoice as templat
 *
 *  Authors:
 *      Marcel van Dorp, Justin Kelly, Nicolas Ruflin
 *
 *  Last edited:
 *      2016-08-02
 *
 *  License:
 *      GPL v3 or above
 *
 *  Website:
 *      http://www.simpleinvoices.org
 */
global $defaults, $pdoDb, $smarty;

// stop the direct browsing to this file - let index.php handle which files get displayed
checkLogin ();

$master_customer_id = $_GET ['customer_id'];
$customer = Customer::get($master_customer_id);

if ($_GET ['action'] == 'update_template') {
    // Update the default template for this customer
    $pdoDb->setFauxPost(array("custom_field4" => $_GET ['id']));
    $pdoDb->addSimpleWhere("id'", $master_customer_id);
    $pdoDb->addSimpleWhere("domain_id", domain_id::get());
    $pdoDb->request("UPDATE", "customers");

    $smarty->assign("view", "quick_view");
    $smarty->assign("spec", "id");
    $smarty->assign("id", $_GET['id']);
} else {
    // Set the template touse. If there is a customer specified invoice,
    // use it. Otherwise, use the application default invoice.
    if (empty($customer['custom_field4'])) {
        $template = $defaults['default_invoice'];
    } else {
        $template = $customer['custom_field4'];
    }

    $invoice = Invoice::getInvoice($template);

    // Set values based on presence of customer specific template.
    if (empty($invoice ['id'])) {
        // No template for this customer
        $smarty->assign("view", "itemised");
        $smarty->assign("spec", "customer_id");
        $smarty->assign("id"  , $master_customer_id);
    } else {
        // Use template for this customer
        $smarty->assign("view", "details");
        $smarty->assign("spec", "template");
        $smarty->assign("id"  , $invoice ['id']);
    }
}
