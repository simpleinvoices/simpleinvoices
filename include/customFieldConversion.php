<?php
include("manageCustomFields.php");
include("../config/config.php");

$conn = mysql_connect( $db_host, $db_user, $db_password,true );
$db = mysql_select_db( $db_name, $conn );


/* check if renamed */
/*$sql = "SELECT * FROM si_custom_fields";
$query = mysql_query($sql);

while($custom = mysql_fetch_array($query)) {
	//echo $res['cf_custom_field']."<br />";
	
	if($custom['cf_custom_label'] != NULL) {
		//echo $custom['cf_custom_label'];
		
		if(preg_match("/(.+)_cf([1-4])/",$custom['cf_custom_field'],$match)) {
			print_r($match);
			if($match[1] != "biller") {
				$sql = "SELECT custom_field".$match[2]." FROM si_$match[1]s";
			}
			else {
				$sql = "SELECT custom_field".$match[2]." FROM si_$match[1]";
			}
			
			//error_log($sql);
			$query2 = mysql_query($sql);
			
			while($res = mysql_fetch_array($query2)) {
				echo($res[0]."<br />");
			}
			//echo $sql;
		}
	}
}*/


/* check if any value set -> keeps all data for sure */
$sql = "SELECT * FROM si_custom_fields";
$query = mysql_query($sql);

while($custom = mysql_fetch_array($query)) {
	if(preg_match("/(.+)_cf([1-4])/",$custom['cf_custom_field'],$match)) {
		//print_r($match);
		
		switch($match[1]) {
			case "biller": $cat = 1;	break;
			case "customer": $cat = 2;	break;
			case "product": $cat = 3;	break;
			case "invoice": $cat = 4;	break;
			default: $case = 0;
		}
		if($match[1] != "biller") {
			$sql = "SELECT id,custom_field".$match[2]." FROM si_$match[1]s";
		}
		else {
			$sql = "SELECT id,custom_field".$match[2]." FROM si_$match[1]";
		}
		
		//error_log($sql);
		$query2 = mysql_query($sql);
		$store = false;
		
		while($res = mysql_fetch_array($query2)) {
			if($res[1] != NULL) {
				$store = true;
				break;
			}
			//echo($res[0]."<br />");
		}
		if($store) {
			print_r($res);
			echo "<br />".$sql."   ".$res['id'];
			//create new custom field

			/*saveCustomField(3,$cat,$custom['cf_custom_field'],$custom['cf_custom_label']);
			$id = mysql_insert_id();
			$plugin = getPluginById($id);
			$plugin->saveInput($value, $res['id']);*/
			
			//insert all data


		}
	}
}

?>