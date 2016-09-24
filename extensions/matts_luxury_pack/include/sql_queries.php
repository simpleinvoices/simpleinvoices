<?php
error_log('loading //matts_luxury_pack//sql_queries.php');

function dbQuery2($sqlQuery, $params = array()) {
	global $dbh;
	global $databaseBuilt;

	if (!$databaseBuilt) return false;

	$sth = false;
	// PDO SQL Preparation
	$sth = $dbh->prepare($sqlQuery);
	//foreach ($params as $key=>$val)	{		$sth->bindValue($key, (strpos($val,' ')!==false||!$val) ? $val : '$val');	}
	foreach ($params as $key=>$val)	{		$sth->bindValue($key, $val);	}
	try {
		$sth->execute();
		//dbLogger('dbQuery2:'. interpolateQuery($sqlQuery, $params));
		error_log('dbQuery2:'. interpolateQuery($sqlQuery, $params));
	} catch (Exception $e) {
		echo $e->getMessage();
		echo "dbQuery2: Dude, what happened to your query?:<br /><br /> ". htmlsafe($sqlQuery). "<br />" .
				htmlsafe(end($sth->errorInfo()));
	}
	return $sth;
}
