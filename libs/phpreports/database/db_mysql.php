<?php
	class PHPReportsDBI {
		function db_connect($oArray) {
			$oCon = mysql_connect($oArray[2], $oArray[0], $oArray[1]);
			if(!$oCon)
				die("could not connect");
			if(!is_null($oArray[3]))
				PHPReportsDBI::db_select_db($oArray[3]);
			return $oCon;
		}

		function db_select_db($sDatabase) {
			mysql_select_db($sDatabase);
		}

		function db_query($oCon,$sSQL) {
			$oStmt = mysql_query($sSQL,$oCon);
			return $oStmt;
		}

		function db_colnum($oStmt) {
			return mysql_num_fields($oStmt);
		}

		function db_columnName($oStmt,$iPos) {
			return mysql_field_name($oStmt,$iPos-1);
		}
		
		function db_columnType($oStmt,$iPos) {
			return mysql_field_type($oStmt,$iPos-1);
		}

		function db_fetch($oStmt) {
			return mysql_fetch_array($oStmt);
		}

		function db_free($oStmt) {
			return mysql_free_result($oStmt);
		}

		function db_disconnect($oCon) {
			return mysql_close($oCon);
		}
	}	
?>
