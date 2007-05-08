<?php

//stop the direct browsing to this file - let index.php handle which files get displayed
checkLogin();

$sql = "SELECT * FROM {$tb_prefix}sql_patchmanager ORDER BY sql_id";                  
$query = mysql_query($sql) or die(mysql_error());

	
getRicoLiveGrid("rico_sqlpatches","{ type:'number', decPlaces:0, ClassName:'alignleft' },,{ type:'number', decPlaces:0, ClassName:'alignleft'}");

echo <<<EOD
<!--[if gte IE 5.5]>
<link rel="stylesheet" type="text/css" href="./modules/include/css/iehacks.css" media="all"/>
<![endif]-->
EOD;

echo <<<EOD
<h2>Database patches applied to Simple Invoices</h2>
<hr></hr>


	<table align="center" class="ricoLiveGrid manage" id="rico_sqlpatches">
	<colgroup>
	<col style='width:20%;' />
	<col style='width:60%;' />
	<col style='width:20%;' />
	</colgroup>
	<thead>
	<tr>
	<th class="sortable">Patch ID</th>
	<th class="sortable">Description</th>
	<th class="sortable">Release</th>
	</tr>
	</thead>
EOD;

while ($patch = mysql_fetch_array($query)) {
	
	echo "
	<tr>
	<td class='index_table'>$patch[sql_patch_ref]</td>
	<td class='index_table'>$patch[sql_patch]</td>
	<td class='index_table'>$patch[sql_release]</td>
	</tr>";

}
		

echo "</table>";
	

?>
