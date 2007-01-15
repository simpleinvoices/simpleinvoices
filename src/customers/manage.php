<?php
include_once('./include/include_main.php');
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<?php

#select customers
$conn = mysql_connect("$db_host","$db_user","$db_password");
mysql_select_db("$db_name",$conn);


$sql = "select * from si_customers ORDER BY c_name";

$result = mysql_query($sql, $conn) or die(mysql_error());
$number_of_rows = mysql_num_rows($result);


if (mysql_num_rows($result) == 0) {
	$display_block = "<P><em>{$LANG_no_customers}.</em></p>";
} else {
	$display_block = <<<EOD

<div id="sorting">
	<div>Sorting tables, please hold on...</div>
</div>

<div id="top"><b>{$LANG_manage_customers}</b> :: <a href="insert_customer.php">{$LANG_customer_add}</a></div>
<hr></hr>
<div id="browser">


<table width="100%" align="center" id="large" class="filterable sortable">
<tr class="sortHeader">
<th class="noFilter">{$LANG_actions}</th>
<th class="index_table">{$LANG_customer_id}</th>
<th class="index_table">{$LANG_customer_name}</th>
<th class="index_table">{$LANG_phone}</th>
<th class="index_table">{$LANG_total}</th>
<th class="index_table">{$LANG_paid}</th>
<th class="index_table">{$LANG_owing}</th>
<th class="selectFilter index_table">{$wording_for_enabledField} &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
</tr>

EOD;

	while ($Array = mysql_fetch_array($result)) {
		$c_idField = $Array['c_id'];
		$c_attentionField = $Array['c_attention'];
		$c_nameField = $Array['c_name'];
		$c_street_addressField = $Array['c_street_address'];
		$c_cityField = $Array['c_city'];
		$c_stateField = $Array['c_state'];
		$c_zip_codeField = $Array['c_zip_code'];
		$c_countryField = $Array['c_country'];
		$c_phoneField = $Array['c_phone'];
		$c_faxField = $Array['c_fax'];
		$c_emailField = $Array['c_email'];
		$c_enabledField = $Array['c_enabled'];
	
  	if ($c_enabledField == 1) {
  		$wording_for_enabled = $wording_for_enabledField;
  	} else {
  		$wording_for_enabled = $wording_for_disabledField;
		}

#invoice total calc - start
		$print_invoice_total ="select IF ( isnull( sum(inv_it_total)) ,  '0', sum(inv_it_total)) as total from si_invoice_items, si_invoices where  si_invoices.inv_customer_id  = $c_idField  and si_invoices.inv_id = si_invoice_items.inv_it_invoice_id";
		$result_print_invoice_total = mysql_query($print_invoice_total, $conn) or die(mysql_error());

		while ($Array = mysql_fetch_array($result_print_invoice_total)) {
			$invoice_total_Field = number_format($Array['total'],2);
#invoice total calc - end

#amount paid calc - start
			$x1 = "select  IF ( isnull( sum(ac_amount)) ,  '0', sum(ac_amount)) as amount from si_account_payments, si_invoices, si_invoice_items where si_account_payments.ac_inv_id = si_invoices.inv_id and si_invoices.inv_customer_id = $c_idField  and si_invoices.inv_id = si_invoice_items.inv_it_invoice_id";
			//$x1 = "select  IF ( isnull( sum(ac_amount)) ,  '0', sum(ac_amount)) as amount from si_account_payments, si_invoices, si_invoice_items where si_account_payments.ac_inv_id = si_invoices.inv_id and si_invoices.inv_customer_id = $c_idField  and si_invoices.inv_id = si_invoice_items.inv_it_id";
			$result_x1 = mysql_query($x1, $conn) or die(mysql_error());
			while ($result_x1Array = mysql_fetch_array($result_x1)) {
				$invoice_paid_Field = number_format($result_x1Array['amount'],2);
#amount paid calc - end

#amount owing calc - start
				$invoice_owing_Field = number_format($invoice_total_Field - $invoice_paid_Field,2);
#amount owing calc - end

				$display_block .= <<<EOD
	<tr class="index_table">
	<td class="index_table"><a class="index_table"
	 href="index.php?module=customers&view=details&submit={$c_idField}&action=view">{$LANG_view}</a> ::
	<a class="index_table"
	 href="index.php?module=customers&view=details&submit={$c_idField}&action=edit">{$LANG_edit}</a> </td>
	<td class="index_table">{$c_idField}</td>
	<td class="index_table">{$c_nameField}</td>
	<td class="index_table">{$c_phoneField}</td>
	<td class="index_table">{$invoice_total_Field}</td>
	<td class="index_table">{$invoice_paid_Field}</td>
	<td class="index_table">{$invoice_owing_Field}</td>
	<td class="index_table">{$wording_for_enabled}</td>
	</tr>

EOD;
			}
		}
	}
	$display_block .= "</table>";
}
?>

<script type="text/javascript" src="include/doFilter.js"></script>
<script type="text/javascript" src="include/jquery.js"></script>
<script type="text/javascript" src="include/jquery.tablesorter.js"></script>
<script type="text/javascript" src="include/jquery.tablesorter.conf.js"></script>

</head>
<body>

<br>
<div id="container">
<?php 

echo $display_block;

echo <<<EOD
</div>
EOD;

include('footer.inc.php');

?>

</div>
</div>

</body>
</html>
