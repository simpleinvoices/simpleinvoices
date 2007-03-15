<?php
include('./include/include_main.php');

//stop the direct browsing to this file - let index.php handle which files get displayed
checkLogin();

#insert customer
$conn = mysql_connect("$db_host","$db_user","$db_password");
mysql_select_db("$db_name",$conn);


$sql = "select * from {$tb_prefix}sql_patchmanager";
                     
$result = mysql_query($sql, $conn) or die(mysql_error());
$number_of_rows = mysql_num_rows($result);


if (mysql_num_rows($result) == 0) {
$display_block = "<P><em>No SQL patches have been applied to the database.</em></p>";
}else{
$display_block = <<<EOD

<table align=center>
<div id=header>
<tr>
<th>Action</th>
<th>SQL ID</th>
<th>SQL Patch</th>
<th>SQL Release</th>
</tr>
EOD;

while ($Array = mysql_fetch_array($result)) {
	$sql_idField = $Array['sql_id'];
	$sql_patchField = $Array['sql_patch'];
	$sql_releaseField = $Array['sql_release'];
	
	$display_block .= "
	<tr>
	<td><a href='sql_patch_details.php?submit=$sql_idField&action=view'>View</a> - <a href='sql_patch_details.php?submit=$sql_idField&action=edit'>Edit</a> </td>
	<td>$sql_idField</td>
	<td>$sql_patchField</td>
	<td>$sql_releaseField</td>
	</tr>";

                
	
		}
		

        $display_block .="</table>";
}

include('./config/config.php');


echo <<<EOD
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script type="text/javascript">
window.onload=function(){
Nifty("div#container");
Nifty("div#content,div#nav","same-height small");
Nifty("div#header,div#footer","small");
}
</script>
<body>

<br>
<div id="container">
$display_block
<div id="footer"></div>
</div>
</div>

</body>

EOD;
?>

