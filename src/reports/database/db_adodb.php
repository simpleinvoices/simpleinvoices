<?php
	// change to wherever you stored adodb
	require_once("adodb/adodb.inc.php");

	// set fetch mode to associative arrays
	$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;

	// Part of the hack to get the db_fetch function to work
	$_gaRows  = array();
	$_giCount = 0;

	function db_connect($oArray) {
		$sType     = substr($oArray[2], 0, strpos($oArray[2], ":"));
		$oArray[2] = substr($oArray[2], strpos($oArray[2], ":") + 1);
		$oCon      = ADONewConnection($sType);
		$oCon->Connect($oArray[2], $oArray[0], $oArray[1], $oArray[3]);
		
		if(!$oCon) {
			die($oCon->ErrorMsg());
		}
		return $oCon;
	}

	function db_select_db($sDatabase) {
		// Already taken care of in the $oCon->Connect() call
	}

	function db_query($oCon, $sSQL) {
		$oRes = $oCon->Execute($sSQL);
		
		if(!$oRes) {
			die($oCon->ErrorMsg());
		}
		return $oRes;
	}

	function db_colnum($oRes) {
		return $oRes->FieldCount();
	}

	function db_columnName($oRes, $iPos) {
		$oField = $oRes->FetchField($iPos - 1);
		return $oField->name;
	}
		
	function db_columnType($oRes, $iPos) {
		$oField = $oRes->FetchField($iPos - 1);
		return $oField->type;
	}

	function db_fetch($oRes) {
		// needed nasty hack to get it to work
		// adodb didnt like me calling $oRes->FetchRow()
		// it would never move to the next row and it would infinate loop
		global $_gaRows, $_giCount;
		
		if($_gaRows == array()) {
			$_gaRows = $oRes->GetArray();
			$_giCount++;
			return $_gaRows[0];
		} else {
			if($_giCount >= $oRes->RowCount()) {
				return false;
			}
			$_giCount++;
			return $_gaRows[$_giCount - 1];
		}
	}

	function db_free($oRes) {
		$oRes->Close();
		return true;
	}

	function db_disconnect($oCon) {
		$oCon->Close();
		return true;
	}
?>
