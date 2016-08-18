<?php

/* CONTAINS FUNCTIONS TO REPLACE FUNCTIONS IN CORE */

/*
 * dbQuery is a variadic function that, in its simplest case, functions as the
 * old mysqlQuery does.  The added complexity comes in that it also handles
 * named parameters to the queries.
 *
 * Examples:
 *  $sth = dbQuery('SELECT b.id, b.name FROM si_biller b WHERE b.enabled');
 *  $tth = dbQuery('SELECT c.name FROM si_customers c WHERE c.id = :id',
 *                 ':id', $id);
 */

function dbQuery($sqlQuery) {
	global $dbh, $logger;
	$argc = func_num_args();
	$binds = func_get_args();
	$sth = false;
	// PDO SQL Preparation
	$sth = $dbh->prepare($sqlQuery);
	if ($argc > 1) {
		array_shift($binds);
		for ($i = 0; $i < count($binds); $i++) {
			$sth->bindValue($binds[$i], $binds[++$i]);
		}
	}
	try {	
		$sth->execute();
		dbLogger($sqlQuery);
	} catch(Exception $e){
		echo $e->getMessage();
		echo "dbQuery: Dude, what happened to your query?:<br /><br /> ".htmlsafe($sqlQuery)."<br />".htmlsafe(end($sth->errorInfo()));
	}
	//$logger->log{'sql='.$sqlQuery, Zend_Log::INFO);
	
	return $sth;
	//$sth = null;
}

// Used for logging all queries
function dbLogger($sqlQuery) {
// For PDO it gives only the skeleton sql before merging with data

	global $log_dbh;
	global $dbh;
	global $auth_session;
	global $can_log;
	
	$userid = $auth_session->id;
	if ($can_log
		&& (preg_match('/^\s*select/iD', $sqlQuery) == 0) 
//		&& (preg_match('/^\s*select\s*sum/iD', $sqlQuery) == 0) 
//		&& (preg_match('/^\s*select\s*coalesce/iD', $sqlQuery) == 0) 
//		&& (preg_match('/^\s*select\s*max/iD', $sqlQuery) == 0) 
//		&& (preg_match('/^\s*select\s*count/iD', $sqlQuery) == 0) 
		&& (preg_match('/^\s*show\s*tables\s*like/iD', $sqlQuery) == 0)
	   ) {
		// Only log queries that could result in data/database  modification

		$last = null;
		$tth = null;
		$sql = "INSERT INTO ".TB_PREFIX."log (id, domain_id, timestamp, userid, sqlquerie, last_id, function, file, line, proposed, stack)
			VALUES (NULL, ?, CURRENT_TIMESTAMP , ?, ?, ?, ?, ?, ?, ?, ?)";

		/* SC: Check for the patch manager patch loader.  If a
		 *     patch is being run, avoid $log_dbh due to the
		 *     risk of deadlock.
		 */
		$call_stack = debug_backtrace();
		//SC: XXX Change the number back to 1 if returned to directly
		//    within dbQuery.  The joys of dealing with the call stack.

		if ($call_stack[2]['function'] == 'run_sql_patch') {
		/* Running the patch manager, avoid deadlock */
			$tth = $dbh->prepare($sql);
		} elseif (preg_match('/^(update|insert)/iD', $sqlQuery)) {
			$last = lastInsertId();
			$tth = $log_dbh->prepare($sql);
		} else {
			$tth = $log_dbh->prepare($sql);
		}
		$find = array();
		$repl = array();
		$args = $call_stack[1]['args'];
		if (count($args) > 1) {
			array_shift($args);
			for ($i = 0; $i < count($args); $i++) {
				$find[] = $args[$i];
				$repl[] = $dbh->quote($args[++$i]);
			}
		}
		$add = str_replace($find, $repl, $sqlQuery);
		$add = preg_replace('/\s\s+/', ' ', $add);
		$tth->execute (array (
			$auth_session->domain_id,
			$userid,
			preg_replace('/\s\s+/', ' ', $sqlQuery),
			$last,
			isset($call_stack[2]['class']) && $call_stack[2]['class'] ? $call_stack[2]['class']."::".$call_stack[2]['function'] : $call_stack[2]['function'],
//			str_replace(realpath(dirname(__FILE__, 3)), '.', $call_stack[2]['file']).' -> '.str_replace(realpath(dirname(__FILE__, 3)), '.', $call_stack[1]['file']),
			str_replace(realpath(dirname(__FILE__)), '.', $call_stack[2]['file']).' -> '.str_replace(realpath(dirname(__FILE__)), '.', $call_stack[1]['file']),
			$call_stack[2]['line'],
			$add,
			print_r($call_stack,true)
		));
		unset($tth);
	}
}
