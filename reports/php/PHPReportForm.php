<?php
	class PHPReportForm extends PHPReportXMLElement {
		var $sName;
		var $sMethod;
		var $sAction;

		function PHPReportForm() {
			$this->sName=null;
			$this->sMethod=null;
			$this->sAction=null;
		}

		function setName($sName_) {
			$this->sName=$sName_;
		}
		function setMethod($sMethod_) {
			$this->sMethod=$sMethod_;
		}
		function setAction($sAction_) {
			$this->sAction=$sAction_;
		}

		function getXMLOpen() {
			return "<FORM NAME='".$this->sName."' METHOD='".$this->sMethod."' ACTION='".$this->sAction."'>";
		}

		function getXMLClose() {
			return "</FORM>";
		}
	}
?>
