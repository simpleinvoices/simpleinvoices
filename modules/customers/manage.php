<?php


//stop the direct browsing to this file - let index.php handle which files get displayed
checkLogin();

$sql = "SELECT * FROM {$tb_prefix}customers ORDER BY name";

$result = mysql_query($sql, $conn) or die(mysql_error());

$customers = null;
//$invoices = null;

for($i=0;$customer = mysql_fetch_array($result);$i++) {
	
		
		if ($customer['enabled'] == 1) {
			$customer['enabled'] = $LANG['enabled'];
		} else {
			$customer['enabled'] = $LANG['disabled'];
		}

		#invoice total calc - start
		$customer['total'] = calc_customer_total($customer['id']);
		#invoice total calc - end

		#amount paid calc - start
		$customer['paid'] = calc_customer_paid($customer['id']);
		#amount paid calc - end

		#amount owing calc - start
		$customer['owing'] = $customer['total'] - $customer['paid'];
		
		#amount owing calc - end
		
		$customers[$i] = $customer;
		//$invoices[$i] = $invoice;

}

$smarty -> assign("result",$result);
$smarty -> assign("customers",$customers);
//$smarty -> assign("invoices",$invoices);

getRicoLiveGrid("rico_customer","	{ type:'number', decPlaces:0, ClassName:'alignleft' },,{ type:'number', decPlaces:2, ClassName:'alignleft' },{ type:'number', decPlaces:2, ClassName:'alignleft' }");

?>
