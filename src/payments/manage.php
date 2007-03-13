<?php
include('./include/include_main.php');

//stop the direct browsing to this file - let index.php handle which files get displayed
if (!defined("BROWSE")) {
   echo "You Cannot Access This Script Directly, Have a Nice Day.";
   exit();
}

if (!defined("BROWSE")) {
   echo "You Cannot Access This Script Directly, Have a Nice Day.";
   exit();
}

#insert customer
$conn = mysql_connect( $db_host, $db_user, $db_password );
mysql_select_db( $db_name, $conn );

#if coming from another page where you want to filter by just one invoice
if (!empty($_GET['inv_id'])) {

	$display_block_header = "<b>$map_payments_filtered $_GET[inv_id]</b> :: <a href='index.php?module=payments&view=process&submit=$_GET[inv_id]&op=pay_selected_invoice'>$map_payments_filtered_invoice</a>";

        $sql = "select {$tb_prefix}account_payments.*, {$tb_prefix}customers.c_name, {$tb_prefix}biller.b_name from {$tb_prefix}account_payments, {$tb_prefix}invoices, {$tb_prefix}customers, {$tb_prefix}biller  where ac_inv_id = {$tb_prefix}invoices.inv_id and {$tb_prefix}invoices.inv_customer_id = {$tb_prefix}customers.c_id and {$tb_prefix}invoices.inv_biller_id = {$tb_prefix}biller.b_id and {$tb_prefix}account_payments.ac_inv_id='$_GET[inv_id]' ORDER BY {$tb_prefix}account_payments.ac_id DESC";
	}
#if coming from another page where you want to filter by just one customer
elseif (!empty($_GET['c_id'])) {
	
	$display_block_header = "<b>$map_payments_filtered_customer $_GET[c_id] :: <a href='index.php?module=payments&view=process&op=pay_invoice'>$map_actions_process_payment</a></b>";
	
        $sql = "select {$tb_prefix}account_payments.*, {$tb_prefix}customers.c_name, {$tb_prefix}biller.b_name from {$tb_prefix}account_payments, {$tb_prefix}invoices, {$tb_prefix}customers, {$tb_prefix}biller  where ac_inv_id = {$tb_prefix}invoices.inv_id and {$tb_prefix}invoices.inv_customer_id = {$tb_prefix}customers.c_id and {$tb_prefix}invoices.inv_biller_id = {$tb_prefix}biller.b_id and {$tb_prefix}customers.c_id='$_GET[c_id]' ORDER BY {$tb_prefix}account_payments.ac_id DESC ";
        }
#if you want to show all invoices - no filters
else {

	$display_block_header = "<b>$map_page_header :: <a href='index.php?module=payments&view=process&op=pay_invoice'>$map_actions_process_payment</a></b>";

	$sql = "select {$tb_prefix}account_payments.*, {$tb_prefix}customers.c_name, {$tb_prefix}biller.b_name from {$tb_prefix}account_payments, {$tb_prefix}invoices, {$tb_prefix}customers, {$tb_prefix}biller  where ac_inv_id = {$tb_prefix}invoices.inv_id and {$tb_prefix}invoices.inv_customer_id = {$tb_prefix}customers.c_id and {$tb_prefix}invoices.inv_biller_id = {$tb_prefix}biller.b_id ORDER BY {$tb_prefix}account_payments.ac_id DESC";
	}

$result = mysql_query($sql, $conn) or die(mysql_error());
$number_of_rows = mysql_num_rows($result);


if (mysql_num_rows($result) == 0) {
$display_block = "<P><em>$map_no_invoices.</em></p>";
}else{
$display_block = <<<EOD


$display_block_header
<hr></hr>

<!-- IE hack so that the table fits on the pages -->
<!--[if gte IE 5.5]>
<link rel="stylesheet" type="text/css" href="./src/include/css/iehacks.css" media="all"/>
<![endif]-->

<table  align="center" class="ricoLiveGrid" id="rico_payment" >
<colgroup>
<col style='width:10%;' />
<col style='width:10%;' />
<col style='width:10%;' />
<col style='width:15%;' />
<col style='width:15%;' />
<col style='width:10%;' />
<col style='width:10%;' />
<col style='width:10%;' />
<col style='width:10%;' />
</colgroup>
<thead>

<tr class="sortHeader">
<th class="noFilter">$map_table_action</th>
<th class="index_table">$map_table_payment_id</th>
<th class="index_table">$map_table_payment_invoice_id</th>
<th class="selectFilter index_table">$map_table_customer</th>
<th class="selectFilter index_table">$map_table_biller</th>
<th class="index_table">$map_table_amount</th>
<th class="index_table">$map_table_notes</th>
<th class="selectFilter index_table">$map_table_payment_type</th>
<th class="noFilter index_table">$map_table_date</th>
</tr>
</thead>
EOD;

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
		$payment_type_description = "select pt_description from {$tb_prefix}payment_types where pt_id = $ac_payment_typeField";
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


include("./html/header.html");

require "./src/include/js/lgplus/php/chklang.php";
require "./src/include/js/lgplus/php/settings.php";
?>

<script src="./src/include/js/lgplus/js/rico.js" type="text/javascript"></script>
<script type='text/javascript'>
Rico.loadModule('LiveGrid');
Rico.loadModule('LiveGridMenu');

<?php
setStyle();
setLang();
?>

Rico.onLoad( function() {
  var opts = {  
    <?php GridSettingsScript(); ?>,
    columnSpecs   : [ 
	,
	{ type:'number', decPlaces:0, ClassName:'alignleft' },
	{ type:'number', decPlaces:0, ClassName:'alignleft' },
	,
	,
	{ type:'number', decPlaces:2, ClassName:'alignleft' }
 ]
  };
  var menuopts = <?php GridSettingsMenu(); ?>;
  new Rico.LiveGrid ('rico_payment', new Rico.GridMenu(menuopts), new Rico.Buffer.Base($('rico_payment').tBodies[0]), opts);
});
</script>

<?php include('./config/config.php'); ?>


<?php echo $display_block; ?>

<a href="./documentation/info_pages/wheres_the_edit_button.html" rel="ibox&height=400"><img src="./images/common/help-small.png"></img>Wheres the Edit button?</a>
<!-- ./src/include/design/footer.inc.php gets called here by controller srcipt -->
