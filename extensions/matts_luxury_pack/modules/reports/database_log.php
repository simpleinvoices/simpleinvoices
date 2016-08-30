<?php

	checkLogin();


	
	$startdate 	= (isset($_POST['startdate'])) ? $_POST['startdate'] : date("Y-m-d",strtotime("last Year"));
	$startdate 	= htmlsafe($startdate);
	$enddate 	= (isset($_POST['enddate']))   ? $_POST['enddate']   : date("Y-m-d",strtotime("now"));
	$enddate 	= htmlsafe($enddate);
	
	$sql = "SELECT l.*, u.email
	FROM
	".TB_PREFIX."log l 
	INNER JOIN 
	".TB_PREFIX."user u ON (u.id = l.userid AND u.domain_id = l.domain_id)
	WHERE l.domain_id = :domain_id 
	  AND l.timestamp BETWEEN :start AND :end 
	ORDER BY l.timestamp";
/*	INNER JOIN
	".TB_PREFIX."invoices v ON (v.id = l.last_id AND v.domain_id = l.domain_id)
	INNER JOIN
	".TB_PREFIX."payment p ON (p.id = l.last_id AND p.domain_id = l.domain_id)
*/	
	$sth 	= dbQuery($sql, ':start', $startdate, ':end', $enddate, ':domain_id', $auth_session->domain_id);
	$sqls 	= null;
	$sqls 	= $sth->fetchAll();
	
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
	foreach ($sqls as $sql) {
		$pattern = "/.*INSERT\s+INTO\s+".TB_PREFIX."invoices\s+/im";
		
		if (preg_match($pattern,$sql['sqlquerie'])) {
			preg_match('/,\s?\'(\d+)/i',$sql['proposed'],$match);
			$var = isset($match[1]) ? $match[1] : '';
			$user = htmlsafe($sql['email']).' (id '.htmlsafe($sql['userid']).')';
//			echo "User $user created invoice $sql[last_id] on $sql[timestamp].<br />";
			echo "User $user created invoice $var on $sql[timestamp].<br />";
		}
	}

	echo "<br /><b>Invoice modified</b><br />";		
	foreach ($sqls as $sql) {
		$pattern = "/.*UPDATE\s+".TB_PREFIX."invoices\s+SET/im";
		if (preg_match($pattern,$sql['sqlquerie'],$match)) {
			preg_match('/index_id\s?=\s?\'(\d+)/i',$sql['proposed'],$match);
			$var = isset($match[1]) ? $match[1] : '';
			$user = htmlsafe($sql['email']).' (id '.htmlsafe($sql['userid']).')';
			echo "User $user modified invoice $var on $sql[timestamp].<br />";
		}
	}
	
	echo "<br /><b>Payment Process</b><br />";

	foreach ($sqls as $sql) {
	
		$pattern = "/.*INSERT\s+INTO\s+".TB_PREFIX."payment\s+/im";
		
		if (preg_match($pattern,$sql['sqlquerie'],$match)) {
			$user = htmlsafe($sql['email']).' (id '.htmlsafe($sql['userid']).')';
			preg_match('/\(\s?.?(\d+)[^,]+,.+?(\d+)/i',$sql['proposed'],$match);
			$var1 = isset($match[1]) ? $match[1] : "";
			if ($var1) {
				$invsql = "SELECT * FROM ".TB_PREFIX."invoices WHERE id = $var1";
				$sth 	= dbQuery($invsql);
			echo '<script>alert("'.$invsql.'")</script>';
				$result	= $sth->fetchAll();
			//echo '<script>alert("'.urlencode(htmlentities(print_r($result,true))).'")</script>';
				if (isset($result) && $result && isset($result[0]['index_id']))	$var1 = $result[0]['index_id'];
			}
			$var2 = isset($match[2]) ? $match[2] : "";
			echo "User $user processed invoice $var1 on $sql[timestamp] with amount $".$var2.".<br />";
		}
	}
	
	echo "</div>";
exit();	
