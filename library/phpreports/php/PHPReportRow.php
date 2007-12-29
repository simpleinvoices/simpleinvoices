<?php
	class PHPReportRow {
		var $_aCols;	// columns array

		function PHPReportRow() {
			$this->_aCols=Array(); 
		}

		/*
			Add a column in the row
		*/
		function addCol(&$oCol_) {
			array_push($this->_aCols,&$oCol_);
		}

		function &getCols() {
			return $this->_aCols;
		}

		/*
			Returns the XML open tag
		*/
		function getXMLOpen() {
			return "<R>";
		}
	
		
		/*
			Returns the XML close tag
		*/
		function getXMLClose() {
			return "</R>";
		}

		/**
			Prints the row (and all the columns inside of it)
			@param tabs - the tabs that must be inserted before this element
			@param row number - this row number
		*/
		function getRowValue($iRow_=0) {
			$sTabs=""; //"\t\t";
			$sStr	= $sTabs.$this->getXMLOpen(); // there was a \n here on the end
			$iSize=sizeof($this->_aCols);	
			$sSep1=""; // \t
			$sSep2=""; // \n
			
			for($i=0;$i<$iSize;$i++) {
				$oCol	 =& $this->_aCols[$i];
				$oVal  = $oCol->getColValue($iRow_);
				$sStr .= $sSep1.$sTabs.$oVal.$sSep2;
			}
			$sStr.= $sTabs.$this->getXMLClose()."\n";
			return $sStr;
		}

		/**
			Returns the row expression
		*/
		function getExpr(){
			$sStr = "";
			$iSize=sizeof($this->_aCols);	
			for($i=0;$i<$iSize;$i++) {
				$oCol	=& $this->_aCols[$i];
				$sStr .= $oCol->getExpr();
			}
			return $sStr;
		}

		function resetOldValue() {
			$iSize=sizeof($this->_aCols);	
			for($i=0;$i<$iSize;$i++) {
				$oCol	=& $this->_aCols[$i];
				$oCol->resetOldValue();	
			}
		}

		function debug() {
			$iSize=sizeof($this->_aCols);
			for($i=0;$i<$iSize;$i++) {
				$oCol =& $this->_aCols[$i];
				$oCol->debug();
			}
		}
	}
?>
