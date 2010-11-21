<?php
	require_once("PHPReportsUtil.php");

	class PHPReportInputObject {
		var $_con;
		var $_sql;
		var $_group_desc;
		var $_group_key;
		var $_options;
		var $_default;

		function PHPReportInputObject($group_desc,$group_key,$options=null){
			$this->_group_desc= $group_desc;
			$this->_group_key	= $group_key;
			$this->_options	= $options;
		}

		function setSQL($sql){
			$this->_sql = $sql;
		}

		function setConnection($con){
			$this->_con = $con;
		}

		function run(){
		}
	}
?>
