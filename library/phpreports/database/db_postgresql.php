<?php
	/*
		Thanks to AZTEK for testing this interface!
	*/
	class PHPReportsDBI {
		function db_connect($oArray) {
			$sHost	= isset($oArray[2])?$oArray[2]:"localhost";
			$sString = "user='".$oArray[0]."' password='".$oArray[1]."' host='".$sHost."' dbname='".$oArray[3]."'";
			return pg_connect($sString);
		}

		function db_select_db($sDatabase) {
			return null;
		}

		function db_query($oCon,$sSQL) {
			$oStmt = pg_query($oCon,$sSQL);
			return $oStmt;
		}

		function db_colnum($oStmt) {
			return pg_num_fields($oStmt);
		}

		function db_columnName($oStmt,$iPos) {
			return pg_field_name($oStmt,$iPos-1);
		}
		
		function db_columnType($oStmt,$iPos) {
			return pg_field_type($oStmt,$iPos-1);
		}

		function db_fetch($oStmt) {
			return pg_fetch_assoc($oStmt);
		}

		function db_free($oStmt) {
			return pg_free_result($oStmt);
		}

		function db_disconnect($oCon) {
			return pg_close($oCon);
		}
	}
?>
