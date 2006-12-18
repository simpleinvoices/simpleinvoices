<?php
include('./include/include_main.php');

#insert customer
$conn = mysql_connect( $db_host, $db_user, $db_password );
mysql_select_db( $db_name, $conn );

#if coming from another page where you want to filter by just one invoice
if (!empty($_GET[inv_id])) {

	$display_block_header = "<b>$map_payments_filtered $_GET[inv_id]</b> :: <a href='process_payment.php?submit=$_GET[inv_id]&op=pay_selected_invoice'>$map_payments_filtered_invoice</a>";

        $sql = "select si_account_payments.*, si_customers.c_name, si_biller.b_name from si_account_payments, si_invoices, si_customers, si_biller  where ac_inv_id = si_invoices.inv_id and si_invoices.inv_customer_id = si_customers.c_id and si_invoices.inv_biller_id = si_biller.b_id and si_account_payments.ac_inv_id='$_GET[inv_id]' ORDER BY si_account_payments.ac_id DESC";
	}
#if coming from another page where you want to filter by just one customer
elseif (!empty($_GET[c_id])) {
	
	$display_block_header = "<b>$map_payments_filtered_customer $_GET[c_id]</b> :: <a href='process_payment.php?op=pay_invoice'>$map_actions_process_payment</a>";
	
        $sql = "select si_account_payments.*, si_customers.c_name, si_biller.b_name from si_account_payments, si_invoices, si_customers, si_biller  where ac_inv_id = si_invoices.inv_id and si_invoices.inv_customer_id = si_customers.c_id and si_invoices.inv_biller_id = si_biller.b_id and si_customers.c_id='$_GET[c_id]' ORDER BY si_account_payments.ac_id DESC ";
        }
#if you want to show all invoices - no filters
else {

	$display_block_header = "<b>$map_page_header</b> :: <a href='process_payment.php?op=pay_invoice'>$map_actions_process_payment</a>";

	$sql = "select si_account_payments.*, si_customers.c_name, si_biller.b_name from si_account_payments, si_invoices, si_customers, si_biller  where ac_inv_id = si_invoices.inv_id and si_invoices.inv_customer_id = si_customers.c_id and si_invoices.inv_biller_id = si_biller.b_id ORDER BY si_account_payments.ac_id DESC";
	}

$result = mysql_query($sql, $conn) or die(mysql_error());
$number_of_rows = mysql_num_rows($result);


if (mysql_num_rows($result) == 0) {
$display_block = "<P><em>$map_no_invoices.</em></p>";
}else{
$display_block = "

<div id=\"sorting\">
       <div>Sorting tables, please hold on...</div>
</div>


<table width=100% class=\"filterable sortable\" id=large align=center>
<div id=header>$display_block_header</div>
<tr class=\"sortHeader\">
<th class=\"noFilter\">$map_table_action</th>
<th class=\" index_table\">$map_table_payment_id</th>
<th class=\"index_table\">$map_table_payment_invoice_id</th>
<th class=\"selectFilter index_table\">$map_table_customer</th>
<th class=\"selectFilter index_table\">$map_table_biller</th>
<th class=\"index_table\">$map_table_amount</th>
<th class=\"index_table\">$map_table_notes</th>
<th class=\"selectFilter index_table\">$map_table_payment_type</th>
<th class=\"index_table\">$map_table_date</th>
</tr>";

while ($Array = mysql_fetch_array($result)) {
	$ac_idField = $Array['ac_id'];
	$ac_inv_idField = $Array['ac_inv_id'];
	$ac_amountField = $Array['ac_amount'];
	$ac_notesField = $Array['ac_notes'];
	$ac_payment_typeField = $Array['ac_payment_type'];
	$ac_dateField =  date( $config['date_format'], strtotime( $Array['ac_date'] ) ); 
	$b_nameField = $Array['b_name'];
	$c_nameField = $Array['c_name'];
	
                #item description - only show first 10 characters and add ... to signify theres more text
                $max_length = 10;
                if (strlen($ac_notesField) > $max_length ) {
                        $stripped_ac_notesField = substr($ac_notesField,0,10);
                        $stripped_ac_notesField .= "...";
                }
                else if (strlen($ac_notesField) <= $max_length ) {
                         $stripped_ac_notesField = $ac_notesField;
                }

		#Payment type section
		$payment_type_description = "select pt_description from si_payment_types where pt_id = $ac_payment_typeField";
		$result_payment_type_description = mysql_query($payment_type_description, $conn) or die(mysql_error());

		while ($Array_pt = mysql_fetch_array($result_payment_type_description) ) {
                		$payment_type_descriptionField = $Array_pt['pt_description'];
		};




	$display_block .= "
	<tr class='index_table'>
		<td class='index_table'><a class='index_table' href='payment_details.php?inv_id=$ac_idField'>$map_actions_view</a></td>
		<td class='index_table'>$ac_idField</td>
		<td class='index_table'>$ac_inv_idField</td>
		<td class='index_table'>$c_nameField</td>
		<td class='index_table'>$b_nameField</td>
		<td class='index_table'>$ac_amountField</td>
		<td class='index_table'>$stripped_ac_notesField</td>
		<td class='index_table'>$payment_type_descriptionField</td>
		<td class='index_table'>$ac_dateField</td>
	</tr>";
	
		}

        $display_block .="</table>";
}


?>
<html>
<head>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

    <script type="text/javascript" src="./include/jquery.js"></script>
    <script type="text/javascript" src="./include/jquery.greybox.js"></script>
    <script type="text/javascript" src="./include/jquery.greybox.conf.js"></script>

    <link rel="stylesheet" type="text/css" href="themes/<?php echo $theme; ?>/tables.css" media="all"/>

<?php include('./include/menu.php'); ?>
<?php
$mid->printMenu('hormenu1');
$mid->printFooter();
?>

<script type="text/javascript" src="include/doFilter.js"></script>

<script type="text/javascript" src="include/jquery.tablesorter.js"></script>

<script type="text/javascript">
$(document).ready(function() {
        $("table#large").tableSorter({
                sortClassAsc: 'sortUp', // class name for asc sorting action
                sortClassDesc: 'sortDown', // class name for desc sorting action
                highlightClass: ['highlight'], // class name for sort column highlighting.
                //stripingRowClass: ['even','odd'],
                //alternateRowClass: ['odd','even'],
                headerClass: 'largeHeaders', // class name for headers (th's)
                disableHeader: [0], // disable column can be a string / number or array containing string or number.
                dateFormat: 'dd/mm/yyyy' // set date format for non iso dates default us, in this case override and set uk-format
        })
});
$(document).sortStart(function(){
        $("div#sorting").show();
}).sortStop(function(){
        $("div#sorting").hide();
});
</script>


<script type="text/javascript" src="niftycube.js"></script>
<script type="text/javascript">
window.onload=function(){
Nifty("div#container");
Nifty("div#content,div#nav","same-height small");
Nifty("div#header,div#footer","small");
}
</script>


<title><?php echo $title; echo $map_page_title;?></title>
</head>
<?php include('./config/config.php'); ?>
<body>

<link rel="stylesheet" type="text/css" href="themes/<?php echo $theme; ?>/tables.css">
<br>
<div id="container">
<?php echo $display_block; ?>
<div id="footer"><a href="./documentation/text/wheres_the_edit_button.html" class="greybox">Wheres the Edit button?</a></div>
</div>
</div>

</body>

