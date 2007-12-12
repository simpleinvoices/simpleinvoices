<?php
	function db_connect($oArray) {
		$sHost	= $oArray[2];
		$sString = $sHost.";dbname=".$oArray[3];
		$dbh = new PDO($sString, $oArray[0], $oArray[1]);
		if(!$dbh) {
			die(htmlspecialchars(end($dbh->errorInfo())));
		}
		return $dbh;
	}

	function db_select_db($sDatabase) {
		// Already done
	}

	function db_query(&$oCon,$sSQL) {
		$oStmt = $oCon->prepare($sSQL);
		$oStmt->execute();
		return $oStmt;
	}

	function db_colnum(&$oStmt) {
		return $oStmt->columnCount();
	}

	function db_columnName(&$oStmt,$iPos) {
		$meta = $oStmt->getColumnMeta($iPos-1);
		return $meta['name'];
	}
	
	function db_columnType(&$oStmt,$iPos) {
		$meta = $oStmt->getColumnMeta($iPos-1);
		if ($meta['pdo_type'] == PDO::PARAM_BOOL) {
			return 'BOOLEAN';
		} elseif ($meta['pdo_type'] == PDO::PARAM_NULL) {
			return 'NULL';
		} elseif ($meta['pdo_type'] == PDO::PARAM_INT) {
			return 'INT';
		} elseif ($meta['pdo_type'] == PDO::PARAM_STR) {
			return 'UNDEFINED';
		} elseif ($meta['pdo_type'] == PDO::PARAM_LOB) {
			return 'LOB';
		}
		return $meta['pdo_type'];
	}

	function db_fetch(&$oStmt) {
		return $oStmt->fetch();
	}

	function db_free(&$oStmt) {
		$oStmt = null;
		return null;
	}

	function db_disconnect(&$oCon) {
		$oCon = null;
		return null;
	}
?>
