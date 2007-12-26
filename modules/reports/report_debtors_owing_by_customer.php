<?php
//   include phpreports library
require_once("./include/reportlib.php");

// Adjusted for NULL on computation with NULL values

	$sSQL  = "SELECT c.id As CID, c.name As Customer, ";
	$sSQL .= "@invd:=(select sum( IF(isnull(ivt.total), 0, ivt.total)) from " . TB_PREFIX . "invoice_items ivt, " . TB_PREFIX . "invoices iv where ivt.invoice_id = iv.id AND iv.customer_id = CID) as invd, "; 
	$sSQL .= "IF(isnull(@invd), 0, @invd) As INV_TOTAL, ";
	$sSQL .= "@apmt:=(select sum( IF(isnull(ap.ac_amount), 0, ap.ac_amount)) from " . TB_PREFIX . "account_payments ap, " . TB_PREFIX . "invoices iv where ap.ac_inv_id = iv.id AND iv.customer_id = CID) As pmt, ";
	$sSQL .= "IF(isnull(@apmt), 0, @apmt) As INV_PAID, ";
	$sSQL .= "(select (INV_TOTAL - INV_PAID)) as INV_OWING ";
	$sSQL .= "FROM " . TB_PREFIX . "customers c ";
	$sSQL .= "GROUP BY CID ";
	$sSQL .= "HAVING INV_OWING > 0 ";
	$sSQL .= "ORDER BY INV_OWING DESC;";


	$oRpt->setXML("./modules/reports/report_debtors_owing_by_customer.xml");

//   include phpreports run code
	include("./include/reportrunlib.php");

?>