<?php
include("./include/include_main.php");

//stop the direct browsing to this file - let index.php handle which files get displayed
if (!defined("BROWSE")) {
   echo "You Cannot Access This Script Directly, Have a Nice Day.";
   exit();
}

include('./config/config.php');

$conn = mysql_connect( $db_host, $db_user, $db_password);
mysql_select_db( $db_name, $conn);

# Deal with op and add some basic sanity checking

$op = !empty( $_POST['op'] ) ? addslashes( $_POST['op'] ) : NULL;


#insert product

if (  $op === 'insert_product' ) {

$sql = "INSERT into
		si_products
	VALUES
		(	
			'',
			'$_POST[prod_description]',
			'$_POST[prod_unit_price]',
			'$_POST[prod_custom_field1]',
			'$_POST[prod_custom_field2]',
			'$_POST[prod_custom_field3]',
			'$_POST[prod_custom_field4]',
			'$_POST[prod_notes]',
			'$_POST[prod_enabled]'
		)";

if (mysql_query($sql, $conn)) {
	$display_block =  "Product successfully added, <br> you will be redirected to the Manage Products page";
} else {
	$display_block =  "Something went wrong, please try adding the biller again";
}

	//header( 'refresh: 2; url=manage_products.php' );
	$refresh_total = "<META HTTP-EQUIV=REFRESH CONTENT=1;URL=index.php?module=products&view=manage>";
}



#edit product

else if (  $op === 'edit_product' ) {

$conn = mysql_connect("$db_host","$db_user","$db_password");
mysql_select_db("$db_name",$conn);

	if (isset($_POST['save_product'])) {
		$sql = "UPDATE
				si_products
			SET
				prod_description = '$_POST[prod_description]',
				prod_enabled = '$_POST[prod_enabled]',
				prod_notes = '$_POST[prod_notes]',
				prod_custom_field1 = '$_POST[prod_custom_field1]',
				prod_custom_field2 = '$_POST[prod_custom_field2]',
				prod_custom_field3 = '$_POST[prod_custom_field3]',
				prod_custom_field4 = '$_POST[prod_custom_field4]',
				prod_unit_price = '$_POST[prod_unit_price]'
			WHERE
				prod_id = '$_GET[submit]'";

		if (mysql_query($sql, $conn)) {
			$display_block =  "Product successfully edited, <br> you will be redirected back to the Manage Products";
		} else {
			$display_block =  "Something went wrong, please try editing the product again";
		}

		//header( 'refresh: 2; url=manage_products.php' );
		$refresh_total = "<META HTTP-EQUIV=REFRESH CONTENT=0;URL=index.php?module=products&view=manage>";


		}

	else if (isset($_POST['cancel'])) {
	
		//header( 'refresh: 0; url=manage_products.php' );
		$refresh_total = "<META HTTP-EQUIV=REFRESH CONTENT=0;URL=index.php?module=products&view=manage>";
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
{$display_block}
<br><br>
{$display_block_items}

EOD;
?>
<!-- ./src/include/design/footer.inc.php gets called here by controller srcipt -->
