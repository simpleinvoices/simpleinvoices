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
<hr></hr>
EOD;
include('./src/invoices/manage.inc.php');

?>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script type="text/javascript" src="include/doFilter.js"></script>

<script type="text/javascript" src="include/jquery.js"></script>
<script type="text/javascript" src="./include/jquery.thickbox.js"></script>
<script type="text/javascript" src="include/jquery.tablesorter.js"></script>
<script type="text/javascript" src="include/jquery.tablesorter.conf.js"></script>



</head>

<body>

<?php echo $display_block; ?>

<div id="footer"><a href="./documentation/text/manage_invoices.html?keepThis=true&TB_iframe=true&height=300&width=500" title="Info :: Manage invoices" class="thickbox">What's all these different columns?</a>

<?php include("footer.inc.php"); ?>
</div>

</body>
</html>
