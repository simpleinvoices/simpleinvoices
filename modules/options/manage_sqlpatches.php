<?php

//stop the direct browsing to this file - let index.php handle which files get displayed
checkLogin();

$sql = "SELECT * FROM {$tb_prefix}sql_patchmanager ORDER BY sql_id";                  
$query = mysql_query($sql) or die(mysql_error());

echo <<<EOD
<h2>Here is a list of all Sql-patches</h2>
<table align="center">
<tr>
<th>SQL ID</th>
<th>SQL Patch</th>
<th>SQL Release</th>
</tr>
EOD;

while ($patch = mysql_fetch_array($query)) {
	
	echo "
	<tr>
	<td>$patch[sql_id]</td>
	<td>$patch[sql_patch]</td>
	<td>$patch[sql_patch]</td>
	</tr>";

}
		

echo "</table>";


?>

