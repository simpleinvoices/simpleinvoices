<?php
/*
* Script: search.php
* 	search invoice page
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
	$startdate = (isset($_POST['startdate'])) ? $_POST['startdate'] : date("Y-m-d",strtotime("last Year"));
	$startdate = htmlspecialchars($startdate);
	$enddate   = (isset($_POST['enddate']))   ? $_POST['enddate']   : date("Y-m-d",strtotime("now"));
	$enddate = htmlspecialchars($enddate);

echo "Search Invoice<br />";

echo <<<EOD
<div style="text-align:left;">
<br />
<b>Search by biller and customer name</b><br />
<form action="index.php?module=invoices&view=search" method="post">
Biller:<input type="text" name="biller"><br />
Customer: <input type="text" name="customer"><br />
<input type="submit" value="Search">
</form>
<br />
<br />


<b>Search by date</b>
<form action="index.php?module=invoices&view=search" method="post">
<input type="text" class="date-picker" name="startdate" id="date1" value='$startdate' /><br /><br />
<input type="text" class="date-picker" name="enddate" id="date2" value='$enddate' /><br /><br />
<input type="submit" value="Search">
</form>
<br />
EOD;

$sth = null;

if(isset($_POST['biller']) || isset($_POST['customer'])) {
	$sth = searchBillerAndCustomerInvoice($_POST['biller'],$_POST['customer']);
}



if(isset($_POST['startdate']) && isset($_POST['enddate'])) {
	$sth = searchInvoiceByDate($startdate, $enddate);
}


if($sth != null) {
	echo "<b>Result</b>";
	echo "<table border=1 cellpadding=2>";
	while($res = $sth->fetch()) {
		echo "<tr>";
		echo "<td><a href='index.php?module=invoices&view=quick_view&invoice=$res[invoice]'>$res[invoice]</a></td>
		<td>$res[date]</td>
		<td>$res[biller]</td>
		<td>$res[customer]</td>
		<td>$res[type]</td>";
		echo "</tr>";
	}
	echo "</table>";
}

echo "</div>";

//getMenuStructure();
// till template is made
exit();

/*
"Enhancements to Invoice Manage page
Initially the invoice manage will display blank screen with only options to search. The search criteria could be on the following:
1. from and To Date
2. Customer wise
3. Biller wise
4. Type
5. Owing greater than zero
6. All"*/

?>
