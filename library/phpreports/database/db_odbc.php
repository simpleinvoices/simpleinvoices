<?php
	//
	// Openlink users may need lines such as the following.  These are 
	// specific to your installation and provided here only as an example.
	//
	// putenv("ODBCINI=/usr/local/OpenLink/bin/odbc.ini");
	// putenv("ODBCINSTINI=/usr/local/OpenLink/bin/odbcinst.ini");
	// putenv("OPENLINKINI=/usr/local/OpenLink/bin/openlink.ini");
	// putenv("PATH=/usr/local/OpenLink/bin:/usr/local/OpenLink/samples/UDBC:/usr/local/OpenLink/samples/ODBC");
	// putenv("LD_LIBRARY_PATH=/usr/local/OpenLink/lib");
	// putenv("LIBPATH=/usr/local/OpenLink/lib");
	// putenv("SHLIB_PATH=/usr/local/OpenLink/lib");
	// putenv("OPL_LOCALEDIR=/usr/local/OpenLink/locale");

	class PHPReportsDBI {
		function db_connect($oArray) {
			$oCon = odbc_connect($oArray[2], $oArray[0], $oArray[1]);
			if(!$oCon)
				die("could not connect");
			return $oCon;
		}
		 
		function db_select_db($sDatabase) {
			return null;
		}
		 
		function db_query($oCon,$sSQL) {
			$oStmt = odbc_exec($oCon,$sSQL);
			return $oStmt;
		}
		 
		function db_colnum($oStmt) {
			return odbc_num_fields($oStmt);
		}
		 
		function db_columnName($oStmt,$iPos) {
			return odbc_field_name($oStmt,$iPos);
		}

		// There are known issues with SQL Server and possibly OpenLink ODBC
		// that may cause odbc_field_type to return a null value.  This will
		// break PHPreports.  If this happens, we have no idea what the 
		// field type is, so we return a space.  This may cause undesirable
		// side effects in processing numeric fields.   
		function db_columnType($oStmt,$iPos) {
			$rval = odbc_field_type($oStmt,$iPos);
			if($rval == "")
				$rval = "UNDEFINED"; 
			return $rval; 
		}
		 
		function db_fetch($oStmt) {
			if(function_exists(odbc_fetch_array))
				return odbc_fetch_array($oStmt);
			return odbc_fetch_array_hack($oStmt);
		}
		 
		function db_free($oStmt) {
			return odbc_free_result($oStmt);
		}
		 
		function db_disconnect($oCon) {
			return odbc_close($oCon);
		}
		 
		/**
			Thanks to ironhacker (ironhacker at users.sourceforge.net) for this!
		*/
		function odbc_fetch_array_hack($result, $rownumber=-1) {
			$rs_assoc = Array();
			if(PHP_VERSION>"4.1"){
				if($rownumber<0)
					odbc_fetch_into($result,&$rs);
				else
					odbc_fetch_into($result, &$rs, $rownumber);
			}else
				odbc_fetch_into($result, $rownumber, &$rs);
				
			foreach($rs as $key => $value)
				$rs_assoc[odbc_field_name($result, $key+1)] = $value;
			return $rs_assoc;
		}
	}
?>
