<?php

	checkLogin();
	
	$today = strtotime("today");
	$yesterday = strtotime("yesterday");
	
	//echo $today;
	//echo $yesterday;
	
	$sql = "SELECT * FROM si_log ORDER BY timestamp";
	$query = mysqlQuery($sql);
	
	$pattern = "/.*INSERT INTO si_invoices /i";

	while($res = mysql_fetch_array($query)) {
		
		$pattern = "/.*INSERT INTO si_invoices /i";
		if(preg_match($pattern,$res['sqlquerie'])) {
			echo "User $res[userid] created invoice $res[last_id] on $res[timestamp].<br />";
		}
		
		$pattern = "/.*UPDATE si_invoices .* WHERE id [^0-9]*([0-9]+)/i";
		if(preg_match($pattern,$res['sqlquerie'],$match)) {
			echo "User $res[userid] modified invoice $match[1] on $res[timestamp].<br />";
		}
	}
	

?>