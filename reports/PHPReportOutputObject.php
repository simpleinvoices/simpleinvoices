<?php
	require_once("PHPReportsUtil.php");

	/******************************************************************************
	*                                                                             *
	*	PHPReportMaker                                                             *
	*	This is the base class of the output plugins. They need to extends this    *
	*	class.																							*
	*                                                                             *
	******************************************************************************/
	class PHPReportOutputObject {
		var $_sInput;
		var $_sOutput;
		var $_bClean;
		var $_bJump;

		/***************************************************************************
		*																									*
		*	Constructor, put default values														*
		*																									*
		***************************************************************************/
		function PHPReportOutputObject(){
			$this->_sInput		= null;
			$this->_sOutput	= null;
			$this->_bClean		= true;
			$this->_bJump		= true;
		}

		/***************************************************************************
		*																									*
		*	Sets the XML input file path															*
		*	This is the XML layout file, not the data one.									*
		*	@param String path																		*
		*																									*
		***************************************************************************/
		function setInput($sInput_=null){
			$this->_sInput=$sInput_;
		}
		
		/***************************************************************************
		*																									*
		*	Returns the XML input file path														*
		*	@return String path																		*
		*																									*
		***************************************************************************/
		function getInput(){
			return $this->_sInput;
		}

		/***************************************************************************
		*																									*
		*	Sets the path of the plugin result file.											*
		*	@param String path																		*
		*																									*
		***************************************************************************/
		function setOutput($sOutput_=null){
			$this->_sOutput=$sOutput_;
		}

		/***************************************************************************
		*																									*
		*	Returns the path of the plugin result file.										*
		*	@return String path																		*
		*																									*
		***************************************************************************/
		function getOutput(){
			return $this->_sOutput;	
		}

		/***************************************************************************
		*																									*
		*	Set the file erasing (after the report is rendered) flag						*
		*	Erases (or not) the XML data file, not the plugin result.					*
		*	@param boolean clean																		*
		*																									*
		***************************************************************************/
		function setClean($bClean_=true){
			$this->_bClean=$bClean_;
		}

		/***************************************************************************
		*																									*
		*	Returns if this class will erase the file											*
		*	after the report is rendered															*
		*	@return boolean erase																	*
		*																									*
		***************************************************************************/
		function isCleaning(){
			return $this->_bClean;
		}

		/***************************************************************************
		*																									*
		*	If true, makes the current URL "jumps" and show the plugin result.		*
		*																									*
		***************************************************************************/
		function setJump($bJump_=true){
			$this->_bJump=$bJump_;
		}

		/***************************************************************************
		*																									*
		*	Returns if it's "jumping".																*
		*																									*
		***************************************************************************/
		function isJumping(){
			return $this->_bJump;
		}

		/***************************************************************************
		*																									*
		*	This function needs to be defined on every plugin.								*
		*																									*
		***************************************************************************/
		function run(){
		}

		/***************************************************************************
		*																									*
		*	Load a saved report from a file.														*
		*	@param file path																			*
		*																									*
		***************************************************************************/
		function loadFrom($sPath_=null){
			if(is_null($sPath_))
				return;

			if(!file_exists($sPath_))
				new PHPReportsError("Could not find $sPath_ for load report.");
					
			$sTemp = tempnam(getPHPReportsTmpPath(),"xml");	
			$fIn	 = fopen("compress.zlib://".$sPath_,"r");			
			$fOut	 = fopen($sTemp,"w");

			// read the md5sum
			$sMD5	 = trim(fread($fIn,50));
			
			while($sStr=fread($fIn,1024))
				fwrite($fOut,$sStr);
			fclose($fOut);
			fclose($fIn);

			$sMD5chk = md5_file($sTemp);
			if(strcmp($sMD5,$sMD5chk)!=0){
				unlink($sTemp);
				print "<b>ERROR</b>: the report stored in $sPath_ is corrupted.";
				return;
			}

			//$sTemp = substr(strrchr($sTemp,"/"),1);
			$this->setInput($sTemp);
			$this->run();
		}
	}
?>
