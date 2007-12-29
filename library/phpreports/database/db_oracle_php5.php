<?php
	class PHPReportsDBI {
		function db_connect($oArray) {
			return oci_pconnect($oArray[0], $oArray[1], $oArray[2]);
		}
		
		function db_select_db($sName){
			return true;
		}

		function db_query($oCon,$sStr){
			$oStmt = oci_parse($oCon,$sStr);
			if(is_null($oStmt))
				return false;
			oci_execute($oStmt);
			return $oStmt;
		}
		
		function db_colnum($oStmt){
			return oci_num_fields($oStmt);
		}
	
		function db_columnName($oStmt,$iPos) {
			return oci_field_name($oStmt,$iPos);
		}
		
		function db_columnType($oStmt,$iPos) {
			return oci_field_type($oStmt,$iPos);
		}

		function db_fetch($oStmt){
			return oci_fetch_array($oStmt,OCI_ASSOC+OCI_RETURN_NULLS);
		}

		function db_free($oStmt){
			return oci_free_statement($oStmt);
		}

		function db_disconnect($oCon){
			return oci_close($oCon);
		}
	}
?>	
