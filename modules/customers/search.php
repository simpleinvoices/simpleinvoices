<?php

checkLogin();


echo <<<EOD
	<form action="index.php?module=customers&view=search" method="post">
	<input type="text" name="name" />
	<input type="submit" value="Search">
	</form>
EOD;

$customers = searchCustomers($_POST['name']);

echo "<table>";

foreach($customers as $customer) {
	echo <<<EOD
		<tr>
			<td>$customer[name]</td>
			<td><a href="index.php?module=invoices&view=itemised&customer=$customer[id]">Itemised</a></td>
			<td><a href="index.php?module=invoices&view=consulting&customer=$customer[id]">Consulting</a></td>
			<td><a href="index.php?module=invoices&view=total&customer=$customer[id]">Total</a></td>
		</tr>
EOD;
}

echo "</table>";

?>
