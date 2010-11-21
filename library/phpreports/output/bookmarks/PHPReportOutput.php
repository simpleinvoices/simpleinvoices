<?php
	// MUST be in the include path
	require_once("PHPReportOutputObject.php");
	require_once("PHPReportsUtil.php");
	
	class PHPReportOutput extends PHPReportOutputObject {
		var $_sCSS;

		/**
			Set the CSS file
		*/
		function setCSS($sCSS_=null){
			$this->_sCSS=$sCSS_;
		}

		/**
			Return the CSS file
		*/
		function getCSS(){
			return $this->_sCSS;
		}
	
		/**
			Run the output plugin
		*/
		function run() {
			$sPath = getPHPReportsFilePath(); 
			$sXML	 = $this->getInput();
			$sCSS  = $this->getCSS();

			// parameter array with CSS info
			$aParm = Array();
			$aParm["css"]	= $sCSS;
			$aParm["body"]	= $this->getBody()?"true":"false";
			
			// get the tmp directory under the DocumentRoot
			$sDocRoot = $_SERVER["DOCUMENT_ROOT"];

			// get the host path
			$sHost = "http://".$_SERVER["HTTP_HOST"];
			
			// create some tempnames there
			$sBook = tempnam(realpath($sDocRoot."/tmp"),"bookmark");
			unlink($sBook);
			$sBook .= ".html";
			
			$sRepo = tempnam(realpath($sDocRoot."/tmp"),"report");
			unlink($sRepo);
			$sRepo .= ".html";

			// create the bookmarks file
			$oProcFactory = new XSLTProcessorFactory();
			$oProc = $oProcFactory->get();
			$oProc->setXML($sXML);
			$oProc->setXSLT(realpath($sPath."/output/bookmarks/bookmarks.xsl"));
			$oProc->setOutput($sBook);
			$oProc->setParms($aParm);
			$oProc->run();
			unset($oProc);

			$aParm = Array();
			$aParm["body"]	= $this->getBody()?"true":"false";

			// create the report file
			$oProcFactory = new XSLTProcessorFactory();
			$oProc = $oProcFactory->get();
			$oProc->setXML($sXML);
			$oProc->setXSLT(realpath($sPath."/output/default/default.xsl"));
			$oProc->setOutput($sRepo);
			$oProc->setParms($aParm);
			$oProc->run();
			unset($oProc);

			// code of the framed content		
			$sFrame =
			"<frameset cols=\"150,*\">\n".
			"<frame name=\"bookmarks\" target=\"main\" src=\"$sHost/tmp/".basename($sBook)."\">\n".
			"<frame name=\"report\"    target=\"main\" src=\"$sHost/tmp/".basename($sRepo)."\">\n".
			"</frameset>";

			// if there is not an output file, write to browser window
			if(is_null($this->getOutput()))
				print $sFrame;
			else{
			// or open the file and store the frames there	
				$fHandle = fopen($this->getOutput(),"w");
				fputs($fHandle,$sFrame);
				fclose($fHandle);
			}

			if($this->isCleaning()) 
				unlink($sXML);
		}
	}	
?>
