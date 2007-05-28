<?php

	checkLogin();
	
	
	if(isset($_POST['startdate'])) {
		$startdate = $_POST['startdate'];
	}
	else {
		$startdate = date("Y-m-d",strtotime("today"));
	}
	
	if(isset($_POST['enddate']) && $_POST['enddate'] != "") {
		$enddate = $_POST['enddate'];
	}
	else {
		$enddate = date("Y-m-d",strtotime("tomorrow"));
	}

	
	$sql = "SELECT * FROM {$tb_prefix}log WHERE (timestamp >= '$startdate' && timestamp < '$enddate') ORDER BY timestamp";
	
	$query = mysqlQuery($sql);
	$sqls = null;
	
	for($i=0;$res = mysql_fetch_array($query);$i++) {
		$sqls[$i] = $res;
	}
	
	
	echo <<<EOD
		<div style="text-align:left;">
		<form action="index.php?module=reports&view=database_log" method="post">
		<input type="text" class="date-picker" name="startdate" id="date1" value="$startdate" /><br /><br />
		<input type="text" class="date-picker" name="enddate" id="date1" value="$enddate" /><br /><br />
		<input type="submit" value="Anzeigen">
		</form>
EOD;

	echo "<br /><b>Invoice created</b><br />";
	foreach($sqls as $sql) {
		$pattern = "/.*INSERT INTO si_invoices /i";
		
		if(preg_match($pattern,$sql['sqlquerie'])) {
			echo "User $sql[userid] created invoice $sql[last_id] on $sql[timestamp].<br />";
		}
	}

	echo "<br /><b>Invoice modified</b><br />";		
	foreach($sqls as $sql) {
		$pattern = "/.*UPDATE si_invoices .* WHERE id [^0-9]*([0-9]+)/i";
		if(preg_match($pattern,$sql['sqlquerie'],$match)) {
			echo "User $sql[userid] modified invoice $match[1] on $sql[timestamp].<br />";
		}
	}
	
	echo "<br /><b>Payment Process</b><br />";		
	foreach($sqls as $sql) {
		$pattern = "/.*INSERT INTO si_account_payments VALUES \( '', '([0-9]+)', '([0-9]+)',/i";
		if(preg_match($pattern,$sql['sqlquerie'],$match)) {
			echo "User $sql[userid] processed invoice $match[1] on $sql[timestamp] with amount $match[2].<br />";
		}
	}
	
	echo "</div>";
	
?>