<?php
	/**
		Object to print and keep values of a sequence of data limited by
		a break expression
	*/
	class PHPReportGroup {
		var $_sName;			// group name
		var $_oReport;			// the main report object
		var $_oFields;			// list of the fields processed here in this group
		var $_oFieldRows;		// rows with the field columns
		var $_sExpr;			// break expression
		var $_bPageBreak;		// break the page?
		var $_oHeader;			// header
		var $_bHeader;			// header was printed?
		var $_bReprintHeader;// reprint header on page break?
		var $_bResetSup;		// reset suppress on page break
		var $_oFooter;			// footer
		var $_bFooter;			// footer was printed?
		var $_oChild;			// child group
		var $_bOpen;			// is an open group? 
		var $_iRowCount;		// row count 
		var $_oOldExpr;		// old expression
		var $_oLastData;		// last data processed
		var $_bFirst;
		var $_bDebug;
		var $_oError;
		var $_oForm;

		/*
			Class constructor
		*/
		function PHPReportGroup($sName_="UNDEFINED") {
			$this->_sName				= $sName_;
			$this->_oFields			= null;
			$this->_oFieldRows		= null;
			$this->_bPageBreak		= false;
			$this->_oChild				= null;
			$this->_bHeader			= false;
			$this->_bReprintHeader	= false;
			$this->_bResetSup			= false;
			$this->_bFooter			= false;
			$this->_bOpen				= false;
			$this->_iRowCount			= 0;
			$this->_oOldExpr			= null;
			$this->_oLastData			= null;
			$this->_bFirst				= true;
			$this->_bDebug				= false;
			$this->_oError				= new PHPReportsErrorTr();
		}

		/**
			Return the group name
			@return String name
		*/
		function getName() {
			return $this->_sName;
		}

		/**
			Sets the main report object
			@param Object report object
		*/
		function setReport(&$oRpt_) {
			$this->_oReport =& $oRpt_;
		}
		
		/**
			Returns the main report object
			(use like this $oRpt =& $this->getReport();)
			@return Object report object
		*/			
		function &getReport() {
			return $this->_oReport;
		}

		/**
			Sets if when the group breaks it will make a page break
			@param String YES,NO,TRUE,FALSE,BREAK,NOBREAK
		*/
		function setPageBreak($sBreak_=false) {
			if(strlen($sBreak_)<1){
				$this->_bPageBreak=false;
				return;
			}
			$this->_bPageBreak = stristr("YES,TRUE,BREAK",$sBreak_);
		}

		/**
			Return if this is a page breaking group
			@return boolean 
		*/
		function isBreakingPage() {
			return $this->_bPageBreak;
		}

		/**
			Sets the break expression
			@param String expression
		*/
		function setBreakExpr($sExpr_=null) {
			$this->_sExpr=$sExpr_;
		}

		/**
			Returns the break expression
			@return String expression
		*/
		function getBreakExpr() {
			return $this->_sExpr;
		}

		/**
			Set fields
			Here its a copy (not reference, each group have its own copy and
			values) of the values array.
			@param Object fields
		*/
		function setFields($oFields_=null) {
			/* Thanks to Robin Marlowe for warning me about a
				bug that was here.
				If using PHP5, now we need to use clone method.
				I need to use the eval function because if you're 
				running in a PHP4 computer it will gives you an
				error with the "clone" method (PHP4 don't know
				what the hell is that :-).
			*/					 
			if(intval(substr(phpversion(),0,1))<5)
				$this->_oFields=$oFields_;
			else{	
				$this->_oFields=null;
            if(!$oFields_)
					return;
            foreach($oFields_ as $k=>$f)
					eval("\$this->_oFields[\$k] = clone \$f;");
			}	
		}

		/**
			Set child group
			@param Object child group
		*/
		function addChild(&$oChild_) {
			$this->_oChild=&$oChild_;
		}

		/**
			Returns child group
			@return Object child group
		*/
		function &getChild() {
			return $this->_oChild;
		}

		/**
			Sets the header Array
			@param Array header
		*/
		function setHeader($oHeader_=null) {
			$this->_oHeader=$oHeader_;
		}

		/**
			Returns the header object
			@return Array header
		*/		
		function &getHeader() {
			return $this->_oHeader;
		}

		/**
			Prints the header rows	
		*/		
		function printHeader() {
			if($this->isDebugging())
				print "(".$this->getName()."):printHeader:flag ".($this->_bHeader?"ON":"OFF").":first field value:".$this->getValueByPos(0)."<br>";
			//$this->_bFirst=false;	
		
			if(!$this->_bHeader&&sizeof($this->_oHeader)>0) {
				$this->printRows($this->_oHeader,true,true); 
				$this->_bHeader=true;
				$this->_bFooter=false;
			}	
		}

		function setReprintHeader($sReprint_="FALSE") {
			if(strlen($sReprint_)<1){
				$this->_bReprintHeader=false;
				return;
			}
			$this->_bReprintHeader=stristr("YES,TRUE,REPRINT",$sReprint_);
		}
		
		function isReprintingHeader() {
			return $this->_bReprintHeader;
		}

		function setResetSuppress($sReset_="FALSE") {
			if(strlen($sReset_)<1){
				$this->_bResetSup=false;
				return;
			}
			$this->_bResetSup=stristr("YES,TRUE,RESET",$sReset_);
		}
		function isResetingSuppress() {
			return $this->_bResetSup;
		}

		/**
			Sets the footer array
			@param Array footer
		*/			
		function setFooter($oFooter_=null) {
			$this->_oFooter=$oFooter_;
		}

		/**
			Returns the footer array
			@return Array footer
		*/					
		function &getFooter() {
			return $this->_oFooter;
		}

		/**
			Prints the footer
			@param $bCount - count on the page the lines of the footer
			The parameter above is set to FALSE on the PHPReportPage class,
			because we don't need to count the page lines if we are printing it's
			footer!
		*/
		function printFooter($bCount_=true) {
			if($this->isDebugging())
				print "(".$this->getName()."):printFooter:flag ".($this->_bFooter?"ON":"OFF").":first field value:".$this->getValueByPos(0)."<br>";
				
			if(!$this->_bFooter&&sizeof($this->_oFooter)>0) {
				$oPage =& $this->getPage();
				$this->_bFooter=true; // isso estava duas linhas abaixo
				$this->printRows($this->_oFooter,$bCount_);
				$this->_bHeader=false;
			}
		}

		/**
			Returns the page object used to print all stuff in here - 
			if its the page layer, returns itself
			@return Object page
		*/
		function &getPage() {
			$oPage=null;
			if(get_class($this)=="phpreportpage")
				return $this;
			else {				
				if(!isset($this->_oReport)) {
					print "REPORT ELEMENT IS NOT OK (".$this->getName().")<br>";
					return;
				}
				$oPage =& $this->_oReport->getPage();
			}	
			// checks the page element
			if(!isset($oPage)) {
				print "PAGE ELEMENT IS NOT OK";
				return;
			}
			return $oPage;
		}

		/**
			Print this group rows
		*/
		function printRows($oRows_=null,$bCount_=true,$bConsumed_=false) {
			if(!isset($oRows_))
				return;
			
			$oPage =& $this->getPage();
			$iRow = $oPage->getNextRow();	
			$iSize=sizeof($oRows_);
			for($i=0;$i<$iSize;$i++,$iRow++) {
				$oObj=& $oRows_[$i];
				$oPage->printRow($oObj->getRowValue($iRow),$bCount_,$bConsumed_);
			}								
		}

		/**
			Sets the field rows array
			@param Array field rows
		*/
		function setFieldRows($oFieldRows_=null) {
			$this->_oFieldRows=&$oFieldRows_;
		}

		function &getFieldRows() {
			return $this->_oFieldRows;
		}

		/**
			Return if this group is a group with 
			the field rows defined
			@param boolean
		*/	
		function isFieldGroup() {
			return isset($this->_oFieldRows);
		}

		/**
			Here the values are just inserted on the page
			to make sense to print headers, for example.
			@param data row 
		*/
		function putValues($oDataRow_=null) {
			$this->_oLastData = $oDataRow_;
			$oKeys	= array_keys($this->_oFields);
			$iColNum	= sizeof($oKeys);
			for($i=0;$i<$iColNum;$i++) {
				$sKey		= $oKeys[$i];
				$oObj	   =&$this->_oFields[$sKey];	
				$sName	= $oObj->getName();		
				$oVal		= $oDataRow_[$sName];
				$oObj->setVal($oVal); // here's the trick part - no stats here, just value		
			}
		}

		/**
			Evaluate the current expression from the fields row values
		*/			
		function evalExpr($oRow_){
			if(is_null($oRow_) || is_null($this->_sExpr) || !$this->_sExpr)
				return null;
			$aExpr = explode(",",$this->_sExpr);	
			$iSize = sizeof($aExpr);
			$sCurExp = "";
			for($i=0;$i<$iSize;$i++)
				$sCurExp .= $oRow_[$aExpr[$i]];
			return $sCurExp;	
		}

		/**
			Process fields values
			@param Array data row returned by SQL query
		*/
		function processValues($oDataRow_=null) {
			$this->_oLastData = $oDataRow_; // DONT move this!
		
			// you MUST evaluate the current expression here!
			// it was a bug till 0.2.9	
			$sCurRowExpr = $this->evalExpr($oDataRow_);
			
			// if there is a break expression but the current value is null ...
			if(!is_null($this->_sExpr) && $this->_sExpr!="" && is_null($this->_oOldExpr))
				//$this->_oOldExpr=$oDataRow_[$this->_sExpr];
				$this->_oOldExpr=$sCurRowExpr;
		
			// check the expression break - if its different, 
			// close the current group
			if(!is_null($this->_sExpr) && $this->_sExpr!="" &&
				$this->_oOldExpr!=$sCurRowExpr) {
				$this->_oOldExpr =$sCurRowExpr;
				$this->eventHandler(GROUP_CLOSE);
			}

			// DONT MOVE THIS ALSO!
			// we need to have the the values before the group is
			// opened, because maybe we need to use it on the group HEADER
			$this->processStats($oDataRow_);	
			
			// if the group is not open, open it please!
			if(!$this->isOpen()) 
				$this->eventHandler(GROUP_OPEN);

			$this->_iRowCount++;
			
			// print the fields if it is a field group
			if($this->isFieldGroup()) {
				$oPage =& $this->getPage();
				$oPage->processValues($oDataRow_);
				$this->printRows($this->_oFieldRows);
			}
			$this->_bFirst=false;

			// if have a child ...
			if(isset($this->_oChild)) 
				$this->_oChild->processValues($oDataRow_);
		}

		/**
			Process all stats about the numeric fields.
			@param Array data row returned by the SQL query
		*/
		function processStats($oDataRow_=null) {
			// get the field values here
			// you could be thinking why I dont use list/each here ...
			// I need the array element by reference, so to be safe I get
			// all the array keys and make a loop on it
			// process values
			$oKeys	= array_keys($this->_oFields);
			$iColNum	= sizeof($oKeys);
			for($i=0;$i<$iColNum;$i++) {
				$sKey		= $oKeys[$i];
				$oObj	   =&$this->_oFields[$sKey];	// get the field object
				$sName	= $oObj->getName();			// get the field name
				$oVal		= $oDataRow_[$sName];		// get the field value, returned from the query
				$oObj->set($oVal);						// set the field object value 
			}
		}

		/**
			Returns a field value 
			@return Object field value
		*/
		function getValue($sField_) {
			if(is_null($this->_oFields[$sField_])) 
				$this->_oError->showMsg("NOFIELD",array($sField_));
			return $this->_oFields[$sField_]->getVal();
		}

		function getValueByPos($iPos_=0){
			$oKeys = array_keys($this->_oFields);
			return $this->_oFields[$oKeys[$iPos_]]->getVal();
		}

		/**
			Returns the sum of a field value
			@return Object sum
		*/
		function getSum($sField_) {
			if(is_null($this->_oFields[$sField_]))
				$this->_oError->showMsg("NOFIELDSUM",array($sField_));
			return $this->_oFields[$sField_]->getSum();
		}

		/**
			Returns the max field value
			@return Object max
		*/
		function getMax($sField_) {
			if(is_null($this->_oFields[$sField_]))
				$this->_oError->showMsg("NOFIELDMAX",array($sField_));
			return $this->_oFields[$sField_]->getMax();
		}

		/**
			Returns the min field value
			@return Object min
		*/
		function getMin($sField_) {
			if(is_null($this->_oFields[$sField_]))
				$this->_oError->showMsg("NOFIELDMIN",array($sField_));
			return $this->_oFields[$sField_]->getMin();
		}

		/**
			Returns the average field value
			@return Object average
		*/
		function getAvg($sField_) {
			if(is_null($this->_oFields[$sField_]))
				$this->_oError->showMsg("NOFIELDAVG",array($sField_));
			return $this->_oFields[$sField_]->getSum()/$this->getRowCount();
		}

		/**
			Returns how many rows this group have
			@return int row count
		*/
		function getRowCount() {
			return $this->_iRowCount;
		}

		/**
			Open the group
		*/	
		function open() {
			if(!$this->isOpen()) 
				$this->eventHandler(GROUP_OPEN);
		}

		/**
			Sets if the group is opened
			@param boolean open
		*/
		function setOpen($bOpen_=true) {
			$this->_bOpen=$bOpen_;
		}

		/**
			Returns if the group is opened
			@return boolean
		*/			
		function isOpen() {
			return $this->_bOpen;
		}

		/**
			Close the group
		*/	
		function close() {
			if($this->isOpen())
				$this->eventHandler(GROUP_CLOSE); 
		}

		/**
			Run this when THE FULL REPORT begins
		*/			
		function initialize() {
		}

		/**
			Run this when THE FULL REPORT ends
		*/
		function finalize() {
			$this->close();
		}

		function getLastData(){
			return $this->_oLastData;
		}

		/**
			Event handler
			@param int event
		*/
		function eventHandler($iEvent_=-1,$oObj_=null) {
			$oPage =& $this->getPage();
			$oRepo =& $this->getReport();
			
			switch($iEvent_) {
				case PUT_DATA:
					$this->putValues($oObj_);
					break;

				case PROCESS_DATA:
					if($this->isFirst())
						$this->putValues($oObj_);
						
					if(!$oPage->isOpen())
						$oPage->eventHandler(PAGE_OPEN,$oObj_);
			
					if(!$this->isOpen())
						$this->eventHandler(GROUP_OPEN,$oObj_);
				
					$this->processValues($oObj_);
					break;	
				
				case REPORT_OPEN:
					$this->initialize();
					break;
				
				case REPORT_CLOSE:
					if(!is_null($oRepo)) 
						$oRepo->setReportEnd(true);
					$this->finalize();
					break;
					
				case GROUP_OPEN:
					if($this->isDebugging())
						print "<font color='#00a000'>(".$this->getName()."):GROUP_OPEN:first field value:".$this->getValueByPos(0)."</font><br>";

					$this->_iRowCount=0;
					$this->setOpen(true);	// RULE: everytime a GROUP_OPEN arrives, the group is marked as open
					if(!$this->isFirst()){
						$this->_bHeader=false;
						$this->_bFooter=false;
					}

					if(!$oPage->isOpen()){
						$oPage->eventHandler(PAGE_OPEN);
						return;
					}

					if($this->isResetingSuppress()) 
						$this->resetOldValues();

					$this->printHeader();	

					if(!is_null($this->_oChild)) {
						$this->_oChild->putValues($this->getLastData());
						$this->_oChild->eventHandler($iEvent_);
					}
					break;	
					
				case GROUP_CLOSE:
					if($this->isDebugging())
						print "<font color='#e00000'>(".$this->getName()."):GROUP CLOSE:first field value:".$this->getValueByPos(0)."</font><br>";
					
					$oRepo =& $this->getReport();
					$oPage =& $this->getPage();	
					if(!is_null($this->_oChild)){
						$this->_oChild->eventHandler($iEvent_);
						$this->_oChild->resetOldExpr();
					}

					/*
						Uncomment the next two lines of code if you want
						the DOCUMENT FOOTER being printed AFTER the PAGE FOOTER
						I think it becomes a little weird when you have for example
						"page number 10" and then "GRAND TOTAL" there, but it's a kind of
						taste.
						To make this a little more beauty we'll need another layer of detail,
						for example, PAGE NUMBER_FOOTER. But that's another history. :-)
							
					if($this->getName()=="DOCUMENT LAYER")
						$oPage->printFooter();
					*/
					$this->printFooter();
					$this->reset();
					$this->_bHeader=true;

					// check if breaks the page on the group close
					if($this->isBreakingPage()&&!$oRepo->isReportEnd()) 
						$oPage->eventHandler(PAGE_CLOSE);
					break;	
				
				case PAGE_OPEN:
					if($this->isDebugging())
						print "(".$this->getName()."):PAGE_OPEN:first field value:".$this->getValueByPos(0)."<br>";
					
					if($this->isDebugging()&&$this->isReprintingHeader())
						print "(".$this->getName()."):PAGE_OPEN:need to reprinting header<br>";

					if($this->isDebugging()&&$this->isFirst())
						print "(".$this->getName()."):PAGE_OPEN:is first time<br>";

					if($this->isReprintingHeader()&&$this->isOpen()) //||$this->isFirst())
						$this->_bHeader=false;
					
					if($this->isDebugging())
						print "(".$this->getName()."):HEADER is ".($this->_bHeader?"ON":"OFF").", FOOTER is ".($this->_bFooter?"ON":"OFF")."<br>";
					
					if(!$this->_bHeader&&!$this->_bFooter){	
						$oRepo =& $this->getReport();
						if(!is_null($oRepo)&&!$oRepo->isReportEnd()){
							if($this->isDebugging())
								print "(".$this->getName()."):PAGE_OPEN:printing header<br>";
							$this->printHeader();
						}
					}

					// here we need to tell the groups that the page starts, BUT,
					// we need to tell them what are the current data being processed
					// right now to put on their headers
					if(!is_null($this->_oChild)) {
						if($this->_oChild->isFirst()) 
							$this->_oChild->putValues($this->getLastData());
						$this->_oChild->eventHandler($iEvent_);
					}
					break;
					
				case PAGE_CLOSE:
					if($this->isResetingSuppress()) 
						$this->resetOldValues();
						
					if(!is_null($this->_oChild))
						$this->_oChild->eventHandler($iEvent_);
					break;	
			}
		}

		/**
			Reset this group and fields values too.
		*/
		function reset($bOnlyStats_=false) {
			$oKeys	= array_keys($this->_oFields);
			$iColNum	= sizeof($oKeys);
			for($i=0;$i<$iColNum;$i++) {
				$sKey		= $oKeys[$i];
				$oObj	   =&$this->_oFields[$sKey];
				if($bOnlyStats_)
					$oObj->resetStats();
				else
					$oObj->reset();
			}
			$this->_bOpen   =false;
			$this->_bHeader =false;
			$this->_bFooter =false;
			if($this->isDebugging())
				print "<font color='#FF0000'>(".$this->getName()."):RESETED</font><br>";
		}

		function resetOldValues() {
			$iRowNum	= sizeof($this->_oFieldRows);
			for($i=0;$i<$iRowNum;$i++) 
				$this->_oFieldRows[$i]->resetOldValue(); 
		}
			
		function resetOldExpr(){	
			$this->_oOldExpr=null;	
		}
			
		/**
			Returns the header size
			@return int header size
		*/
		function getHeaderSize() {
			return sizeof($this->_oHeader);
		}
		
		/**
			Returns the footer size
			@return int footer size
		*/
		function getFooterSize() {
			return sizeof($this->_oFooter);
		}

		function getParameter($oKey_){
			$oRepo =& $this->getReport();
			return $oRepo->getParameter($oKey_);
		}

		function isFirst(){
			return $this->_bFirst;
		}

		function setDebug($sDebug_="FALSE"){
			$sDebug_ = strtoupper($sDebug_);
			$this->_bDebug=($sDebug_=="TRUE"||$sDebug_=="YES");
		}

		function isDebugging(){
			return $this->_bDebug;
		}

		function getXMLOutputFile(){
			return $this->_oReport->getXMLOutputFile();
		}

		function listFields(){
			$sStr="Available fields:<br/>";
         foreach($this->_oFields as $f)
				$sStr .= $f->getName()." ";
			return $sStr;				
		}

		function getEnvObj($sKey_){
			return $this->_oReport->getEnvObj($sKey_);
		}

		function setForm($oForm_=null){
			$this->_oForm=$oForm_;
		}
		function getForm(){
			return $this->_oForm;
		}
	}
?>
