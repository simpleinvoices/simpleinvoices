<?php
	require_once("PHPReportInputObject.php");

	class PHPReportInputCrosstab extends PHPReportInputObject {
		function run(){
			// default values for aggregation functions
			$this->_default["SUM"]		= 0;
			$this->_default["COUNT"]	= "null";
			$this->_default["MIN"]		= "null";
			$this->_default["MAX"]		= "null";

			// first step - find what columns we have, let's make a query that returns nothing but the columns
			$stmt = PHPReportsDBI::db_query($this->_con,"select * from (".$this->_sql.") crosstab_table where 1=2");
			$cols	= Array();
			for($i=1; $i<=PHPReportsDBI::db_colnum($stmt); $i++)
				array_push($cols,PHPReportsDBI::db_columnName($stmt,$i));	
			PHPReportsDBI::db_free($stmt);

			// find a delimiter 
			$deli = isNumericType($cols[$this->_group_key]) ? "" : "'";

			// now we find the colums to work with the aggregated values - those are the values we'll create the columns
			$cagr = array_diff(array_values($cols),array_merge($this->_group_desc,Array($this->_group_key)));

			// ok, now we know that columns to work with, we need to know the values of the group key
			$stmt = PHPReportsDBI::db_query($this->_con,"select distinct ".$this->_group_key." from (".$this->_sql.") crosstab_table order by ".$this->_group_key);
			$keys = Array();
			while($row=PHPReportsDBI::db_fetch($stmt))
				array_push($keys,$row[$this->_group_key]);
			PHPReportsDBI::db_free($stmt);

			// create the sql query

			// check if there is another default operation other than SUM
			$oper = "SUM";
			if($this->_options["DEFAULT_OPERATION"])
				$oper = strtoupper($this->_options["DEFAULT_OPERATION"]);

			// check if there is an order
			$order = "";
			if($this->_options["ORDER"])
				$order = "order by ".$this->_options["ORDER"];

			// first the description columns
			$sql = "";
			foreach($this->_group_desc as $col)
				$sql .= $col.",";
			$group	= substr($sql,0,strlen($str)-1);
			$sql		= "select ".$sql;
			$coln		= Array(); // store the used column names		
			$apcn		= $this->_options["APPEND_COLUMNS_NAMES"];

			// then the aggregated values
			foreach($keys as $key){			// here the key to compare
				foreach($cagr as $col){		// here the column to manipulate
					$op   = $oper;				// default operation

					// if there is a customized function for this column ...
					if($this->_options["COLUMNS_FUNCTIONS"][$col])
						$op = $this->_options["COLUMNS_FUNCTIONS"][$col];

					// check the default value of the aggregation function - convert to uppercase because they're uppercase there
					if(!array_key_exists(strtoupper($op),$this->_default)){
						print "THERE IS NO DEFAULT VALUE FOR $op!";
						return;
					}
					$defv = $this->_default[strtoupper($op)];

					// check if there is some alias to the function - some translation, for example
					$alias= $this->_options["FUNCTIONS_ALIASES"][$op] ? $this->_options["FUNCTIONS_ALIASES"][$op] : $op;

					// create the column name
					$name = strtoupper($alias)."_".strtoupper($key).($apcn?"_$col":"");

					// check if there is already a column name like this, if so create a new
					// one based on how many times it was repeated.
					if($coln[$name]){
						$coln[$name] = $coln[$name]+1;
						$name = strtoupper($alias)."_".$coln[$name]."_".strtoupper($key).($apcn?"_$col":"");
					}else
						$coln[$name] = 1;

					$sql .= "$op(case when ".$this->_group_key."=$deli$key$deli then $col else $defv end) as $name,";
				}
			}
			$sql = substr($sql,0,strlen($sql)-1)." from (".$this->_sql.") crosstab_table group by $group $order";
			if($this->_options["SHOW_SQL"])
				print $sql;
			return $sql;
		}
	}	
?>
