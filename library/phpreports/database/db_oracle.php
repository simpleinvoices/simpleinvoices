<?php
	class PHPReportsDBI {
		function db_connect($oArray) {
			return ociPLogon($oArray[0], $oArray[1], $oArray[2]);
		}

		function db_select_db($sDatabase) {
			return null;
		}

		function db_query($oCon,$sSQL) {
			$oStmt = ociParse($oCon,$sSQL);
			ociExecute($oStmt);
			return $oStmt;
		}

		function db_colnum($oStmt) {
			return ociNumCols($oStmt);
		}

		function db_columnName($oStmt,$iPos) {
			return ociColumnName($oStmt,$iPos);
		}
		
		function db_columnType($oStmt,$iPos) {
			return ociColumnType($oStmt,$iPos);
		}

		function db_fetch($oStmt) {
			$aArray = Array();
			if(!@ociFetchInto($oStmt,&$aArray,OCI_ASSOC+OCI_RETURN_NULLS))
				return false;
			return $aArray;
		}

		function db_free($oStmt) {
			return ociFreeStatement($oStmt);
		}

		function db_disconnect($oCon) {
			return ociLogoff($oCon);
		}
	}	
?>
