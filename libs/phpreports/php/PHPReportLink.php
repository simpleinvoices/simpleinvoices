<?php
	/**
		Column link object
	*/	
	class PHPReportLink {
		var $_sType;
		var $_sExpr;
		var $_sTitle;
		var $_sTarget;

		/**
			Constructor
		*/
		function PHPReportLink($sType_="STATIC",$sExpr_="",$sTitle_="",$sTarget_="") {
			$this->_sType	=$sType_;
			$this->_sExpr	=$sExpr_;
			$this->_sTitle	=$sTitle_;
			$this->_sTarget=$sTarget_;
		}

		/**
			Set the type
			It can be of three types:
			1) DYNAMIC    - get the link value from the field value specified on the LINK element text
			2)	STATIC     - get the link value from the LINK element text
			3) EXPRESSION - get the link value from the evaluated LINK element text
		*/
		function setType($sType_="STATIC") {
			$this->_sType=strtoupper($sType_);
		}

		/**
			Get the type
		*/		
		function getType() {
			return $this->_sType;
		}

		/**
			Set the expression that will be evaluated on the link
		*/		
		function setExpr($sExpr_="") {
			$this->_sExpr=$sExpr_;
		}
		
		/**
			Get the expression
		*/
		function getExpr() {
			return $this->_sExpr;
		}

		/**
			Set the title (it's the tooltip the browser shows)
		*/		
		function setTitle($sTitle_="") {
			$this->_sTitle=$sTitle_;
		}

		/**
			Return the title
		*/		
		function getTitle() {
			return $this->_sTitle;
		}

		/**
			Set the target frame
			Same funcionality as the HTML target
		*/
		function setTarget($sTarget_="") {
			$this->_sTarget=$sTarget_;
		}

		/**
			Returns the target frame
		*/	
		function getTarget() {
			return $this->_sTarget;
		}

		/**
			Returns this link value based on it's expression and type
		*/
		function getLinkValue($oCol_) {
			$sVal		= "";
			$oError	= new PHPReportsErrorTr();
			if($this->_sType=="STATIC")
				$sVal=$this->_sExpr;
			else if($this->_sType=="DYNAMIC") {
				if(!isset($oCol_))
					$oError->showMsg("DYNLINK");
				$sVal=$oCol_->getValue($this->_sExpr);
			}else if($this->_sType=="EXPRESSION") {						
				if(!isset($oCol_))
					$oError->showMsg("EXPLINK");
				$sVal = htmlspecialchars($oCol_->availExpr($this->_sExpr));
			}
			return $sVal;
		}
	}
?>
