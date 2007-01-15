<?php
include_once('./include/include_main.php');
?>

<html>
<head>

<?php
echo <<<EOD
<title>{$title} :: {$LANG_manage_invoices}</title>
<link rel="stylesheet" type="text/css" href="themes/{$theme}/jquery.thickbox.css"> 

EOD;
#insert customer
$conn = mysql_connect("$db_host","$db_user","$db_password");
mysql_select_db("$db_name",$conn);


$sql = "select * from si_invoices ORDER BY inv_id desc";

$page_header = <<<EOD
<b>{$LANG_manage_invoices}</b> ::
<a href="index.php?module=invoices&view=total">{$LANG_add_new_invoice} - {$LANG_total_style}</a> ::
<a href="index.php?module=invoices&view=itemised">{$LANG_add_new_invoice} - {$LANG_itemised_style}</a> ::
<a href="index.php?module=invoices&view=consulting">{$LANG_add_new_invoice} - {$LANG_consulting_style}</a>

EOD;
include('./src/invoices/manage.inc.php');

?>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script type="text/javascript" src="include/doFilter.js"></script>

<script type="text/javascript" src="include/jquery.js"></script>
<script type="text/javascript" src="./include/jquery.thickbox.js"></script>
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


</head>

<body>

<br>
<div id="container">
<?php echo $display_block; ?>
<div id="footer"><a href="./documentation/text/manage_invoices.html?keepThis=true&TB_iframe=true&height=300&width=500" title="Info :: Manage invoices" class="thickbox">What's all these different columns?</a></div>
</div>
</div>

</div>

</body>
</html>
