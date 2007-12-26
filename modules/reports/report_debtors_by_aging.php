<?php
//   include phpreports library
require_once("./include/reportlib.php");

// Adjusted for NULL on computation with NULL values

	$sSQL  = "SELECT iv.id As id, (CASE WHEN datediff(now(),date) <= 14 THEN '0-14'  ";
	$sSQL .= "				WHEN datediff(now(),date) <= 30 THEN '15-30' ";
	$sSQL .= "				WHEN datediff(now(),date) <= 60 THEN '31-60' ";
	$sSQL .= "				WHEN datediff(now(),date) <= 60 THEN '61-90' ";
	$sSQL .= "			ELSE '90+' END ) as Aging, ";
	$sSQL .= "	b.name As Biller, ";
	$sSQL .= "	c.name As Customer, ";
	$sSQL .= "  DATE_FORMAT(date,'%Y-%m-%d') as Date, ";
	$sSQL .= "  datediff(now(),date) as Age, ";
	$sSQL .= "	@invd:=(select sum( IF(isnull(ivt.total), 0, ivt.total)) from " . TB_PREFIX . "invoice_items ivt where ivt.invoice_id = iv.id) As invd, ";
	$sSQL .= "	IF(isnull(@invd), 0, @invd) As INV_TOTAL, ";
	$sSQL .= "	@apmt:=(select sum( IF(isnull(ap.ac_amount), 0, ap.ac_amount)) from " . TB_PREFIX . "account_payments ap where ap.ac_inv_id = iv.id) As pmt, ";
	$sSQL .= "	IF(isnull(@apmt), 0, @apmt) As INV_PAID, ";
	$sSQL .= "	(select (INV_TOTAL - INV_PAID)) as INV_OWING ";
	$sSQL .= "FROM " . TB_PREFIX . "invoices iv, " . TB_PREFIX . "customers c, " . TB_PREFIX . "biller b ";
	$sSQL .= "WHERE iv.customer_id = c.id AND iv.biller_id = b.id ";
	$sSQL .= "ORDER BY Aging";

	$oRpt->setXML("./modules/reports/report_debtors_by_aging.xml");

//   include phpreports run code
	include("./include/reportrunlib.php");

?>