<?php
	class PHPReportColParm {
		var $_sName;	// parameter name
		var $_sValue;	// parameter value

		/*
			This is the smaller class on the report - control the
			parameters name and values. Since we dont need to change
			its values while running, I just created the "get" methods.
		*/
		function PHPReportColParm($sName_=null,$sValue_=null) {
			$this->_sName=$sName_;
			$this->_sValue=$sValue_;
		}

		// returns the parameter name
		function getName() {
			return strtoupper($this->_sName);
		}

		// returns the parameter value
		function getValue() {
			return $this->_sValue;
		}
	}
?>
