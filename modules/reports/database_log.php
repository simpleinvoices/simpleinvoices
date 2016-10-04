<?php

	checkLogin();

$smarty -> assign('pageActive', 'report');
$smarty -> assign('active_tab', '#home');
	
	$smarty -> display("../templates/default/menu.tpl");
	$smarty -> display("../templates/default/main.tpl");
	
	$startdate	= (isset($_POST['startdate'])) ? $_POST['startdate'] : date("Y-m-d",strtotime("last Year"));
	$startdate = htmlsafe($startdate);
	$enddate	= (isset($_POST['enddate']))   ? $_POST['enddate']   : date("Y-m-d",strtotime("now"));
	$enddate = htmlsafe($enddate);
	
	$sql = "SELECT l.*, u.email
	FROM
	".TB_PREFIX."log l INNER JOIN 
	".TB_PREFIX."user u ON (u.id = l.userid AND u.domain_id = l.domain_id)
	WHERE l.domain_id = :domain_id 
	  AND l.timestamp BETWEEN :start AND :end 
	ORDER BY l.timestamp";
	
	$sth = dbQuery($sql, ':start', $startdate, ':end', $enddate, ':domain_id', $auth_session->domain_id);
	$sqls = null;
	$sqls = $sth->fetchAll();
	
	echo <<<EOD
		<div style="text-align:left;">
		<br /><br />
		<form action="index.php?module=reports&amp;view=database_log" method="post">
		<input type="text" class="date-picker" name="startdate" id="date1" value='$startdate' /><br /><br />
		<input type="text" class="date-picker" name="enddate" id="date2" value='$enddate' /><br /><br />
		<input type="submit" value="Show">
		</form>
EOD;

	echo "<br /><b>Invoice created</b><br />";
	foreach($sqls as $sql) {
		$pattern = "/.*INSERT\s+INTO\s+".TB_PREFIX."invoices\s+/im";
		
		if(preg_match($pattern,$sql['sqlquerie'])) {
			$user = htmlsafe($sql['email']).' (id '.htmlsafe($sql['userid']).')';
			echo "User $user created invoice $sql[last_id] on $sql[timestamp].<br />";
		}
	}

	echo "<br /><b>Invoice modified</b><br />";		
	foreach($sqls as $sql) {
		$pattern = "/.*UPDATE\s+".TB_PREFIX."invoices\s+SET/im";
		if(preg_match($pattern,$sql['sqlquerie'],$match)) {
			$user = htmlsafe($sql['email']).' (id '.htmlsafe($sql['userid']).')';
			echo "User $user modified invoice $match[1] on $sql[timestamp].<br />";
		}
	}
	
	echo "<br /><b>Payment Process</b><br />";

	foreach($sqls as $sql) {
	
		$pattern = "/.*INSERT\s+INTO\s+".TB_PREFIX."payment\s+/im";
		
		if(preg_match($pattern,$sql['sqlquerie'],$match)) {
			$user = htmlsafe($sql['email']).' (id '.htmlsafe($sql['userid']).')';
			echo "User $user processed invoice $match[1] on $sql[timestamp] with amount $match[2].<br />";
		}
	}
	
	echo "</div>";
exit();	
?>
