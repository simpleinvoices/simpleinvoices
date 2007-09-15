<?php

include("../config/config.php");

$conn = mysql_connect( $db_host, $db_user, $db_password,true );
$db = mysql_select_db( $db_name, $conn );



$sql = "SELECT * FROM si_custom_fields";
$query = mysql_query($sql);

while($res = mysql_fetch_array($query)) {
	//echo $res['cf_custom_field']."<br />";
	
	if($res['cf_custom_label'] != NULL) {
		echo $res['cf_custom_label'];
		
		if(preg_match("/(.+)_cf([1-4])/",$res['cf_custom_field'],$match)) {
			print_r($match);
		}
	}
}

?>