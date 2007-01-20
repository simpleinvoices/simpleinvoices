<?php
include('./config/config.php');

$conn = mysql_connect( $db_host, $db_user, $db_password);
mysql_select_db( $db_name, $conn);

# Deal with op and add some basic sanity checking

$op = !empty( $_POST['op'] ) ? addslashes( $_POST['op'] ) : NULL;

#insert tax rate

if (  $op === 'insert_tax_rate' ) {

$conn = mysql_connect("$db_host","$db_user","$db_password");
mysql_select_db("$db_name",$conn);

/*Raymond - what about the '', bit doesnt seem to do an insert in me environment when i exclude it
$sql = "INSERT INTO si_tax VALUES ('$_POST[tax_description]','$_POST[tax_percentage]')";
*/

$sql = "INSERT into
		si_tax
	VALUES
		(	
			'',
			'$_POST[tax_description]',
			'$_POST[tax_percentage]',	
			'$_POST[tax_enabled]'
		)";

if (mysql_query($sql, $conn)) {
	$display_block =  "Tax rate successfully added, <br> you will be redirected to the Manage Tax Rates page";
} else {
	$display_block =  'Something went wrong, please try adding the tax rate again';
}

//header( 'refresh: 2; url=manage_tax_rates.php' );
$refresh_total = "<META HTTP-EQUIV=REFRESH CONTENT=2;URL=index.php?module=tax_rates&view=manage>";
}



#edit tax rate

else if (  $op === 'edit_tax_rate' ) {

$conn = mysql_connect("$db_host","$db_user","$db_password");
mysql_select_db("$db_name",$conn);

	if (isset($_POST['save_tax_rate'])) {
		$sql = "UPDATE
				si_tax
			SET
				tax_description = '$_POST[tax_description]',
				tax_percentage = '$_POST[tax_percentage]',
				tax_enabled = '$_POST[tax_enabled]'
			WHERE
				tax_id = " . $_GET['submit'];

		if (mysql_query($sql, $conn)) {
			$display_block =  "Tax Rate successfully edited, <br> you will be redirected back to the Manage Tax Rates";
		} else {
			$display_block =  'Something went wrong, please try editing the tax rate again';
		}

		//header( 'refresh: 2; url=manage_tax_rates.php' );
		$refresh_total = "<META HTTP-EQUIV=REFRESH CONTENT=2;URL=index.php?module=tax_rates&view=manage>";

		}

	else if (isset($_POST['cancel'])) {

		//header( 'refresh: 0; url=manage_tax_rates.php' );
		$refresh_total = "<META HTTP-EQUIV=REFRESH CONTENT=2;URL=index.php?module=tax_rates&view=manage>";
	}
}

?>

<html>
<head>
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
