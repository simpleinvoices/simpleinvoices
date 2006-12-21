<?php
	function db_connect($oArray) {
		return ibase_connect($oArray[2], $oArray[0], $oArray[1]);
	}

	function db_select_db($sDatabase) {
		return null;
	}

	function db_query($oCon,$sSQL) {
		$oStmt = ibase_query($oCon,$sSQL);
		return $oStmt;
	}

	function db_colnum($oStmt) {
		return ibase_num_fields($oStmt);
	}

	function db_columnName($oStmt,$iPos) {
		$aFieldInfo = ibase_field_info($oStmt,$iPos-1);
		return $aFieldInfo["alias"];
	}
	
	function db_columnType($oStmt,$iPos) {
		$aFieldInfo = ibase_field_info($oStmt,$iPos-1);
		return $aFieldInfo["type"];
	}

	function db_fetch($oStmt) {
		return ibase_fetch_assoc($oStmt);
	}

	function db_free($oStmt) {
		return ibase_free_result($oStmt);
	}

	function db_disconnect($oCon) {
		return ibase_close($oCon);
	}
?>
