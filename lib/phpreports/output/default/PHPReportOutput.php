<?php
	// MUST be in the include path
	require_once("PHPReportOutputObject.php");
	
	/**
		PHPReports default plugin - renders the page
		into HTML (directly on the browser or in a file)
	*/
	class PHPReportOutput extends PHPReportOutputObject {
		function run() {
			$sPath  = getPHPReportsFilePath();
			$sXML	  = $this->getInput();
			$sXSLT  = "$sPath/output/default/default.xsl";
		
			$oProcFactory = new XSLTProcessorFactory();
			$oProc = $oProcFactory->get();
			$oProc->setXML($sXML);
			$oProc->setXSLT($sXSLT);
			$oProc->setOutput($this->getOutput()); 
			$oProc->setParms(array("body"=>($this->getBody()?"true":"false")));
			$sRst = $oProc->run();
			unset($oProc);
			
			if(is_null($this->getOutput()))
				print $sRst;
				
			if($this->isCleaning())	
				unlink($sXML);	
		}
	}
?>
