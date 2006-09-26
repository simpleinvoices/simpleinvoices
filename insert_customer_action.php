<?php
#insert customer
if ($_POST[op] = "insert_customer") {
$conn = mysql_connect("$db_host","$db_user","$db_password");
mysql_select_db("$db_name",$conn);

$sql = "INSERT into si_customers values ('','$_POST[c_attention]','$_POST[c_name]','$_POST[c_street_address]','$_POST[c_city]','$_POST[c_state]','$_POST[c_zip_code]','$_POST[c_phone]','$_POST[c_fax]','$_POST[c_email]')";

if (mysql_query($sql, $conn)) {
        $display_block =  "Customer successfully added,<br> you will be redirected back to the home page in 5 seconds";
} else {
        $display_block =  "Something went wrong, please try adding the customer again";
}

header( 'refresh: 5; url=index.php' );

}

/*
#insert biller

else if ($_POST[op] = "insert_biller") {

$sql = "INSERT into si_biller values ('','$_POST[b_name1]','$_POST[b_name2]','$_POST[b_street_address]','$_POST[b_city]','$_POST[b_state]','$_POST[b_zip_code]','$_POST[b_phone]','$_POST[b_fax]','$_POST[b_email]')";

if (mysql_query($sql, $conn)) {
        $display_block =  "Biller successfully added";
} else {
        $display_block =  "Something went wrong, please try adding the biller again";
}
*/
?>

<html>
<head>
<title> Words available
</title>
<?php include('./include/menu1.php'); ?>
<?php include('./config/config.php'); ?>

<BODY>
<link rel="stylesheet" type="text/css" href="themes/<?php echo $theme; ?>/style.css">
<br>

<link rel="stylesheet" type="text/css" href="css/rhdocs.css">
<br>
<?php echo $display_block; ?>
</body>
</html>
