<?php
include_once('./include/include_main.php');

//stop the direct browsing to this file - let index.php handle which files get displayed
checkLogin();


#select customers
$conn = mysql_connect("$db_host","$db_user","$db_password");
mysql_select_db("$db_name",$conn);


$sql = "select * from {$tb_prefix}customers ORDER BY c_name";

$result = mysql_query($sql, $conn) or die(mysql_error());
$number_of_rows = mysql_num_rows($result);


if (mysql_num_rows($result) == 0) {
	$display_block = "<P><em>{$LANG_no_customers}.</em></p>";
} else {
	$display_block = <<<EOD


<b>{$LANG_manage_customers} :: <a href="index.php?module=customers&view=add">{$LANG_customer_add}</a></b>
<hr></hr>

<!-- IE hack so that the table fits on the pages -->
<!--[if gte IE 5.5]>
<link rel="stylesheet" type="text/css" href="./src/include/css/iehacks.css" media="all"/>
<![endif]-->


<table align="center" id="rico_customer" class="ricoLiveGrid manage">
<colgroup>
<col style='width:10%;' />
<col style='width:5%;' />
<col style='width:25%;' />
<col style='width:15%;' />
<col style='width:15%;' />
<col style='width:15%;' />
</colgroup>
<thead>
<tr class="sortHeader">
<th class="noFilter sortable">{$LANG_actions}</th>
<th class="index_table sortable">{$LANG_customer_id}</th>
<th class="index_table sortable">{$LANG_customer_name}</th>
<!--
<th class="index_table">{$LANG_phone}</th>
-->
<th class="index_table sortable">{$LANG_total}</th>
<!--
<th class="index_table">{$LANG_paid}</th>
-->
<th class="index_table sortable">{$LANG_owing}</th>
<th class="noFilter index_table sortable">{$wording_for_enabledField}</th>
</tr>
</thead>
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
	$invoice_total_Field = calc_customer_total($c_idField );	
	$invoice_total_Field_format = number_format($invoice_total_Field,2);	
#invoice total calc - end

#amount paid calc - start
	$invoice_paid_Field = calc_customer_paid($c_idField);
	$invoice_paid_Field_format = number_format($invoice_paid_Field,2);
#amount paid calc - end

#amount owing calc - start
	$invoice_owing_Field = $invoice_total_Field - $invoice_paid_Field;
	$invoice_owing_Field_format = number_format($invoice_total_Field - $invoice_paid_Field,2);
#amount owing calc - end

				$display_block .= <<<EOD
	<tr class="index_table">
	<td class="index_table"><a class="index_table"
	 href="index.php?module=customers&view=details&submit={$c_idField}&action=view">{$LANG_view}</a> ::
	<a class="index_table"
	 href="index.php?module=customers&view=details&submit={$c_idField}&action=edit">{$LANG_edit}</a> </td>
	<td class="index_table">{$c_idField}</td>
	<td class="index_table">{$c_nameField}</td>
	<!--
	<td class="index_table">{$c_phoneField}</td>
	-->
	<td class="index_table">{$invoice_total_Field}</td>
	<!--
	<td class="index_table">{$invoice_paid_Field}</td>
	-->
	<td class="index_table">{$invoice_owing_Field}</td>
	<td class="index_table">{$wording_for_enabled}</td>
	</tr>

EOD;
	}
	$display_block .= "</table>";
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
	,
	{ type:'number', decPlaces:2, ClassName:'alignleft' },
	{ type:'number', decPlaces:2, ClassName:'alignleft' }
 ]
  };
  var menuopts = <?php GridSettingsMenu(); ?>;
  new Rico.LiveGrid ('rico_customer', new Rico.GridMenu(menuopts), new Rico.Buffer.Base($('rico_customer').tBodies[0]), opts);
});
</script>



<?php 

echo $display_block;

?>
<!-- ./src/include/design/footer.inc.php gets called here by controller srcipt -->
