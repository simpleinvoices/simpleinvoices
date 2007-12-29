<?php
	class PHPReportsDBI {
		function db_connect($oArray) {
			if(!is_null($oArray[3]))
				$oCon = PHPReportsDBI::db_select_db($oArray[3]);
			return $oCon;
		}

		function db_select_db($sDatabase) {
			$connect = sqlite_open($sDatabase);
			return $connect;
		}

		function db_query($oCon,$sSQL) {
			$oStmt = sqlite_query($sSQL,$oCon);
			return $oStmt;
		}

		function db_colnum($oStmt) {
			return sqlite_num_fields($oStmt);
		}

		function db_columnName($oStmt,$iPos) {
			return sqlite_field_name($oStmt,$iPos-1);
		}
		
		function db_columnType($oStmt,$iPos) {
//			echo "oStmt:<pre>";get_class($oStmt);echo "</pre>iPos: $iPos<br";
			return "UNDEFINED";
		}

		function db_fetch($oStmt) {
			return sqlite_fetch_array($oStmt);
		}

		function db_free(&$oStmt) {
			$oStmt = NULL;
			return True;
		}

		function db_disconnect($oCon) {
			return sqlite_close($oCon);
		}
	}	
?>
