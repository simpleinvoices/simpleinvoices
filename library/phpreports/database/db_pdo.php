<?php
	/*
	 * PDO support module for PHPReports.
	 *
	 * When using this module, set the database interface to pdo.  The
	 * value to use for setConnection is the combination of the backend and
	 * host.
	 *
	 * $report->setDatabaseInterface('pdo');
	 * $report->setConnection('pgsql:host=localhost');
	 *
	 */
	class PHPReportsDBI {
		function db_connect($oArray) {
			$sHost	= isset($oArray[2])?$oArray[2]:"localhost";
			$sString = ";dbname=".$oArray[3];
			$dbh = new PDO($sString, $oArray[0], $oArray[1]);
			if(!$dbh) {
				die(htmlspecialchars(end($dbh->errorInfo())));
			}
			return $dbh;
		}

		function db_select_db($sDatabase) {
			// Already done in db_connect
		}

		function db_query(&$oCon,$sSQL) {
			$oStmt = $oCon->prepare($sSQL);
			$oStmt->execute();
			return $oStmt;
		}

		function db_colnum(&$oStmt) {
			return $oStmt->columnCount();
		}

		public static function db_columnName(&$oStmt,$iPos) {
			$meta = $oStmt->getColumnMeta($iPos-1);
			return $meta['name'];
		}
		
		/*
		 * Due to issues concerning how PDO handles text, floating
		 * point,  and fixed point types, PDO::PARAM_TEXT is reported
		 * as UNDEFINED, like with the ODBC driver.
		 */
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
	}
?>
