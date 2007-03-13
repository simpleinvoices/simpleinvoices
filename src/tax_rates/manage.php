<?php
	include('./include/include_main.php');

//stop the direct browsing to this file - let index.php handle which files get displayed
if (!defined("BROWSE")) {
   echo "You Cannot Access This Script Directly, Have a Nice Day.";
   exit();
}


#insert customer
$conn = mysql_connect("$db_host","$db_user","$db_password");
mysql_select_db("$db_name",$conn);


$sql = "select * from {$tb_prefix}tax ORDER BY tax_description";

$result = mysql_query($sql, $conn) or die(mysql_error());
$number_of_rows = mysql_num_rows($result);


if (mysql_num_rows($result) == 0) {
	$display_block = "<p><em>{$LANG_no_tax_rates}.</em></p>";
}else{
	$display_block = <<<EOD

	<b>{$LANG_manage_tax_rates} ::
	<a href="./index.php?module=tax_rates&view=add">{$LANG_add_new_tax_rate}</a></b>
 <hr></hr>


<!-- IE hack so that the table fits on the pages -->
<!--[if gte IE 5.5]>
<link rel="stylesheet" type="text/css" href="./src/include/css/iehacks.css" media="all"/>
<![endif]-->

<table align="center" class="ricoLiveGrid" id="rico_tax_rates">
<colgroup>
<col style='width:10%;' />
<col style='width:10%;' />
<col style='width:30%;' />
<col style='width:10%;' />
<col style='width:15%;' />
</colgroup>
<thead>
<tr class="sortHeader">
	<th class="noFilter sortable">{$LANG_actions}</th>
	<th class="index_table sortable">{$LANG_tax_id}</th>
	<th class="index_table sortable">{$LANG_tax_description}</th>
	<th class="index_table sortable">{$LANG_tax_percentage}</th>
	<th class="noFilter index_table sortable">{$wording_for_enabledField}</th>
</tr>
</thead>
EOD;

	while ($Array = mysql_fetch_array($result)) {
		$tax_idField = $Array['tax_id'];
		$tax_descriptionField = $Array['tax_description'];
		$tax_percentageField = $Array['tax_percentage'];
		$tax_enabledField = $Array['tax_enabled'];

		if ($tax_enabledField == 1) {
			$wording_for_enabled = $wording_for_enabledField;
		} else {
			$wording_for_enabled = $wording_for_disabledField;
		}

		$display_block .= <<<EOD
		<tr class="index_table">
		<td class="index_table">
		<a class="index_table"
		href="./index.php?module=tax_rates&view=details&submit={$tax_idField}&action=view">{$LANG_view}</a> ::
		<a class="index_table"
		 href="./index.php?module=tax_rates&view=details&submit={$tax_idField}&action=edit">{$LANG_edit}</a></td>
		<td class="index_table">{$tax_idField}</td>
		<td class="index_table">{$tax_descriptionField}</td>
		<td class="index_table">{$tax_percentageField}</td>
		<td class="index_table">{$wording_for_enabled}</td>
		</tr>

EOD;

	}
	$display_block .= "</table>";
}



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
	{ type:'number', decPlaces:2, ClassName:'alignleft' }
 ]
  };
  var menuopts = <?php GridSettingsMenu(); ?>;
  new Rico.LiveGrid ('rico_tax_rates', new Rico.GridMenu(menuopts), new Rico.Buffer.Base($('rico_tax_rates').tBodies[0]), opts);
});
</script>

<?php 
	echo $display_block; 
?>
<!-- ./src/include/design/footer.inc.php gets called here by controller srcipt -->
