<?php
/*
* Script: details.php
* 	invoice details page
*
* Authors:
*	 Justin Kelly, Nicolas Ruflin
*
* Last edited:
* 	 2007-07-19
*
* License:
*	 GPL v2 or above
*
* Website:
* 	http://www.simpleinvoices.org
 */
#table

//stop the direct browsing to this file - let index.php handle which files get displayed
checkLogin();

#get the invoice id
$master_invoice_id = $_GET['invoice'];

$invoice = getInvoice($master_invoice_id);
$invoiceItems = matrix_invoice::getInvoiceItems($master_invoice_id);
$customers = getActiveCustomers();
$preference = getPreference($invoice['preference_id']);
$billers = getActiveBillers();
$taxes = getActiveTaxes();
$preferences = getActivePreferences();
$products = getActiveProducts();


for($i=1;$i<=4;$i++) {
	$customFields[$i] = show_custom_field("invoice_cf$i",$invoice["custom_field$i"],"write",'',"details_screen",'','','');
}

$pageActive = "invoices";
$lines =count($invoiceItems);

$smarty -> assign('pageActive', $pageActive);
$smarty -> assign("invoice",$invoice);
$smarty -> assign("invoiceItems",$invoiceItems);
$smarty -> assign("customers",$customers);
$smarty -> assign("preference",$preference);
$smarty -> assign("billers",$billers);
$smarty -> assign("taxes",$taxes);
$smarty -> assign("preferences",$preferences);
$smarty -> assign("products",$products);
$smarty -> assign("customFields",$customFields);
$smarty -> assign("lines",$lines);

$js =<<<EOD
<script type="text/javascript" charset="utf-8">
$(function()
{
/*
for (var x = 0; x <= $lines; x++)
   {
        $('.product_select'+x).chainSelect('#attr1-'+x,'./index.php?module=invoices&view=ajax&search=attr1');
        $('.product_select'+x).chainSelect('#attr2-'+x,'./index.php?module=invoices&view=ajax&search=attr2');
        $('.product_select'+x).chainSelect('#attr3-'+x,'./index.php?module=invoices&view=ajax&search=attr3');
	}
*/
});
</script>
EOD;
echo $js;
?>
