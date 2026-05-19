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

$bladeView->display("templates/default/menu.blade.php");
$bladeView->display("templates/default/main.blade.php");

echo <<<EOD
	<div>
	<form action="index.php?module=customers&view=search" method="post">
	<input type="text" name="name" />
	<input type="submit" value="Search">
	</form>
EOD;

$searchName = $_POST['name'] ?? '';
$customers = searchCustomers($searchName);
if (!is_array($customers)) {
	$customers = array();
}

echo "<table> <br />";

foreach ($customers as $customer) {
	$name = htmlspecialchars((string) ($customer['name'] ?? ''), ENT_QUOTES, 'UTF-8');
	$id = (int) ($customer['id'] ?? 0);
	echo <<<EOD
		
		<tr>
			<td>{$name}&nbsp;&nbsp;</td>
			<td><a href="index.php?module=invoices&amp;view=itemised&amp;customer_id={$id}">Itemised</a> |</td> 
			<td><a href="index.php?module=invoices&amp;view=total&amp;customer_id={$id}">&nbsp;Total</a></td> 
		</tr>
EOD;
}

echo "</table></div>";

//getMenuStructure();
exit(); //Fix double menu display ;-) - Gates

?>
