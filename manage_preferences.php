<?php
include('./include/include_main.php');	

	#insert customer
	$conn = mysql_connect("$db_host","$db_user","$db_password");
	mysql_select_db("$db_name",$conn);


	$print_preferences = "SELECT * FROM si_preferences";
	$result_print_preferences  = mysql_query($print_preferences, $conn) or die(mysql_error());


	if (mysql_num_rows($result_print_preferences) == 0) {
	$display_block = "<P><em>$mip_no_invoices.</em></p>";
	}else{
	$display_block = "

	<div id=\"sorting\">
       		<div>Sorting tables, please hold on...</div>
	</div>

	<table width=100% align=center class=\"filterable sortable\" id=large>
	<div id=header><b>$mip_page_header</b> :: <a href='insert_preference.php'>$mip_actions_new_preference</a></div>
	<tr class=\"sortHeader\">
	<th class=\"noFilter\">$mip_table_action</th>
	<th class=\"index_table\">$mip_table_preference_id</th>
	<th class=\"index_table\">$mip_table_description</th>
	<th class=\"selectFilter index_table\">$wording_for_enabledField &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
	</tr>";

	while ($Array_preferences = mysql_fetch_array($result_print_preferences)) {
			$pref_idField = $Array_preferences['pref_id'];
			$pref_descriptionField = $Array_preferences['pref_description'];
			$pref_currency_signField = $Array_preferences['pref_currency_sign'];
			$pref_inv_headingField = $Array_preferences['pref_inv_heading'];
			$pref_inv_wordingField = $Array_preferences['pref_inv_wording'];
			$pref_inv_detail_headingField = $Array_preferences['pref_inv_detail_heading'];
			$pref_inv_detail_lineField = $Array_preferences['pref_inv_detail_line'];
			$pref_inv_payment_methodField = $Array_preferences['pref_inv_payment_method'];
			$pref_inv_payment_line1_nameField = $Array_preferences['pref_inv_payment_line1_name'];
			$pref_inv_payment_line1_valueField = $Array_preferences['pref_inv_payment_line1_value'];
			$pref_inv_payment_line2_nameField = $Array_preferences['pref_inv_payment_line2_name'];
			$pref_inv_payment_line2_valueField = $Array_preferences['pref_inv_payment_line2_value'];
			$pref_enabledField = $Array_preferences['pref_enabled'];
		
		        if ($pref_enabledField == 1) {
		                $wording_for_enabled = $wording_for_enabledField;
		        } else {
		                $wording_for_enabled = $wording_for_disabledField;
		        }


		$display_block .= "
		<tr class='index_table'>
		<td class='index_table'><a class='index_table' href='preference_details.php?submit=$pref_idField&action=view'>$mip_actions_view</a> :: <a class='index_table' href='preference_details.php?submit=$pref_idField&action=edit'>$mip_actions_edit</a> </td>
		<td class='index_table'>$pref_idField</td>
		<td class='index_table'>$pref_descriptionField</td>
	        <td class='index_table'>$wording_for_enabled</td>

		</tr>";
			
		
			}
			

		$display_block .="</table>";
	}



	?>
<html>
<head>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <script type="text/javascript" src="./include/jquery.js"></script>
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


<?php include('./include/menu.php'); ?>
<?php
$mid->printMenu('hormenu1');
$mid->printFooter();
?>
<script type="text/javascript" src="include/doFilter.js"></script>

<script type="text/javascript" src="./include/tablesorter.js"></script>

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


<title><?php echo $title; echo $mip_page_title; ?></title>
</head>
<?php include('./config/config.php'); ?>
<body>

<link rel="stylesheet" type="text/css" href="themes/<?php echo $theme; ?>/tables.css">
<br>
<div id="container">
<?php echo $display_block; ?>
<div id="footer"><a href="./documentation/text/inv_pref_what_the.html" class="greybox">Whats all this "Invoice Preference" stuff about?</a></div>
</div>
</div>

</body>
</html>
