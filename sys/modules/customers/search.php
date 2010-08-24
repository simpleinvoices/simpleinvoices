<?php
/*
* Script: search.php
* 	Customers search page
*
* Authors:
*	 Nicolas Ruflin, John Gates 
*
* Last edited:
* 	 2008-03-15 - John Gates
*
* License:
*	 GPL v2 or above
*
* Website:
* 	http://www.simpleinvoices.org
 */

checkLogin();

$smarty -> display("../templates/default/menu.tpl");
$smarty -> display("../templates/default/main.tpl");

echo <<<EOD
	<div>
	<form action="index.php?module=customers&view=search" method="post">
	<input type="text" name="name" />
	<input type="submit" value="Search">
	</form>
EOD;

$customers = searchCustomers($_POST['name']);

echo "<table> <br />";

foreach($customers as $customer) {
	echo <<<EOD
		
		<tr>
			<td>$customer[name]&nbsp;&nbsp;</td>
			<td><a href="index.php?module=invoices&view=itemised&customer_id=$customer[id]">Itemised</a> |</td> 
			<td><a href="index.php?module=invoices&view=consulting&customer_id=$customer[id]">&nbsp;Consulting</a> |</td> 
			<td><a href="index.php?module=invoices&view=total&customer_id=$customer[id]">&nbsp;Total</a></td> 
		</tr>
EOD;
}

echo "</table></div>";

//getMenuStructure();
exit(); //Fix double menu display ;-) - Gates

?>
