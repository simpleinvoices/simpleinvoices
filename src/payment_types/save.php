<?php
include('./include/include_main.php'); 
include('./config/config.php');

$conn = mysql_connect( $db_host, $db_user, $db_password);
mysql_select_db( $db_name, $conn);

# Deal with op and add some basic sanity checking

$op = !empty( $_POST['op'] ) ? addslashes( $_POST['op'] ) : NULL;

#insert payment type

if (  $op === 'insert_payment_type' ) {

$conn = mysql_connect("$db_host","$db_user","$db_password");
mysql_select_db("$db_name",$conn);

/*Raymond - what about the '', bit doesnt seem to do an insert in me environment when i exclude it
$sql = "INSERT INTO si_tax VALUES ('$_POST[tax_description]','$_POST[tax_percentage]')";
*/

$sql = "INSERT into
		si_payment_types
	VALUES
		(	
			'',
			'$_POST[pt_description]',
			'$_POST[pt_enabled]'
		)";

if (mysql_query($sql, $conn)) {
	$display_block =  "Payment Type successfully added, <br> you will be redirected to the Manage Payment Types page";
} else {
	$display_block =  'Something went wrong, please try adding the tax rate again';
}

//header( 'refresh: 2; url=manage_payment_types.php' );
$refresh_total = "<META HTTP-EQUIV=REFRESH CONTENT=2;URL=index.php?module=payment_types&view=manage>";


}


#edit payment type

else if (  $op === 'edit_payment_type' ) {

	$conn = mysql_connect("$db_host","$db_user","$db_password");
	mysql_select_db("$db_name",$conn);

	if (isset($_POST['save_payment_type'])) {
		$sql = "UPDATE
				si_payment_types
			SET
				pt_description = '$_POST[pt_description]',
				pt_enabled = '$_POST[pt_enabled]'
			WHERE
				pt_id = " . $_GET['submit'];

		if (mysql_query($sql, $conn)) {
			$display_block =  "Payment Type successfully edited, <br> you will be redirected back to the Manage Payment Types";
		} else {
			$display_block =  'Something went wrong, please try editing the tax rate again';
		}

		//header( 'refresh: 2; url=manage_payment_types.php' );
		$refresh_total = "<META HTTP-EQUIV=REFRESH CONTENT=2;URL=index.php?module=payment_types&view=manage>";

		}

	else if (isset($_POST['cancel'])) {

		//header( 'refresh: 0; url=manage_payment_types.php' );
		$refresh_total = "<META HTTP-EQUIV=REFRESH CONTENT=0;URL=index.php?module=payment_types&view=manage>";

	}
}



?>

<html>
<head>
<?php

include('./include/include_main.php');

$refresh_total = isset($refresh_total) ? $refresh_total : '&nbsp';
$display_block_items = isset($display_block_items) ? $display_block_items : '&nbsp;';
echo <<<EOD
{$refresh_total}
</head>

<body>

EOD;

echo <<<EOD
<br>
<br>
       <div id="browser">

{$display_block}
<br><br>
{$display_block_items}

EOD;

include("footer.inc.php");
?>
</body>
</html>
