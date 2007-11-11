<?php
//Developed by -==[Mihir Shah]==- during my Project work
//for the output
header("Content-type: text/xml");

$start = (isset($_POST['start'])) ? $_POST['start'] : "0" ;
$dir = (isset($_POST['dir'])) ? $_POST['dir'] : "DESC" ;
$sort = (isset($_POST['sort'])) ? $_POST['sort'] : "id" ;
$limit = (isset($_POST['limit'])) ? $_POST['limit'] : "5" ;

//$sql = "SELECT * FROM ".TB_PREFIX."invoices ORDER BY $sort $dir LIMIT $start, $limit";

$sql ="
SELECT
 si_invoices.id,
 si_biller.name AS Biller,
 si_customers.name AS Customer,
 sum(si_invoice_items.total) AS INV_TOTAL,
 IF ( ISNULL(SUM(ac_amount)) , '0', SUM(ac_amount))  AS INV_PAID,
 (SUM(si_invoice_items.total) - IF(ISNULL(sum(ac_amount)), '0', SUM(ac_amount))) AS INV_OWING ,
 DATE_FORMAT(date,'%Y-%m-%e') AS Date ,
 (SELECT DateDiff(now(),date)) AS Age,
 (CASE WHEN DateDiff(now(),date) <= 14 THEN '0-14'
  WHEN DateDiff(now(),date) <= 30 THEN '15-30'
  WHEN DateDiff(now(),date) <= 60 THEN '31-60'
  WHEN DateDiff(now(),date) <= 90 THEN '61-90'
  ELSE '90+'
 END) AS Aging,
 si_preferences.pref_description AS Type
FROM
 si_invoices
 LEFT JOIN si_account_payments ON  ac_inv_id = si_invoices.id
 LEFT JOIN si_invoice_items ON si_invoice_items.invoice_id = si_invoices.id
 LEFT JOIN si_biller ON si_biller.id = si_invoices.biller_id
 LEFT JOIN si_customers ON si_customers.id = si_invoices.customer_id
 LEFT JOIN si_preferences ON pref_id = preference_id
GROUP BY
 si_invoices.id
ORDER BY
 $sort $dir 
 LIMIT $start, $limit
";

$result = mysqlQuery($sql) or die(mysql_error());

$sqlTotal = "SELECT count(id) FROM ".TB_PREFIX."invoices as count";
$resultTotal = mysql_query($sqlTotal) or die(mysql_error());
$resultCount = mysql_fetch_array($resultTotal);
$count = $resultCount[0];
echo sql2xml($result,$count,'test');

?> 
