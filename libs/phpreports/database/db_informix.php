<?php

/*	$oArray contains this:
	$oArray[0]=>user name:Informix install directory:Config File
	$oArray[1]=>password
	$oArray[2]=>server (from 'sqlhosts')
	$oArray[3]=>database name
*/
	class PHPReportsDBI {
		function db_connect($oArray) {
			$db = $oArray[3] . '@' . $oArray[2];
			list ($sUser, $sHome, $sConfig) = split (':', $oArray[0]);
			putenv("INFORMIXDIR=$sHome");
			putenv("INFORMIXSERVER=$oArray[2]");
			putenv("ONCONFIG=$sConfig");
			$oCon = @ifx_connect ($db, $sUser, $oArray[1]);
			if(!$oCon) { die("could not connect"); }
			return $oCon;
		}

		function db_select_db($sDatabase) {
			return;
		}

		function db_query($oCon,$sSQL) {
			return @ifx_query($sSQL, $oCon, IFX_SCROLL);
		}

		function db_colnum($oStmt) {
				return @ifx_num_fields($oStmt);
		}

		function db_columnName($oStmt,$iPos) {
			$types = @ifx_fieldtypes ($oStmt);
			$keys = array_keys ($types);
			return $keys[$iPos-1];
		}
		
		function db_columnType($oStmt,$iPos) {
			$types = @ifx_fieldtypes ($oStmt);
			$keys = array_keys ($types);
			$key = $keys[$iPos-1];
			return $types[$key];
		}

		function db_fetch($oStmt) {
			return @ifx_fetch_row($oStmt);
		}

		function db_free($oStmt) {
			return @ifx_free_result($oStmt);
		}

		function db_disconnect($oCon) {
			return @ifx_close($oCon);
		}
	}
?>
