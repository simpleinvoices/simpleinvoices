<?php

include('./config/config.php');
include("./lang/$language.inc.php");

#insert customer
$conn = mysql_connect("$db_host","$db_user","$db_password");
mysql_select_db("$db_name",$conn);


$sql = "select * from si_invoices ORDER BY inv_id desc";

$page_header = "<b>$mi_page_header</b> :: <a href ='invoice_total.php'>$mi_action_invoice_total</a> :: <a href='invoice_itemised.php'>$mi_action_invoice_itemised</a> :: <a href='invoice_consulting.php'>$mi_action_invoice_consulting</a>";
include('./manage_invoices.inc.php');


?>
<html>
<head>

<?php include('./include/menu.php'); ?>

<?php
$mid->printMenu('hormenu1');
$mid->printFooter();
?>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script type="text/javascript" src="include/doFilter.js"></script>

<script type="text/javascript" src="include/jquery.js"></script>
    <script type="text/javascript" src="./include/greybox.js"></script>
    <link rel="stylesheet" type="text/css" href="themes/<?php echo $theme; ?>/tables.css" media="all"/>
    <script type="text/javascript">
      var GB_ANIMATION = true;
      $(document).ready(function(){
        $("a.greybox").click(function(){
          var t = this.title || $(this).text() || this.href;
           GB_show(t,this.href,470,600);
          return false;
        });
      });
    </script>

<script type="text/javascript" src="include/tablesorter.js"></script>

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

<title><?php echo $title; echo $mi_page_title; ?></title>
</head>
<body>



<?php include('./config/config.php'); ?>

<link rel="stylesheet" type="text/css" href="themes/<?php echo $theme; ?>/tables.css"> 
<br>
<div id="container">
<?php echo $display_block; ?>
<div id="footer"><a href="./documentation/text/manage_invoices.html" class="greybox">Whats all these different columns?</a></div>
</div>
</div>

</div>

</body>
</html>
