<?php
	class PHPReportImg{
		var $_sURL;
		var $_iWidth;
		var $_iHeight;
		var $_iBorder;
		var $_sAlt;

		function PHPReportImg($sURL_=null,$iWidth_=-1,$iHeight_=-1,$iBorder_=-1,$sAlt_=null){
			$this->_sURL=$sURL_;
			$this->_iWidth=$iWidth_;
			$this->_iHeight=$iHeight_;
			$this->_iBorder_=$iBorder_;
			$this->_sAlt=$sAlt_;
		}

		function setURL($sURL_=null){
			$this->_sURL=$sURL_;
		}
		function getURL(){
			return $this->_sURL;
		}

		function setWidth($iWidth_=-1){
			$this->_iWidth=$iWidth_;
		}
		function getWidth(){
			return $this->_iWidth;
		}

		function setHeight($iHeight_=-1){
			$this->_iHeight=$iHeight_;
		}
		function getHeight(){
			return $this->_iHeight;
		}

		function setBorder($iBorder_=-1){
			$this->_iBorder=$iBorder_;
		}
		function getBorder(){
			return $this->_iBorder;
		}

		function setAlt($sAlt_=-1){
			$this->_sAlt=$sAlt_;
		}
		function getAlt(){
			return $this->_sAlt;
		}
	}
?>
