<?php
	class PHPReportField {
		var $sName;	// field name
		var $sType;	// field type
		var $dVal;	// current value
		var $dMin;	// stores min value
		var $dMax;	// stores max value
		var $dSum;	// stores sum
		var $bNum;	// is it a numeric field?

		/**
			Constructor
		*/		
		function PHPReportField($sName_=null,$sType_=null) {
			$this->sName	= $sName_;
			$this->sType	= $sType_;
			$this->dVal		= -1;
			$this->dMin		= null;
			$this->dMax		= null;
			$this->dSum		= 0;
			$this->bNum		= $this->checkNumeric();
		}

		/**
			Reset field values
		*/
		function reset() {
			$this->dVal	= (is_numeric($this->dVal)?0:"");
			$this->resetStats();
		}

		/**
			Reset statistics
		*/
		function resetStats() {
			$this->dMin	= null;
			$this->dMax	= null;
			$this->dSum	= 0;
		}

		/*
			Field name
		*/	
		function getName() {
			return $this->sName;
		}
		
		/*
			Field type
		*/	
		function getType() {
			return $this->sType;
		}

		/**
			Is a numeric field?
		*/
		function isNumeric(){
			return $this->bNum;
		}

		/**
			Returns if its a numeric field
			@param
		*/
		function checkNumeric() {
			// just make statistics on the numeric fields - change here if your
			// database treats numeric fields with other description
			
			// there is a workaround on numeric fields that some
			// databases don't return the correct type (some ODBC databases)
			// and I return UNDEFINED as the type and presume they are numeric
			$sStr = 
			"NUMBER,NUMERIC,INT,DOUBLE,DECIMAL,REAL,TINY,SHORT,LONG,FLOAT,LONGLONG,INT24,YEAR,CID,FLOAT4,FLOAT8,INT2,".
			"INT4,MONEY,OID,RELTIME,XID,DOUBLE PRECISION,SMALLINT,TINYINT,BIGINT,INT64,INT8,DATE,DATETIME";
			return stristr($sStr,$this->sType);
		}

		/**
			Set the values and statistics about this
			field here.
			@param value
		*/
		function set($oVal_=-1) {
			$this->setVal($oVal_);
			/*
				If not numeric, don't make statistics, or
				if the type is UNDEFINED, tries to make statistics for all kind of
				data. note that is a bug on the way the database returns the data
				type, and not a PHPReports bug.
			*/
			if($this->isNumeric($oVal_) ||
				$this->sType=="UNDEFINED") {
				$this->setMin($oVal_);
				$this->setMax($oVal_);	
				$this->setSum($oVal_);
			}	
		}

		/**
			Set the value
		*/
		function setVal($oVal_=-1) {
			$this->dVal=$oVal_;
		}

		/**
			Return the value
		*/
		function getVal() {
			return $this->dVal;
		}

		/**
			Set the minimum value
		*/		
		function setMin($oVal_=0) {
			if(is_null($this->dMin)){
				$this->dMin=$oVal_;
				return;
			}
			$this->dMin=$oVal_<$this->dMin?$oVal_:$this->dMin;
		}

		/**
			Returns the minimum value
		*/
		function getMin() {
			return $this->dMin;
		}

		/**
			Sets the maximum value
			@param value
		*/
		function setMax($oVal_=0) {
			if(is_null($this->dMax)){
				$this->dMax=$oVal_;
				return;
			}
			$this->dMax=$oVal_>$this->dMax?$oVal_:$this->dMax;
		}

		/**
			Returns the maximum value
		*/
		function getMax() {
			return $this->dMax;
		}

		/**
			Set the field sum
		*/
		function setSum($oVal_=0) {
			$this->dSum+=$oVal_;
		}

		/**
			Returns the field sum
		*/
		function getSum() {
			return $this->dSum;
		}
	}
?>
