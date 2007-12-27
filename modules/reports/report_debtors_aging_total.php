<?php 
//   include phpreports library
require_once("./include/reportlib.php");

// Adjusted for NULL on computation with NULL values

	$sSQL  = "SELECT SUM(invtot) As INV_TOTAL, SUM(invpaid) As INV_PAID, SUM(invowing) As INV_OWING, Aging  FROM ";
	$sSQL .= "	(SELECT (CASE WHEN datediff(now(),date) <= 14 THEN '0-14'  ";
	$sSQL .= "				WHEN datediff(now(),date) <= 30 THEN '15-30' ";
	$sSQL .= "				WHEN datediff(now(),date) <= 60 THEN '31-60' ";
	$sSQL .= "				WHEN datediff(now(),date) <= 60 THEN '61-90' ";
	$sSQL .= "			ELSE '90+' END ) as Aging, ";
	$sSQL .= "	@invd:=(select sum( IF(isnull(ivt.total), 0, ivt.total)) from " . TB_PREFIX . "invoice_items ivt where ivt.invoice_id = iv.id) As invd, ";
	$sSQL .= "	IF(isnull(@invd), 0, @invd) As invtot, ";
	$sSQL .= "	@apmt:=(select sum( IF(isnull(ap.ac_amount), 0, ap.ac_amount)) from " . TB_PREFIX . "account_payments ap where ap.ac_inv_id = iv.id) As pmt, ";
	$sSQL .= "	IF(isnull(@apmt), 0, @apmt) As invpaid, ";
	$sSQL .= "	(select (invtot - invpaid)) as invowing ";
	$sSQL .= "	FROM " . TB_PREFIX . "invoices iv GROUP BY iv.id) As mysum ";
	$sSQL .= "GROUP BY Aging DESC";

	$oRpt->setXML("./modules/reports/report_debtors_aging_total.xml");

//   include phpreports run code
	include("./include/reportrunlib.php");

?>