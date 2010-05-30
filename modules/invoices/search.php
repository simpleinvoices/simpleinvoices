<?php
/*
* Script: search.php
* 	search invoice page
*
* Authors:
*	 Justin Kelly, Nicolas Ruflin
*
* Last edited:
* 	 2008-03-09 - John Gates
*
* License:
*	 GPL v2 or above
*
* Website:
* 	http://www.simpleinvoices.org
 */
//stop the direct browsing to this file - let index.php handle which files get displayed
checkLogin();

$smarty -> display("../templates/default/menu.tpl");
$smarty -> display("../templates/default/main.tpl");

	$startdate = (isset($_POST['startdate'])) ? $_POST['startdate'] : date("Y-m-d",strtotime("last Year"));
	$startdate = htmlsafe($startdate);
	$enddate   = (isset($_POST['enddate']))   ? $_POST['enddate']   : date("Y-m-d",strtotime("now"));
	$enddate = htmlsafe($enddate);



echo <<<EOD
<div style="text-align:left;">
<b>Search Invoice</b><br />
<br />
<b>Search by biller and customer name</b><br />
<form action="index.php?module=invoices&view=search" method="post">
<table width="18%"  border="0">
  <tr>
    <td width="6%"><div align="right">Biller: </div></td>
    <td width="94%"><input type="text" name="biller"></td>
  </tr>
  <tr>
    <td><div align="right">Customer:</div></td>
    <td><input type="text" name="customer"></td>
  </tr>
  <tr>
    <td><div align="right">
      <input type="submit" value="Search">
    </div></td>
    <td>&nbsp;</td>
  </tr>
</table>

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
	echo "<table border=1 cellpadding=2 cellspacing=2>";
	echo "<tr><td>&nbsp;Invoice Number&nbsp;</td><td>&nbsp;Date</td><td>&nbsp;Biller</td><td>&nbsp;Customer</td><td>&nbsp;Type</td></tr>";
	while($res = $sth->fetch()) {
		echo "<tr>";
		echo "<td>&nbsp;<a href='index.php?module=invoices&view=quick_view&invoice=$res[invoice]'>$res[invoice]</a></td>
		<td>&nbsp; $res[date] &nbsp;</td>
		<td>&nbsp; $res[biller] &nbsp;</td>
		<td>&nbsp; $res[customer] &nbsp;</td>
		<td>&nbsp; $res[type] &nbsp;</td>";
		echo "</tr>";
	}
	echo "</table>";
}

echo "</div>";

$pageActive = "invoices";
$smarty -> assign("invoices",$invoices);
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
