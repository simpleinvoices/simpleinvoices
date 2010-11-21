<?php
	/*
	 * To use this interface you need an array on the format returned by
	 * some functions like oci_fetch_all, with indexes for the column names and
	 * arrays for each column value like this two column / five rows array:
	 * array("FIELD1"=>array(1,2,3,4,5),"FIELD2"=>array("one","two","three","four","five"))
	 *
	 * Example of use:
	 *
	*	$a = array(	"id"=>array(1,2,3,4,5),
	*					"name"=>array("one","two","three","four","five"),
	*					"email"=>array("1@one","2@two","3@three","4@four","5@five"));
	*
	*	$r = new PHPReportMaker();
	*	$r->setDatabaseInterface("array");
	*	$r->setDatabaseConnection($a);
	*	$r->setXML("test.xml");
	*	$r->run();
	*/
	class PHPReportsDBI {
		var $_array;
		var $_pos;
		var $_cols;
		var $_rows;
		var $_keys;

		function db_connect($array) {
			return true;
		}

		function db_select_db($sDatabase) {
		}

		function db_query($array,$sql) {
			$this->_array	= $array;
			$this->_keys	= array_keys($array);
			$this->_pos		= 0;
			$this->_cols	= sizeof($array);
			$this->_rows	= sizeof($array[$this->_keys[0]]);
			return $this->_array;
		}

		function db_colnum($oStmt) {
			return $this->_rows; 
		}

		function db_columnName($oStmt,$iPos) {
			return $this->_keys[$iPos-1];
		}
		
		function db_columnType($oStmt,$iPos) {
			return gettype($this->_array[$this->_keys[$iPos]][0]);
		}

		function db_fetch($oStmt) {
			if($this->_pos>=$this->_rows)
				return null;
			$a = array();
			foreach($this->_keys as $key)
				$a[$key] = $this->_array[$key][$this->_pos];
			$this->_pos++;
			return $a; 
		}

		function db_free($oStmt) {
		}

		function db_disconnect($oCon) {
		}
	}	
?>
