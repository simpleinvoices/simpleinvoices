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

	
	$sql = "SELECT * FROM ".TB_PREFIX."log l WHERE l.timestamp BETWEEN :start AND :end ORDER BY l.timestamp";
	
	$sth = dbQuery($sql, ':start', $startdate, ':end', $enddate);
	$sqls = null;
	
	for($i=0;$res = $sth->fetch();$i++) {
		$sqls[$i] = $res;
	}
	
	
	$e_startdate = htmlspecialchars($startdate);
	$e_enddate = htmlspecialchars($enddate);
	echo <<<EOD
		<div style="text-align:left;">
		<form action="index.php?module=reports&view=database_log" method="post">
		<input type="text" class="date-picker" name="startdate" id="date1" value="$e_startdate" /><br /><br />
		<input type="text" class="date-picker" name="enddate" id="date1" value="$e_enddate" /><br /><br />
		<input type="submit" value="Show">
		</form>
EOD;

	echo "<br /><b>Invoice created</b><br />";
	foreach($sqls as $sql) {
		$pattern = "/.*INSERT\s+INTO\s+si_invoices\s+/im";
		
		if(preg_match($pattern,$sql['sqlquerie'])) {
			echo "User $sql[userid] created invoice $sql[last_id] on $sql[timestamp].<br />";
		}
	}

	echo "<br /><b>Invoice modified</b><br />";		
	foreach($sqls as $sql) {
		$pattern = "/.*UPDATE\s+si_invoices\s+SET/im";
		if(preg_match($pattern,$sql['sqlquerie'],$match)) {
			echo "User $sql[userid] modified invoice $match[1] on $sql[timestamp].<br />";
		}
	}
	
	echo "<br /><b>Payment Process</b><br />";
	global $db_server;
	foreach($sqls as $sql) {
		if ($db_server == 'pgsql') {
			$pattern = "/.*INSERT\s+INTO\s+si_account_payments\s+/im";
		} else {
			$pattern = "/.*INSERT\s+INTO\s+si_account_payments\s+/im";
		}
		if(preg_match($pattern,$sql['sqlquerie'],$match)) {
			echo "User $sql[userid] processed invoice $match[1] on $sql[timestamp] with amount $match[2].<br />";
		}
	}
	
	echo "</div>";
	
?>
