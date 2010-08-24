<?php
	/**
		Column bookmark object
		It can be of three types:
		1) DYNAMIC    - get the link value from the field value specified on the LINK element text
		2)	STATIC     - get the link value from the LINK element text
		3) EXPRESSION - get the link value from the evaluated LINK element text
	*/	
	class PHPReportBookmark {
		var $_sType;
		var $_sExpr;
		var $_iId;
		var $_sCellClass;	// cell class
		var $_sTextClass;	// cell class

		function PHPReportBookmark($sType_="STATIC",$sExpr_="",$iId_=0,$sCellClass_="") {
			$this->_sType			=$sType_;
			$this->_sExpr			=$sExpr_;
			$this->_iId				=$iId_;
			$this->_sCellClass	=$sCellClass_;
		}

		function setType($sType_="STATIC") {
			$this->_sType=strtoupper($sType_);
		}
		
		function getType() {
			return $this->_sType;
		}
		
		function setExpr($sExpr_="") {
			$this->_sExpr=$sExpr_;
		}
		
		function getExpr() {
			return $this->_sExpr;
		}

		function setId($iId_) {
			$this->_iId=$iId_;
		}

		function getId() {
			return $this->_iId;
		}
		
		function setCellClass($sCellClass_) {
			$this->_sCellClass=$sCellClass_;
		}

		function getCellClass() {
			return $this->_sCellClass;
		}
		
		function setTextClass($sTextClass_) {
			$this->_sTextClass=$sTextClass_;
		}

		function getTextClass() {
			return $this->_sTextClass;
		}

		function getBookmarkValue($oCol_=null) {
			$sVal		= "";
			$oError	= new PHPReportsErrorTr();

			if($this->_sType=="STATIC")
				$sVal=$this->_sExpr;
			else if($this->_sType=="DYNAMIC") {
				if(!isset($oCol_))
					$oError->showMsg("DYNBOOK");
				$sVal=$oCol_->getValue($this->_sExpr);
			}else if($this->_sType=="EXPRESSION") 						
				if(!isset($oCol_))
					$oError->showMsg("EXPBOOK");
				$sVal = $oCol_->availExpr($this->_sExpr);
			return $sVal;
		}
	}
?>
