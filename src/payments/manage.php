<?php
include('./include/include_main.php');

#insert customer
$conn = mysql_connect( $db_host, $db_user, $db_password );
mysql_select_db( $db_name, $conn );

#if coming from another page where you want to filter by just one invoice
if (!empty($_GET[inv_id])) {

	$display_block_header = "<b>$map_payments_filtered $_GET[inv_id]</b> :: <a href='index.php?module=payments&view=process&submit=$_GET[inv_id]&op=pay_selected_invoice'>$map_payments_filtered_invoice</a>";

        $sql = "select si_account_payments.*, si_customers.c_name, si_biller.b_name from si_account_payments, si_invoices, si_customers, si_biller  where ac_inv_id = si_invoices.inv_id and si_invoices.inv_customer_id = si_customers.c_id and si_invoices.inv_biller_id = si_biller.b_id and si_account_payments.ac_inv_id='$_GET[inv_id]' ORDER BY si_account_payments.ac_id DESC";
	}
#if coming from another page where you want to filter by just one customer
elseif (!empty($_GET[c_id])) {
	
	$display_block_header = "<b>$map_payments_filtered_customer $_GET[c_id] :: <a href='index.php?module=payments&view=process&op=pay_invoice'>$map_actions_process_payment</a></b>";
	
        $sql = "select si_account_payments.*, si_customers.c_name, si_biller.b_name from si_account_payments, si_invoices, si_customers, si_biller  where ac_inv_id = si_invoices.inv_id and si_invoices.inv_customer_id = si_customers.c_id and si_invoices.inv_biller_id = si_biller.b_id and si_customers.c_id='$_GET[c_id]' ORDER BY si_account_payments.ac_id DESC ";
        }
#if you want to show all invoices - no filters
else {

	$display_block_header = "<b>$map_page_header :: <a href='index.php?module=payments&view=process&op=pay_invoice'>$map_actions_process_payment</a></b>";

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

$display_block_header
<hr></hr>
 <div id='browser'>
<table width=100% class=\"filterable sortable\" id=large align=center>

<table width=\"97%\" align=\"center\" class=\"ricoLiveGrid\" id=\"rico_payment\" >
<colgroup>
<col style='width:10%;' />
<col style='width:10%;' />
<col style='width:10%;' />
<col style='width:10%;' />
<col style='width:10%;' />
<col style='width:10%;' />
<col style='width:10%;' />
<col style='width:10%;' />
<col style='width:10%;' />
</colgroup>
<thead>

<tr class=\"sortHeader\">
<th class=\"noFilter\">$map_table_action</th>
<th class=\"index_table\">$map_table_payment_id</th>
<th class=\"index_table\">$map_table_payment_invoice_id</th>
<th class=\"selectFilter index_table\">$map_table_customer</th>
<th class=\"selectFilter index_table\">$map_table_biller</th>
<th class=\"index_table\">$map_table_amount</th>
<th class=\"index_table\">$map_table_notes</th>
<th class=\"selectFilter index_table\">$map_table_payment_type</th>
<th class=\"noFilter index_table\">$map_table_date</th>
</tr>
</thead>
";

while ($Array = mysql_fetch_array($result)) {
	$ac_idField = $Array['ac_id'];
	$ac_inv_idField = $Array['ac_inv_id'];
	$ac_amountField = ($Array['ac_amount']);
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
		<td class='index_table'><a class='index_table' href='index.php?module=payments&view=details&inv_id=$ac_idField'>$map_actions_view</a></td>
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
    <script type="text/javascript" src="./include/jquery.thickbox.js"></script>

    <link rel="stylesheet" type="text/css" href="./src/include/css/jquery.thickbox.css" media="all"/>

<? 
require "lgplus/php/chklang.php";
require "lgplus/php/settings.php";
?>

<script src="lgplus/js/rico.js" type="text/javascript"></script>
<script type='text/javascript'>
Rico.loadModule('LiveGrid');
Rico.loadModule('LiveGridMenu');

<?
setStyle();
setLang();
?>

Rico.onLoad( function() {
  var opts = {  
    <? GridSettingsScript(); ?>,
    columnSpecs   : [ 
	,
	{ type:'number', decPlaces:0, ClassName:'alignleft' },
	{ type:'number', decPlaces:0, ClassName:'alignleft' },
	,
	,
	{ type:'number', decPlaces:2, ClassName:'alignleft' }
 ]
  };
  var menuopts = <? GridSettingsMenu(); ?>;
  new Rico.LiveGrid ('rico_payment', new Rico.GridMenu(menuopts), new Rico.Buffer.Base($('rico_payment').tBodies[0]), opts);
});
</script>

</head>
<?php include('./config/config.php'); ?>
<body>

<?php echo $display_block; ?>

<a href="./documentation/info_pages/wheres_the_edit_button.html?keepThis=true&TB_iframe=true&height=300&width=500" title="Info :: Payments" class="thickbox"><img src="./images/common/help-small.png"></img>Wheres the Edit button?</a>
<!-- ./src/include/design/footer.inc.php gets called here by controller srcipt -->
