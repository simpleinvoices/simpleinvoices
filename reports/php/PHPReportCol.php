<?php
	/**
		Report column object
		It can be of three types: 
		1) REGULAR		   - where the element value is interpreted as pure text
		2) EXPRESSION	   - the element value will be evaluated with the eval() function
		3) RAW_EXPRESSION - the element value will be evaluated the same way as EXPRESSION, 
		                    but the htmlspecialchars function will be used on the result.
		4) FIELD			   - the column will try to retrieve a field value where the name 
		                    matches the element value (its CASE-SENSITIVE!)
	*/
	class PHPReportCol extends PHPReportXMLElement {
		var $_aParms;				// column parameters
		var $_sType;				// column type
		var $_sExpr;				// column expression
		var $_sNform;				// number format
		var $_iNformX;				// number format extended
		var $_bSuppr;				// suppress old values
		var $_oGroup;				// col group
		var $_sDecSep;				// decimal separator
		var $_sThoSep;				// thousand separator
		var $_oCurVal;				// current value
		var $_oOldVal;				// old column value
		var $_sEvenClass;			// class for use in even rows
		var $_sOddClass;			// class for use in odd rows
		var $_oLink;				// link element
		var $_oBookmark;			// bookmark element
		var $_oImg;					// image element
		var $_aTrans;				// translation array
		var $_sCellClassExpr;	// cell class expression

		/**
			Constructor
		*/
		function PHPReportCol() {
			$this->_aParms			= Array();	
			$this->_sType			= "UNDEFINED";
			$this->_sExpr			= null;
			$this->_oGroup			= null;
			$this->_sNform			= null;
			$this->_iNformX		= -1;
			$this->_sDecSep		= ",";
			$this->_sThoSep		= ".";
			$this->_bSuppr			= false;
			$this->_oCurVal		= null; 
			$this->_oOldVal		= null;
			$this->_sEvenClass	= null;
			$this->_sOddClass		= null;
			$this->_oLink			= null;
			$this->_oBookmark		= null;
			$this->_oImg			= null;
			$this->_sClassExpr	= null;
			$this->makeTranslationArray();
		}

		function makeTranslationArray(){
			$this->_aTrans["TYPE"]				="TP";
			$this->_aTrans["NUMBERFORMAT"]	="NF";
			$this->_aTrans["NUMBERFORMATEX"]	="NE";
			$this->_aTrans["CELLCLASS"]		="CC";
			$this->_aTrans["TEXTCLASS"]		="TC";
			$this->_aTrans["ROWSPAN"]			="RS";
			$this->_aTrans["COLSPAN"]			="CS";
			$this->_aTrans["WIDTH"]				="WI";
			$this->_aTrans["HEIGHT"]			="HE";
			$this->_aTrans["ALIGN"]				="AL";
			$this->_aTrans["VALIGN"]			="VA";
		}

		/**
			Add a parameter
			@param PHPReportColParm - parameter object
		*/	
		function addParm($aParm_=null) {
			if($aParm_->getName()=="VISIBLE")
				return;
			if(is_null($this->_aTrans[$aParm_->getName()])) 
				new PHPReportsError("COL parameter ".$aParm_->getName()." not found on translation","contactdeveloper.php?msg=COL parameter not found on translation: ".$aParm_->getName());
			$this->_aParms[$this->_aTrans[$aParm_->getName()]]=$aParm_;
		}

		/**
			Gets a parameter value
			@param String parameter name
			@return Object parameter value
		*/
		function getParm($sParm_=null) {
			$oObj=$this->_aParms[$sParm_];
			if(is_null($oObj))
				return null;
			return $oObj->getValue();
		}

		/*
			Returns the parameters array - we dont need a method to set it,
			it MUST be set using the addParm to add the parameters one by one.
			@return Object[] parameters
		*/
		function getParms() {
			return $this->$_aParms;
		}

		/*
			Returns the XML open tag
			@param int row - row number to check about odd and even styles
			@return String XML open tag
		*/
		function getXMLOpen($iRow_=0) {
			$sStr		= "<C ";
			
			// check for even and odd cell classes
			if(!is_null($this->_sEvenClass)&&$iRow_%2==0) 
				$sStr .="CC=\"".$this->_sEvenClass."\" ";
			else if(!is_null($this->_sOddClass)&&$iRow_%2>0) 
				$sStr .="CC=\"".$this->_sOddClass."\" ";

			// check for expression on the CELLCLASS parameter
			if(!is_null($this->_sCellClassExpr))
				$sStr .= "CC=\"".eval($this->_sCellClassExpr)."\" ";
			
			// check if the CELLCLASS was processed
			$bClassProcessed = strpos($sStr,"CC")>0;	
			
			// loop on the parameters array
			$aKeys=array_keys($this->_aParms);
			$iSize=sizeof($aKeys);
			for($i=0;$i<$iSize;$i++) {
				$oParm=$this->_aParms[$aKeys[$i]];	// get the parameter object
				$sName=$aKeys[$i];						// get the parameter name
				$oVal =$oParm->getValue();				// and the parameter value
				
				// if its the CELLCLASS parm, check if it
				// it was not processed above
				if($sName!="CC" || ($sName=="CC" && !$bClassProcessed)) {	
					$sParm="$sName=\"$oVal\"";
					$sStr.=$sParm.($i==($iSize-1)?"":" ");
				}	
			}
			$sStr =trim($sStr).">";						// if you mind about processing, trim is
			return $sStr;									// here just to beautify stuff, you can remove it
		}

		/**
			Returns the XML close tag
			@return String XML closing tag
		*/
		function getXMLClose() {
			return "</C>";
		}

		/**
			Sets the column type
			@param String type
		*/
		function setType($sType_="REGULAR") {
			$this->_sType=$sType_;
		}

		/**
			Returns the column type
			@return String type
		*/
		function getType() {
			return $this->_sType;
		}

		/**
			Sets the column expression
			@param Object expression
		*/
		function setExpr($sExpr_=null) {
			$this->_sExpr=$sExpr_;
		}

		/**
			Gets the column value
			This function will be always called from a PHPReportRow, that 
			must provide the row number for checking if we must use an odd or even
			class (if its configured that way)
			@param int row number 
			@return Object value
		*/
		function getColValue($iRow_=0) {
			$sBookmark = "";
			$sLinkOpen = "";
			$sLinkClose= "";
			$sImg		  = "";
			$oBm		  = $this->_oBookmark;
			$oLink     = $this->_oLink;
			$oImg		  = $this->_oImg;

			// check if there is some bookmark
			if(!is_null($oBm)) 
				$sBookmark = "<BK HREF=\"".$this->getNextBookmark()."\" CC=\"".$oBm->getCellClass()."\" TC=\"".$oBm->getTextClass()."\">".$oBm->getBookmarkValue($this)."</BK>";
			
			// check if there is some link
			if(!is_null($oLink)) {
				$sLinkOpen = "<LI TITLE=\"".$oLink->getTitle()."\" TARGET=\"".$oLink->getTarget()."\" HREF=\"".$oLink->getLinkValue($this)."\">";
				$sLinkClose= "</LI>";
			}

			// check if there is some image
			if(!is_null($oImg)){
				$iWidth	= $oImg->getWidth();
				$iHeight = $oImg->getHeight();
				$iBorder = $oImg->getBorder();
				$sAlt		= $oImg->getAlt();
				$sImg = "<IMG ".($iWidth>0?" WIDTH=\"$iWidth\"":"").($iHeight>0?" HEIGHT=\"$iHeight\"":"").($iBorder>0?" BORDER=\"$iBorder\"":"").(!empty($sAlt)?" ALT=\"$sAlt\"":"").">".$oImg->getURL()."</IMG>";
			}
			
			// column value	
			$this->avail();
			return $this->getXMLOpen($iRow_).$sBookmark.$sLinkOpen.$sImg.($this->isSuppressed()&&strcmp($this->_oCurVal,$this->_oOldVal)==0?"&#160;":$this->_oCurVal).$sLinkClose.$this->getXMLClose();
		}

		/**
			Returns the last value processed on this column
			@return Object value
		*/
		function getOldValue() {
			return $this->_oOldVal;
		}

		function resetOldValue() {
			$this->_oOldVal=null;
			$this->resetCurValue();
		}

		function resetCurValue() {
			$this->_oCurVal=null;
		}
		
		/**
			Returns the column expression
			@return String expression
		*/		
		function getExpr() {
			return $this->_sExpr;
		}

		/**
			Returns the column evaluated value
			@return Object value
		*/
		function avail() {
			// stores the old value
			$this->_oOldVal=$this->_oCurVal;
			
			// get the column value here
			$this->_oCurVal = $this->availValue($this->_sExpr);
				
			// if its not null and have some special stuff on it 	
			if(!is_null($this->_oCurVal)) {
				// number format
				if(!is_null($this->_sNform))	
					$this->_oCurVal = sprintf($this->_sNform,$this->_oCurVal);
				// number format extended
				if($this->_iNformX>0)
					$this->_oCurVal = number_format($this->_oCurVal,$this->_iNformX,$this->_sDecSep,$this->_sThoSep);	
			}
			return $this->_oCurVal;
		}

		/**
			Return the column value
			@param String value
		*/
		function availValue($sExpr_=null){
			if(is_null($sExpr_))
				return $sExpr_;
			$header	=& $this;
			$oValue	=& $this;

			if($this->_sType=="EXPRESSION") 
				return htmlspecialchars($this->availExpr($sExpr_),ENT_NOQUOTES);
			else if($this->_sType=="RAW_EXPRESSION") 
				return $this->availExpr($sExpr_);
			else if($this->_sType=="FIELD")
				return htmlspecialchars($this->getValue($sExpr_),ENT_NOQUOTES);
			else 
				return $sExpr_;					
		}
		
		/**
			Evaluate the column, if it's the EXPRESSION type
			@param String expression
		*/
		function availExpr($sExpr_=null){
			if(is_null($sExpr_))
				return $sExpr_;
			$header	=& $this;
			$oValue	=& $this;
			return eval($sExpr_);	
		}
		
		/**
			Returns a field value inside the column group
			@param String field
		*/
		function getValue($sField_) {
			return $this->_oGroup->getValue($sField_);
		}

		/**
			Returns the sum of a field inside the column group
			@param String field
		*/		
		function getSum($sField_) {
			return $this->_oGroup->getSum($sField_);
		}
		
		/**
			Returns the max value of a field inside the column group
			@param String field
		*/		
		function getMax($sField_) {
			return $this->_oGroup->getMax($sField_);
		}
		
		/**
			Returns the min value of a field inside the column group
			@param String field
		*/		
		function getMin($sField_) {
			return $this->_oGroup->getMin($sField_);
		}

		function getAvg($sField_) {
			return $this->_oGroup->getAvg($sField_);
		}

		function getRowCount() {
			return $this->_oGroup->getRowCount();
		}

		function getRowNum() {
			$oPage =& $this->_oGroup->getPage();
			return $oPage->getRowNum();
		}
		
		function getPageNum() {
			$oPage =& $this->_oGroup->getPage();
			return $oPage->getPageNum();
		}
		
		/**
			Sets the column group
			@param Object group
		*/
		function setGroup(&$oGroup_) {
			$this->_oGroup=&$oGroup_;
			$oRpt=$oGroup_->getReport();
			if(!is_null($oRpt)) {
				$this->_sDecSep=$oRpt->getDecSep();
				$this->_sThoSep=$oRpt->getThoSep();
			}
		}

		/**
			Set the number format
			@param String format (printf like)
		*/
		function setNumberFormat($sFormat_=null) {
			$this->_sNform=$sFormat_;
		}

		/**
			Return the number format
			@return String format
		*/
		function getNumberFormat() {
			return $this->_sNform;
		}
		
		/**
			Set the number format decimal places
			@param int - number of decimal places
		*/
		function setNumberFormatEx($iNum_=0) {
			$this->_iNformX=$iNum_;
		}

		/**
			Return the number of decimal places
			@return int - number of decimal places
		*/
		function getNumberFormatEx() {
			return $this->_iNformX;
		}

		/**
			Set if the column will print blank values
			when the current value is the same of the
			last printed value
			@param String YES,NO,TRUE,FALSE
		*/
		function suppress($sStr_="FALSE") {
			$sStr = strtoupper($sStr_);
			// don't use strpos here, there's some bug there to check this ...
			if($sStr=="TRUE"||$sStr=="YES")
				$this->_bSuppr=true;
		}

		/**
			Return if its a suppressed values column
			@return boolean 
		*/
		function isSuppressed() {
			return $this->_bSuppr;
		}

		/**
			Set the even row class this column will fit on
			@param String class
		*/
		function setEvenClass($sClass_=null) {
			$this->_sEvenClass=$sClass_;
		}
		
		/**
			Set the odd row class this column will fit on
			@param String class
		*/
		function setOddClass($sClass_=null) {
			$this->_sOddClass=$sClass_;
		}

		function setCellClassExpr($sExpr_=null){
			$this->_sCellClassExpr=$sExpr_;
		}

		/**
			Add a link object in this column 
			@param PHPReportLink link
		*/			
		function addLink($oLink_) {
			$this->_oLink=$oLink_;
		}
		
		/**
			Add a bookmark object in this column 
			@param PHPReportBookmark link
		*/			
		function addBookmark($oBm_) {
			$this->_oBookmark=$oBm_;
		}

		function addImg($oImg_){
			$this->_oImg=$oImg_;
		}

		function getNextBookmark() {
			$oRpt=&$this->_oGroup->getReport();
			return $oRpt->getNextBookmark();
		}

		function getParameter($oKey_) {
			return $this->_oGroup->getParameter($oKey_);
		}

		function getFileName(){
			$oPage =& $this->_oGroup->getPage();
			return basename($oPage->getFileName());
		}

		function getEnvObj($sKey_){
			return $this->_oGroup->getEnvObj($sKey_);
		}
	}
?>
