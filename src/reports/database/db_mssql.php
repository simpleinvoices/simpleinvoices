<?php
	function db_connect($oArray) {
		$oCon = mssql_connect($oArray[2], $oArray[0], $oArray[1]);
		if(!$oCon)
			die("could not connect");
		if(!is_null($oArray[3]))
			db_select_db($oArray[3]);
		return $oCon;
	}

	function db_select_db($sDatabase) {
		mssql_select_db($sDatabase);
	}

	function db_query($oCon,$sSQL) {
		$oStmt = mssql_query($sSQL,$oCon);
		return $oStmt;
	}

	function db_colnum($oStmt) {
		return mssql_num_fields($oStmt);
	}

	function db_columnName($oStmt,$iPos) {
		$oField = mssql_fetch_field($oStmt,$iPos-1);
		return $oField->name;
	}
	
	function db_columnType($oStmt,$iPos) {
		$oFields = mssql_fetch_field($oStmt,$iPos-1);
		return $oFields->type;
	}

	function db_fetch($oStmt) {
		$aArray = Array();
		if( ($aArray = mssql_fetch_array($oStmt)) == null )
			return false;
		return $aArray;
	}

	function db_free($oStmt) {
		return mssql_free_result($oStmt);
	}

	function db_disconnect($oCon) {
		return mssql_close($oCon);
	}
?>
