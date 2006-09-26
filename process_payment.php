<?php
include('./config/config.php');
include("./lang/$language.inc.php");
/* validataion code */
include("./include/validation.php");

$today = date("Y-m-d");

$conn = mysql_connect("$db_host","$db_user","$db_password");
mysql_select_db("$db_name",$conn);

#get max invoice id for validataion - start
$sql_max = "SELECT max(inv_id) as max_inv_id FROM si_invoices";
$result_max = mysql_query($sql_max, $conn) or die(mysql_error());

while ($Array_max = mysql_fetch_array($result_max) ) {
                $max_invoice_id = $Array_max['max_inv_id'];
};

#get max invoice id for validataion - end


jsBegin();
jsFormValidationBegin("frmpost");
#jsValidateifNum("ac_inv_id","Invoice ID");
jsPaymentValidation("ac_inv_id","Invoice ID",1,$max_invoice_id);
jsValidateifNum("ac_amount","Amount");
jsValidateifNum("ac_date","Date");
jsFormValidationEnd();
jsEnd();


/* end validataion code */


$master_invoice_id = $_GET['submit'];

#master invoice id select
if (!empty($master_invoice_id)) {
$print_master_invoice_id = 'SELECT * FROM si_invoices WHERE inv_id = ' . $master_invoice_id;
}
elseif (empty($master_invoice_id)) {
$print_master_invoice_id = 'SELECT * FROM si_invoices';
}
$result_print_master_invoice_id  = mysql_query($print_master_invoice_id , $conn) or die(mysql_error());

while ($Array_master_invoice = mysql_fetch_array($result_print_master_invoice_id)) {
                $inv_idField = $Array_master_invoice['inv_id'];
                $inv_biller_idField = $Array_master_invoice['inv_biller_id'];
                $inv_customer_idField = $Array_master_invoice['inv_customer_id'];
                $inv_typeField = $Array_master_invoice['inv_type'];
                $inv_preferenceField = $Array_master_invoice['inv_preference'];
                $inv_dateField = date( $config['date_format'], strtotime( $Array_master_invoice['inv_date'] ) );
                $inv_noteField = $Array_master_invoice['inv_note'];


};

#customer query
$print_customer = "SELECT * FROM si_customers WHERE c_id = $inv_customer_idField";
$result_print_customer = mysql_query($print_customer, $conn) or die(mysql_error());


while ($Array = mysql_fetch_array($result_print_customer)) {
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
};

#biller query
$print_biller = "SELECT * FROM si_biller WHERE b_id = $inv_biller_idField";
$result_print_biller = mysql_query($print_biller, $conn) or die(mysql_error());

while ($billerArray = mysql_fetch_array($result_print_biller)) {
                $b_idField = $billerArray['b_id'];
                $b_nameField = $billerArray['b_name'];
                $b_street_addressField = $billerArray['b_street_address'];
                $b_cityField = $billerArray['b_city'];
                $b_stateField = $billerArray['b_state'];
                $b_zip_codeField = $billerArray['b_zip_code'];
                $b_countryField = $billerArray['b_country'];
                $b_phoneField = $billerArray['b_phone'];
                $b_mobile_phoneField = $billerArray['b_mobile_phone'];
                $b_faxField = $billerArray['b_fax'];
                $b_emailField = $billerArray['b_email'];
};

#biller query
$sql = "SELECT * FROM si_payment_types where pt_enabled != 0";
$result = mysql_query($sql, $conn) or die(mysql_error());



#DEFAULTS
#defaults query and DEFAULT NUMBER OF LINE ITEMS
$sql_defaults = "SELECT * FROM si_defaults";
$result_defaults = mysql_query($sql_defaults, $conn) or die(mysql_error());

while ($Array_defaults = mysql_fetch_array($result_defaults) ) {
                $def_payment_typeField = $Array_defaults['def_payment_type'];
};

#Get the names of the defaults from their id -start
#default biller name query
$sql_payment_type_default = "SELECT pt_description FROM si_payment_types where pt_id = $def_payment_typeField";
$result_payment_type_default = mysql_query($sql_payment_type_default , $conn) or die(mysql_error());

while ($Array = mysql_fetch_array($result_payment_type_default) ) {
                $sql_payment_type_desciptionField = $Array['pt_description'];
}



#biller selector

if (mysql_num_rows($result) == 0) {
        //no records
        $display_block_payment_type = "<p><em>Sorry, no payment types available, please insert one</em></p>";

} else {
        //has records, so display them
        $display_block_payment_type = "
        <select name=\"ac_payment_type\">
        <option selected value=\"$def_payment_typeField\" style=\"font-weight: bold\">$sql_payment_type_desciptionField</option>";

        while ($recs = mysql_fetch_array($result)) {
                $id = $recs['pt_id'];
                $display_name = $recs['pt_description'];

                $display_block_payment_type .= "<option value=\"$id\">
                        $display_name</option>";
        }
}










#Accounts - for the invoice - start
#invoice total calc - start

        $print_invoice_total ="select sum(inv_it_total) as total from si_invoice_items where inv_it_invoice_id =$inv_idField";
        $result_print_invoice_total = mysql_query($print_invoice_total, $conn) or die(mysql_error());

        while ($Array = mysql_fetch_array($result_print_invoice_total)) {
                $invoice_total_Field = $Array['total'];
#invoice total calc - end

#amount paid calc - start
        $x1 = "select IF ( isnull(sum(ac_amount)) , '0', sum(ac_amount)) as amount from si_account_payments where ac_inv_id = $inv_idField";
        $result_x1 = mysql_query($x1, $conn) or die(mysql_error());
        while ($result_x1Array = mysql_fetch_array($result_x1)) {
                $invoice_paid_Field = $result_x1Array['amount'];
#amount paid calc - end

#amount owing calc - start
        $invoice_owing_Field = $invoice_total_Field - $invoice_paid_Field;
#amount owing calc - end
	}
	}
#Accounts - for the invoice - end

# Deal with op and add some basic sanity checking

$op = !empty( $_GET['op'] ) ? addslashes( $_GET['op'] ) : NULL;


if ($op === "pay_selected_invoice") {


$display_block = "
<table align=center>
	<tr>
		<td colspan=4 align=center><b>Process Payment</b></th>
	</tr>
	<tr>
		<td class='details_screen'>Invoice ID</td><td><input type=hidden name=\"ac_inv_id\" value=\"$inv_idField\">$inv_idField</td><td class='details_screen'>Total</td><td>$invoice_total_Field</td>
	</tr>
	<tr>
		<td class='details_screen'>Biller</td><td>$b_nameField</td><td class='details_screen'>Paid</td><td>$invoice_paid_Field</td>
	</tr>
	<tr>
		<td class='details_screen'>Customer</td><td>$c_nameField</td><td class='details_screen'>Owing</td><td><u>$invoice_owing_Field</u></td>
	</tr>
	<tr>
		<td class='details_screen'>Amount</td><td colspan=5><input type=text name=\"ac_amount\" size=25></td>
	</tr>
        <tr>
                        <td class='details_screen'>Date (YYYY-MM-DD)</td><td><input type=\"text\" class=\"date-picker\" name=\"ac_date\" id=\"date1\" value=$today /></td>
        </tr>
        <tr>
                <td class='details_screen'>Payment Type/Method</td><td>$display_block_payment_type</td>
        </tr>
	<tr>
		<td class='details_screen'>Note</td><td colspan=5><textarea input type=text name=\"ac_notes\" rows=5 cols=50></textarea></td>
	</tr>
</table>";

$insert_action_op = "pay_selected_invoice";

}
/*Code for the when the user want to process a payment and manually enter the invoice id ie, not come from print_preview - come from Process Payment menu item */
else if ($op === "pay_invoice") {
$display_block = "

<!-- jquery autocomplete sweet stuff - start -->
<style type=\"text/css\">
.ac_wrapper {
	width: 300px;
	position: relative;
}
.ac_wrapper input {
	width: 100%;
	display: block;
}
.ac_wrapper ul {
	list-style: none;
	padding: 0;
	margin: 0;
}
.ac_results {
	background: #ccc;
	cursor: pointer;
	position: absolute;
	left:0;
}
.ac_results li {
	padding: 2px 5px;
}
.ac_loading {
	background : url('./images/indicator-js.gif') right center no-repeat;
}
.over {
	background: yellow;
}
</style>

<script type=\"text/javascript\">
function selectItem(li) {
//        document.frmpost.js_total.value = \"That's \" + li.extra[0] + \" you picked.\"
	if (li.extra) {
		document.getElementById(\"js_total\").innerHTML= \" \" + li.extra[0] + \" \"
//		alert(\"That's '\" + li.extra[0] + \"' you picked.\")
	}
}
function formatItem(row) {
	return row[0] + \"<br><i>\" + row[1] + \"</i>\";
}
$(document).ready(function() {
	$(\"#ac_me\").autocomplete(\"auto_complete_search.php\", { minChars:1, matchSubset:1, matchContains:1, cacheLength:10, onItemSelect:selectItem, formatItem:formatItem, selectOnly:1 });
});
</script>

<!-- jquery autocomplete sweet stuff - end -->


<table align=center>
        <tr>
                <td colspan=6 align=center><b>Process Payment</b></th>
        </tr>
        <tr>
                <td class='details_screen'>Invoice ID <a href='text/process_payment_inv_id.html' class='greybox'><font color=blue>*</font></a></td><td><input type=text id=\"ac_me\" name=\"ac_inv_id\" /></td>
        </tr>
	<tr>
		<td class='details_screen'>Details <a href='text/process_payment_details.html' class='greybox' ><font color=blue>*</font></a></td><td id =\"js_total\"><i>Please select an invoice</i> </td>
	</tr>
        <tr>
                <td class='details_screen'>Amount</td><td colspan=5><input type=text name=\"ac_amount\" size=25></td>
        </tr>
	<tr>
                <div class=\"demo-holder\">
			<td class='details_screen'>Date (YYYY-MM-DD)</td><td><input type=\"text\" class=\"date-picker\" name=\"ac_date\" id=\"date1\" value=$today /></td>
                </div>
	</tr>
        <tr>
                <td class='details_screen'>Payment Type/Method</td><td>$display_block_payment_type</td>
        </tr>
        <tr>
                <td class='details_screen'>Note</td><td colspan=5><textarea input type=text name=\"ac_notes\" rows=5 cols=50></textarea></td>
        </tr>
</table>
";

$insert_action_op = "pay_invoice";

}
else if ($op === "pay_invoice_batch") {
}




?>

<html>
<head>
<link rel="stylesheet" type="text/css" href="include/jquery-datePicker.css" title="default" media="screen" />
<script type="text/javascript" src="include/jquery.js"></script>
<script type="text/javascript" src="include/jquery-dom_creator.js"></script>
<script type="text/javascript" src="include/jquery-datePicker.js"></script>
		<script type='text/javascript' src='include/jquery_autocomplete.js'></script>
<?php include('./include/menu.php'); ?>


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


<script type="text/javascript" src="niftycube.js"></script>
<script type="text/javascript">
window.onload=function(){
Nifty("div#container");
Nifty("div#content,div#nav","same-height small");
Nifty("div#header,div#footer","small");
}
</script>

<!-- *Date selcetor js* - Start -->
<script type="text/javascript">
$(document).ready(init);
function init()
{
	$.datePicker.setDateFormat('yyyy-mm-dd');
	$('input#date1').datePicker({startDate:'01/01/1970'});
}
</script>
<!-- *Date selcetor js* - End -->

<script language="javascript" type="text/javascript" src="include/tiny_mce/tiny_mce_src.js"></script>
<script language="javascript" type="text/javascript">
tinyMCE.init({
mode : "textareas",
        theme : "advanced",
        theme_advanced_buttons1 : "bold,italic,underline,separator,strikethrough,justifyleft,justifycenter,justifyright, justifyfull,bullist,numlist,undo,redo",
        theme_advanced_buttons2 : "",
        theme_advanced_buttons3 : "",
        theme_advanced_toolbar_location : "top",
        theme_advanced_toolbar_align : "left",
        extended_valid_elements : "a[name|href|target|title|onclick],img[class|src|border=0|alt|title|hspace|vspace|width|height|align|onmouseover|onmouseout|name],hr[class|width|size|noshade],font[face|size|color|style],span[class|align|style]"
});
</script>

</head>
<title>Simple Invoices - Process Payment</title>
<?php include('./config/config.php'); ?>

<BODY>
<?php
$mid->printMenu('hormenu1');
$mid->printFooter();
?>

<link rel="stylesheet" type="text/css" href="themes/<?php echo $theme; ?>/tables.css">
<br>

<FORM name="frmpost" ACTION="insert_action.php" METHOD=POST onsubmit="return frmpost_Validator(this)">
<div id="container">
<div id="header">
</div>

<?php echo $display_block; ?>

<div id="footer">

<p><input type=submit name="submit" value="Process Payment"><input type=hidden name="op" value="<?php echo $insert_action_op;?>"></p>
</div>
</div>


</FORM>
</BODY>
</HTML>







