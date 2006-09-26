<?php
	class PHPReportRpt {
		var $sTitle;
		var $sPath;
		var $sBackgroundColor;
		var $sBackgroundImage;
		var $sCSS;
		var $sBookmarksCSS;
		var $iMarginWidth;
		var $iMarginHeight;
		var $sDecSep;		
		var $sThoSep;	
		var $sNoDataMsg;
		var $_bCaseSensitive;	// case sensitive fields
		var $_bReportEnd;			// did the report ends?
		var $_iMaxRowBuffer;		// max row buffer

		var $oForm;
		var $oDocument;
		var $oPage;
		var $oGroups;
		var $aParameters;
		var $_iBookmarkCtrl;
		var $_aCSS;
		var $_aEnv;
		
		function PHPReportRpt($aEnv_=null) {
			$this->sTitle				= "untitled";
			$this->sPath				= "";
			$this->sBackgroundColor	= "#FFFFFF";
			$this->sBackgroundImage	= null;
			$this->sCSS					= null;
			$this->sBookmarksCSS		= null;
			$this->sSQL					= null;
			$this->sNoDataMsg			= "NO DATA FOUND";
			$this->bCaseSensitive	= true;
			$this->_iBookmarkCtrl	= 0;
			$this->_bReportEnd		= false;
			$this->_iMaxRowBuffer	= 2500;
			$this->_aCSS				= Array();
			$this->_aEnv				= $aEnv_;
			$this->configSep();
		}

		// title
		function setTitle($sTitle_="untitled") {
			$this->sTitle=$sTitle_;
		}
		function getTitle() {
			return $this->sTitle;
		}
		
		// path
		function setPath($sPath_="") {
			$this->sPath=$sPath_;
		}
		function getPath() {
			return $this->sPath;
		}
		
		// background color
		function setBackgroundColor($sColor_="") {
			$this->sBackgroundColor=$sColor_;
		}
		function getBackgroundColor() {
			return $this->sBackgroundColor;
		}
		
		// background image
		function setBackgroundImage($sImg_) {
			$this->sBackgroundImage=$sImg_;
		}
		function getBackgroundImage() {
			return $this->sBackgroundImage;
		}

		// no data found message
		function setNoDataMsg($sMsg_="NO DATA FOUND") {
			$this->sNoDataMsg=$sMsg_;
		}
		function getNoDataMsg() {
			return $this->sNoDataMsg;
		}

		function addCSS($sCSS_,$sMedia_=""){
			array_push($this->_aCSS,Array($sCSS_,$sMedia_));
		}
		
		function getCSS() {
			return $this->_aCSS;
		}
		
		// bookmarks css file 
		function setBookmarksCSS($sCSS_) {
			$this->sBookmarksCSS=$sCSS_;
		}
		function getBookmarksCSS() {
			return $this->sBookmarksCSS;
		}
		
		// page 
		function setPage(&$oPage_) {
			$this->oPage=&$oPage_;
		}
		function &getPage() {
			return $this->oPage;
		}

		// form
		function setForm(&$oForm_) {
			$this->oForm=&$oForm_;
		}
		function &getForm() {
			return $this->oForm;
		}

		// margin width
		function setMarginWidth($iWidth_) {
			$this->iMarginWidth=$iWidth_;
		}
		function getMarginWidth() {
			return $this->iMarginWidth;
		}

		// margin height
		function setMarginHeight($iHeight_) {
			$this->iMarginHeight=$iHeight_;
		}
		function getMarginHeight() {
			return $this->iMarginHeight;
		}

		// parameters
		function setParameters($aParms_) {
			$this->aParameters=$aParms_;
		}
		function getParameters() {
			return $this->aParameters;
		}
		function getParameter($oKey_){
			return $this->aParameters[$oKey_];
		}

		/*
			Configure numeric separators
		*/
		function configSep() {
			$oLocale				= localeconv();
			$this->sDecSep		= $oLocale["mon_decimal_point"];
			$this->sThoSep		= $oLocale["mon_thousands_sep"];

			if(strlen($this->sDecSep)<1)
				$this->sDecSep = ".";
			if(strlen($this->sThoSep)<1)
				$this->sThoSep = ",";
		}
		function getDecSep() {
			return $this->sDecSep;
		}
		function getThoSep() {
			return $this->sThoSep;
		}

		function getNextBookmark() {
			return $this->_iBookmarkCtrl++;
		}

		function setReportEnd($bEnd_) {
			$this->_bReportEnd=$bEnd_;
		}

		function isReportEnd() {
			return $this->_bReportEnd;
		}

		function setMaxRowBuffer($iBuffer_=2500){
			$this->_iMaxRowBuffer=$iBuffer_;
		}

		function getMaxRowBuffer(){
			return $this->_iMaxRowBuffer;
		}

		function getEnvObj($sKey_){
			if(!array_key_exists($sKey_,$this->_aEnv))
				return null;
			return $this->_aEnv[$sKey_];
		}
	}
?>
