<?php
	// MUST be on the include path
	require_once("PHPReportsUtil.php");
	require_once("PHPReportOutputObject.php");
	
	/**
		PHPReports default plugin - renders page-to-page
		into HTML (directly on the browser or in a file)
	*/
	class PHPReportOutput extends PHPReportOutputObject {
		var $_iCurPage;
		var $_iIncr;
		var $_sFirst;
		var $_sLast;
		var $_sNext;
		var $_sPrev;

		function PHPReportOutput() {
			$this->_iCurPage	= 1;
			$this->_iIncr		= 10;
			$this->_sFirst		= "<< ";
			$this->_sLast		= " >>";
			$this->_sNext		= " >";
			$this->_sPrev		= "< ";
		}

		function setIncr($iIncr_=10) {
			$this->_iIncr=$iIncr_;
		}

		function setFirst($sFirst_) {
			$this->_sFirst=$sFirst_;
		}

		function setLast($sLast_) {
			$this->_sLast=$sLast_;
		}

		function setNext($sNext_) {
			$this->_sNext=$sNext_;
		}

		function setPrev($sPrev_) {
			$this->_sPrev=$sPrev_;
		}

		function run() {
			// get the files paths
			$sPath = getPHPReportsFilePath();
			$sXSLT = "$sPath/output/page/page.xsl";
			$sXML	 = $this->getInput();

			// get the DOCUMENT_ROOT path, for temporary copy
			$sRoot = $_SERVER["DOCUMENT_ROOT"];

			// copy the page.php file to the temporary dir
			if(!copy(realpath($sPath."/output/page/page.php"),realpath($sRoot."/tmp")."/page.php"))
				new PHPReportsError("Could not copy the temporary page parser to the /tmp directory");

			// get the HOST root URL
			$sHost = "http://".$_SERVER["HTTP_HOST"];
			
			$aParm  = Array();
			$aParm["curpage"]=1;
			$aParm["incr"]		= $this->_iIncr;
			$aParm["l1"]		= 1;
			$aParm["l2"]		= $this->_iIncr;
			$aParm["first"]	= $this->_sFirst;
			$aParm["last"]		= $this->_sLast;
			$aParm["next"]		= $this->_sNext;
			$aParm["prev"]		= $this->_sPrev;
			$aParm["xmlfile"]	= $this->getInput();
			$aParm["url"]		= $sHost."/tmp/page.php"; 

			$oProcFactory = new XSLTProcessorFactory();
			$oProc = $oProcFactory->get();
			$oProc->setXML($sXML);
			$oProc->setXSLT($sXSLT);
			$oProc->setOutput($this->getOutput());
			$oProc->setParms($aParm);
			$sRst = $oProc->run();
			print $sRst;
		}
	}
?>
