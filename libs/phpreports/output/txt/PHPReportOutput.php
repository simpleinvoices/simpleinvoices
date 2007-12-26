<?php
	// MUST be in the include path
	require_once("PHPReportsUtil.php");
	require_once("PHPReportOutputObject.php");

	/*********************************************************************************
	*																											*
	*	PHPReports TXT plugin - renders the page into a TXT file								*
	*	You need to erase the file if you'll need it no more.									*
	*	If $this->_bJump is true, it will print the report on the screen.					*
	*																											*
	*********************************************************************************/
	class PHPReportOutput extends PHPReportOutputObject {
		function run() {
			$sPath  = getPHPReportsFilePath();
			$sTmp	  = getPHPReportsTmpPath();
			$sXSLT  = $sPath."/output/txt/txt.xsl";
			$sXML	  = $this->getInput();

			// create a new filename if its empty
			if(is_null($this->getOutput())){
				$sOut  = tempnam($sTmp,"txt");
				unlink($sOut);
				$sOut .= ".txt";
			}else
				$sOut  = $this->getOutput();

			// XSLT processor				
			$oProcFactory = new XSLTProcessorFactory();
			$oProc = $oProcFactory->get();
			$oProc->setXML($sXML);
			$oProc->setXSLT($sXSLT);
			$oProc->setOutput($sOut);
			$oProc->setParms(array("body"=>($this->getBody()?"true":"false")));
			$sRst = $oProc->run();
		
			/*	
				Read file to pre-processing, replacing the __formfeed__ indicator for a 
				chr(12), and write it again
			*/	
			$fHand = fopen($sOut,"rb");
			$sText = fread($fHand,filesize($sOut));
			fclose($fHand);
			
			if(strpos($sText,"__formfeed__")){
				$sText = str_replace("__formfeed__",chr(12),$sText);
				$sText = str_replace(chr(160)," ",$sText);
				$fHand = fopen($sOut,"wb");
				fwrite($fHand,$sText);
				fclose($fHand); 
			}	

			// if needs to jump to show the file, show it
			if($this->isJumping())
				print $sText;
				
			// check if needs to clean the XML data file	
			if($this->isCleaning())	
				unlink($sXML);	
		}
	}
?>
