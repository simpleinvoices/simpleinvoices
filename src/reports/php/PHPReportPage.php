<?php
	class PHPReportPage extends PHPReportGroup {
		var $_iSize;			// page size
		var $_iPageNum;		// page number
		var $_iRow;				// current row
		var $_iLimit;			// limit to print on memory or file
		var $_sBuffer;			// memory buffer for XML elements
		var $_iBcount;			// buffer count
		var $_sTemp;			// temporary dir
		var $_iCurBehaviour;	// current behaviour 
		var $_iBUFFER = 0;
		var $_iFILE	  = 1;
		var $_sFile;
		var $_fHandle;
		var $_sName;
		var $_sTag;
		var $_bOpen;
		var $_iWidth;
		var $_iHeight;
		var $_iCellPadding;
		var $_iBorder;
		var $_sAlign;
		var $_oGroups;
		var $_oDoc;		// document layer

		function PHPReportPage($sXMLOutputFile=null) {
			$this->_sTemp			 = getPHPReportsTmpPath();
			$this->_sName			 = "PAGE LAYER";
			$this->_sTag			 = "PG";
			$this->_bOpen			 = false;
			$this->_iPageNum		 = 0;
			$this->_iSize			 = 50;
			$this->_iRow			 = 1;
			$this->_iLimit			 = 2500;
			$this->_sBuffer		 = "";
			$this->_iBcount		 = 0;
			$this->_fHandle		 = null;
			$this->_iCurBehaviour = $this->_iBUFFER;
			$this->_iPosition		 = 1;
			$this->_iWidth			 = -1;
			$this->_iHeight		 = -1;
			$this->_iCellPadding  = -1;
			$this->_iCellSpacing  = -1;
			$this->_iBorder		 = -1;
			$this->_sAlign			 = " "; //left
			$this->_oGroups		 = null;
			$this->_sFile			 = $sXMLOutputFile;
			$this->_bReprintHeader= true;
			$this->_oDoc			 = null;
			$this->createFileName();
		}

		function setDocument(&$oDoc_){
			$this->_oDoc=&$oDoc_;
		}
		
		function setGroups(&$oGroups_) {
			$this->_oGroups=&$oGroups_;
		}

		function setWidth($iWidth_=800) {
			$this->_iWidth=$iWidth_;
		}
		function getWidth() {
			return $this->_iWidth;
		}

		function setHeight($iHeight_=800) {
			$this->_iHeight=$iHeight_;
		}
		function getHeight() {
			return $this->_iHeight;
		}

		function setCellPadding($iCellPadding_=-1) {
			$this->_iCellPadding=$iCellPadding_;
		}
		function getCellPadding() {
			return $this->_iCellPadding;
		}
		
		function setCellSpacing($iCellSpacing_=-1) {
			$this->_iCellSpacing=$iCellSpacing_;
		}
		function getCellSpacing() {
			return $this->_iCellSpacing;
		}
		
		function setBorder($iBorder_=-1) {
			$this->_iBorder=$iBorder_;
		}
		function getBorder() {
			return $this->_iBorder;
		}
		
		function setAlign($sAlign_="LEFT") {
			$this->_sAlign=$sAlign_;
		}
		function getAlign() {
			return $this->_sAlign;
		}
		
		function setSize($iSize_=50) {
			$this->_iSize=$iSize_;
		}

		function getSize() {
			return $this->_iSize;
		}

		function setLimit($iLimit_=2500) {
			$this->_iLimit=$iLimit_;
		}

		function getLimit() {
			return $this->_iLimit;
		}

		function printTag($sRow_) {
			$this->printRow($sRow_);
		}

		function getTemp() {
			return $this->_sTemp;
		}

		function getBufferRows() {
			return $this->_iBcount;
		}

		function getName() {
			return "PAGE";
		}

		function printRow($sRow_,$bCount_=true,$bConsumed_=false) {
			if(!$this->isOpen()){ 
				$this->eventHandler(PAGE_OPEN);
				if($bConsumed_){
					if($this->isDebugging())
						print "(".$this->getName()."):CONSUMED<br>";
					return;
				}
			}

			if($this->isDebugging())
				print "PAGE:printRow:".$sRow_."<br>";

			$this->_iBcount++;
			$this->_iRow+=($bCount_?1:0);
			$this->output($sRow_);

			// check if the buffer limit was reached
			// if so, open the temp file and flush the buffer
			if($this->_iCurBehaviour==$this->_iBUFFER && 
				$this->_iBcount>$this->_iLimit) {
				$this->openFile();
				$this->writeFile($this->_sBuffer);
				$this->_iCurBehaviour = $this->_iFILE;
			}

			// check if have a page break here
			if($this->_iRow+$this->getFooterSize()>$this->_iSize) 
				$this->eventHandler(PAGE_CLOSE);
		}

		/**
			Returns the current page number
			@return int page number
		*/
		function getPageNum() {
			return $this->_iPageNum;
		}

		function &getPage() {
			return $this;
		}

		/**
			Return the number of the current row
		*/
		function getRowNum() {
			return $this->_iRow;
		}

		/**
			Return the number of the next row
		*/
		function getNextRow() {
			return $this->getRowNum()+1;
		}

		function output($sStr_) {
			// store the result, according to the current behaviour
			if($this->_iCurBehaviour==$this->_iBUFFER)
				$this->_sBuffer .= $sStr_;
			else	
				$this->writeFile($sStr_);
		}

		function getBuffer() {
			return $this->_sBuffer;
		}

		function createFileName(){
			if(is_null($this->_sFile)){
				// tempnam returns a temp file name and create it - but without the suffix
				$this->_sFile = tempnam($this->_sTemp,"phprpt");
				
				// so we delete it
				unlink($this->_sFile);
				
				// put the .xml stuff at the end
				$this->_sFile .=".xml";
			}
		}

		function openFile() {
			$this->_iCurBehaviour=$this->_iFILE;
		
			if(is_null($this->_sFile)) 
				$this->createFileName();
			
			$this->_fHandle=fopen($this->_sFile,"w");
			if(!$this->_fHandle){
				exit("<br/><b>ERROR!</b><br/>".
					  "PHPReports could not create the XML file with your data to make your report ".
					  "runs. Please check if the web server user have rights to write ".
					  "in your temporary directory. Script aborted.");
			}
		}

		function writeFile($sStr_) {
			if(!fputs($this->_fHandle,$sStr_))
				new PHPReportsError("Can't write to the disk. Check your disk quota/space and rights.");
		}

		function getFileName() {
			return $this->_sFile;
		}

		function closeFile() {
			fflush($this->_fHandle);
			fclose($this->_fHandle);
		}

		/**
			Don't fire a PAGE_OPEN event here.
			The page will be opened when the first row arrives.
		*/
		function initialize($oDoc_=null) {
			$oRepo  = $this->getReport();
			$sTitle = $oRepo->getTitle();
			$sColor = $oRepo->getBackgroundColor();
			$sImage = $oRepo->getBackgroundImage();
			$sBCSS  = $oRepo->getBookmarksCSS();
			
			$sParm  = (strlen($sTitle)>0?" TITLE=\"".$oRepo->getTitle()."\"":"");
			$sParm .= (strlen($sColor)>0?" BGCOLOR=\"".$oRepo->getBackgroundColor()."\"":"");
			$sParm .= (strlen($sImage)>0?" BACKGROUND=\"".$oRepo->getBackgroundImage()."\"":"");
			$sParm .= (strlen($sBCSS)>0?" BOOKMARKS_CSS=\"".$oRepo->getBookmarksCSS()."\"":"");
			$this->output("<?xml version=\"1.0\" encoding=\"ISO-8859-1\" standalone=\"no\"?>\n");
			$this->output("<RP$sParm>\n");

			$aCSS = $oRepo->getCSS();
			for($i=0;$i<sizeof($aCSS);$i++){
				$sCSSFile= $aCSS[$i][0];
				$sMedia	= $aCSS[$i][1];
				$this->output("\t<CSS ".(strlen($sMedia)>0?"MEDIA=\"".$aCSS[$i][1]."\"":"").">$sCSSFile</CSS>\n");
			}
		}

		function finalize() {
			// if is only on the buffer yet ...
			if($this->_iCurBehaviour==$this->_iBUFFER){
				$this->openFile();
				$this->writeFile($this->_sBuffer);
			}	
				
			if($this->isOpen()) 
				$this->printFooter(false);

			if($this->isOpen()){	
				$this->writeFile("</".$this->_sTag.">\n");
				$this->setOpen(false);
			}
			$this->writeFile("</RP>\n");
			$this->closeFile();
		}

		function getIniTag() {
			$sTag  = "<".$this->_sTag." SZ=\"".$this->_iSize."\" AL=\"".$this->_sAlign."\" PN=\"".$this->_iPageNum."\"";
			$sTag	.= ($this->_iWidth >0?" WI=\"".$this->_iWidth."\"":"");
			$sTag	.= ($this->_iHeight>0?" HE=\"".$this->_iHeight."\"":"");
			$sTag	.= ($this->_iCellPadding>=0?" PA=\"".$this->_iCellPadding."\"":"");
			$sTag	.= ($this->_iCellSpacing>=0?" SP=\"".$this->_iCellSpacing."\"":"");
			$sTag	.= ($this->_iBorder>0?" BO=\"".$this->_iBorder."\"":"");
			$sTag .= ">\n";
			return $sTag;
		}
		
		/**
			Event handler
			@param int event
		*/
		function eventHandler($iEvent_=-1,$oObj_=null) {
			switch($iEvent_) {
				case REPORT_OPEN:
					$this->initialize($oObj_);
					break;

				case REPORT_CLOSE:
					$this->finalize();
					break;

				case PUT_DATA:
					$this->putValues($oObj_);
					break;	

				case PROCESS_DATA:
					$this->processValues($oObj_);
					break;	
					
				case PAGE_OPEN:
					if($this->isDebugging())
						print "<font color='#00c000'>(PAGE):PAGE_OPEN:first field value:".$this->getValueByPos(0)."</font><br>";
					
					$this->_iPageNum++;
					$this->output($this->getIniTag());
					$this->setOpen();

					// now check here if the DOCUMENT layer was opened
					// it not, print it
					if(!$this->_oDoc->isOpen())
						$this->_oDoc->printHeader();
					
					// print the PAGE HEADER
					$this->printHeader();
					
					if(!is_null($this->_oGroups)) {
						$oGroup =& $this->_oGroups;
						if($oGroup->isFirst()){
							if($this->isDebugging())
								print "(".$this->getName()."):PAGE_OPEN:putting data to ".$oGroup->getName()."<br>";
							$oGroup->eventHandler(PUT_DATA,$this->getLastData());
						}	
						$oGroup->eventHandler(PAGE_OPEN);
					}
					break;
					
				case PAGE_CLOSE:
					$this->_iRow=-100;
					$this->printFooter(false);
					$this->_iRow=1;
					$this->output("</".$this->_sTag.">\n");
					$this->_bOpen=false;
					$this->reset();
					
					if($this->isDebugging())
						print "<font color='#FF0000'>(PAGE):PAGE_CLOSE:first field value:".$this->getValueByPos(0)."<hr></font><br>";
					
					if(!is_null($this->_oGroups)) {
						$oGroup =& $this->_oGroups;
						$oGroup->eventHandler($iEvent_);
					}
					break;
			}
		}
	}
?>
