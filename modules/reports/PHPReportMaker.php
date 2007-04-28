<?php
	require_once("./modules/reports/PHPReportsUtil.php");

	/******************************************************************************
	*                                                                             *
	*	PHPReportMaker                                                             *
	*	This is the main class of PHPReports                                       *
	*                                                                             *
	*	Use like this:                                                             *
	*	$oRpt = new PHPReportMaker();                                              *
	*	$oRpt->setXML("test.xml");                                                 *
	*	$oRpt->setXSLT("test.xsl");                                                *
	*	$oRpt->setUser("john");                                                    *
	*	$oRpt->setPassword("doe");                                                 *
	*	$oRpt->setConnection("mydatabaseaddr");                                    *
	*	$oRpt->setDatabaseInterface("oracle");                                     *
	*	$oRpt->setSQL("select * from mytable");                                    *
	*	$oRpt->run();                                                              *
	*                                                                             *
	******************************************************************************/
	class PHPReportMaker {
		var $_sPath;			// PHPReports path
		var $sXML;				// XML report file
		var $sXSLT;				// XSLT file
		var $sUser;				// user name
		var $sPass;				// password
		var $sCon;				// connection name
		var $sDataI;			// database interface
		var $sSQL;				// sql query command
		var $_oParm;			// parameters
		var $sDatabase;		// database
		var $sCodeOut;			// code output
		var $sOut;				// HTML result file
		var $bDebug;			// debug report
		var $_sNoDataMsg;		// no data message - NEW!!! on 0.2.0
		var $_sOutputPlugin;	// output plugin name - NEW!!! on 0.2.0
		var $_oOutputPlugin;	// output plugin - NEW!!! on 0.2.0 
		var $_sXMLOutputFile;// XML output file with the data
		var $_sSaveTo;			// save to file - NEW!!! 0.2.0
		var $_oProc;			// XSLT processor
		var $_aEnv;				// enviroment vars
		var $_sClassName;		// report class name to create
		var $_sTmp;				// temporary dir
		var $_oCon;				// database connection handle

		/***************************************************************************
		*																									*
		*	Constructor - remember to set the PHPREPORTS										*
		*	environment variable																		*
		*																									*
		***************************************************************************/		
		function PHPReportMaker() {
			$this->_sPath				= getPHPReportsFilePath();
			$this->_sTmp				= getPHPReportsTmpPath();
			$this->sXML					= null;		
			$this->sXSLT				= $this->_sPath."/xslt/PHPReport.xsl";		
			$this->sUser				= null;		
			$this->sPass				= null;		
			$this->sCon					= null;		
			$this->sDataI				= null;	
			$this->sSQL					= null;		
			$this->_oParm				= Array();		
			$this->sDatabase			= null;
			$this->sCodeOut			= null;	
			$this->sOut					= null;		
			$this->bDebug				= false;
			$this->_sNoDataMsg		= "";
			$this->_sOutputPlugin	= "default";
			$this->_oOutputPlugin	= null;
			$this->_sSaveTo			= null;
			$this->_aEnv				= Array();
			$this->_sClassName		= "PHPReport";

			/*
				Now we get the XSLT processor
				new code on the 0.2.8 version, because PHP5 have XSL
				support with libxslt, by default
			*/			
			$oProcFactory = new XSLTProcessorFactory();
			$this->_oProc = $oProcFactory->get();
			if(is_null($this->_oProc))
				new PHPReportsError("There is no XSLT processor available.<br/>".
										  "Check if you compiled with Sablotron (PHP4) with --enable-xslt or<br/>".
										  "PHP5 XSL support, with --enable-xsl, correctly.","noxsltprocessor.php");
				
			// check path stuff
			if(is_null(getPHPReportsFilePath()))
				new PHPReportsError("Seems that you didn't the PHPReports path on the PHP include_path.<br/>".
				                    "I don't know where the classes are.","nopath.php");
		}

		/******************************************************************************
		*																										*
		*	Run report																						*
		*	Here is where things happens. :-)														*
		*																										*
		******************************************************************************/
		function run() {
			// create the parameters array
			$aParm["user"     ] = $this->sUser;				// set user
			$aParm["pass"     ] = $this->sPass;				// set password
			$aParm["conn"     ] = $this->sCon;				// set connection name
			$aParm["interface"] = $this->sDataI;			// set database interface
			$aParm["database" ] = $this->sDatabase;		// set database
			$aParm["classname"] = $this->_sClassName;		// ALWAYS use this class to run the report
			$aParm["sql"      ] = $this->sSQL;				// set the sql query
			$aParm["nodatamsg"] = $this->_sNoDataMsg;		// no data msg

			// create the parameters keys array - with element numbers or element keys
			$aKeys = null;
			if(!is_null($this->_oParm)){
				$aKeys = array_keys($this->_oParm);
				$iSize = sizeof($this->_oParm);
				for($i=0; $i<$iSize; $i++){
					$sOkey = $aKeys[$i];	// original key
					$sKey	 = $sOkey;		// reference key
				
					// check if its a numeric key - if so, add 1 to
					// it to keep the parameters based on 1 and not on 0
					if(is_numeric($sOkey)) 
						$sKey = intval($sOkey)+1;
			
					$aParm["parameter".($i+1)] = $this->_oParm[$sOkey];
					$aParm["reference".($i+1)] = $sKey;
				}
			}		

			// if there is not a file to create the code,
			// create it on the memory (faster, use file just for 
			// debugging stuff)
			if(is_null($this->sCodeOut)) {
				$sOut = null;
				$aParm["output_format"]="memory";
			}else{
				$sOut = $this->sCodeOut;		
				$aParm["output_format"]="file";
			}

			// XSLT processing
			$this->_oProc->setXML($this->sXML);
			$this->_oProc->setXSLT($this->sXSLT);
			$this->_oProc->setOutput($sOut);
			$this->_oProc->setParms($aParm);
			$sRst = $this->_oProc->run();

			// Auto-generated PHPReport_* classes have a nasty habit
			// of being full of random futz, so catch it and throw
			// it away
			ob_start();

			// if its created on the memory ...
			if(is_null($sOut))
				eval($sRst);
			else {
			// include the generated classes, if it was created
				if(!file_exists($sOut)){
					new PHPReportsError("PHPReports could not create the output code to make your report ".
											  "runs. Please check if the web server user have rights to write ".
											  "in your ".dirname($sOut)." directory. Script aborted.");
				}
				require_once($sOut);
			}	
			ob_end_clean();

			// include the generated class	
			$oReport = new $this->_sClassName;
			
			// set the database connection handle, if there is one
			$oReport->setDatabaseConnection($this->_oCon);

			// run the generated class
			$this->_sXMLOutputFile = $oReport->run($this->_sXMLOutputFile,$this->_aEnv);

			// check if the XML file exists, we need data!
			if(!file_exists($this->_sXMLOutputFile)){
				new PHPReportsError("PHPReports could not find the XML file with your data (".$this->_sXMLOutputFile.") to make your report ".
										  "runs. Please check if the web server user have rights to write ".
										  "in your temporary directory. Script aborted.");
			}

			/*
				Now we have a XML file with the report contents ... what to to with it???
				Let's call the output plugin!
			*/	
					
			//	if there is no one, create a new default plugin
			$oOut = null;
			if(is_null($this->_oOutputPlugin)) {
				$oOut = $this->createOutputPlugin("default");
				$oOut->setInput ($this->_sXMLOutputFile);
				$oOut->setOutput($this->sOut);
				$this->setOutputPlugin($oOut);
			}else{
				$oOut = $this->_oOutputPlugin;
				$oOut->setInput($this->_sXMLOutputFile);
				if(!is_null($this->sOut))
					$oOut->setOutput($this->sOut);
			}

			// if need to save it
			if(!is_null($this->_sSaveTo))
				$this->save();

			// run 	
			$oOut->run();
			return $this->_sXMLOutputFile;
		}

		/******************************************************************************
		*																										*
		*	Set the XML file path																		*
		*	@param String file path																		*
		*																										*
		******************************************************************************/		
		function setXML($sXML_) {
			if(!file_exists($sXML_))
				new PHPReportsError("File $sXML_ was not found.");
			$this->sXML = $sXML_;
		}

		/******************************************************************************
		*																										*
		*	Returns the XML file path																	*
		*	@return String file path																	*
		*																										*
		******************************************************************************/		
		function getXML() {
			return $this->sXML;
		}
		
		/******************************************************************************
		*																										*
		*	Sets the XSLT file path																		*
		*	@param String file path																		*
		*																										*
		******************************************************************************/		
		function setXSLT($sXSLT_) {
			if(!file_exists($sXSLT_))
				new PHPReportsError("File $sXSLT_ was not found.");
			$this->sXSLT = $sXSLT_;
		}

		/******************************************************************************
		*																										*
		*	Returns the XSLT file path																	*
		*	@return String file path																	*
		*																										*
		******************************************************************************/		
		function getXSLT() {
			return $this->sXSLT;
		}
		
		/******************************************************************************
		*																										*
		*	Set the user name																				*
		*	@param String user name																		*
		*																										*
		******************************************************************************/		
		function setUser($sUser_) {
			$this->sUser = $sUser_;
		}

		/******************************************************************************
		*																										*
		*	Returns the user name																		*
		*	@return String user name																	*
		*																										*
		******************************************************************************/		
		function getUser() {
			return $this->sUser;
		}

		/******************************************************************************
		*																										*
		*	Sets the password																				*
		*																										*
		******************************************************************************/		
		function setPassword($sPass_) {
			$this->sPass = $sPass_;
		}

		/******************************************************************************
		*																										*
		*	Returns the password																			*
		*																										*
		******************************************************************************/		
		function getPassword() {
			return $this->sPass;
		}

		/******************************************************************************
		*																										*
		*	Sets the database connection																*
		*																										*
		******************************************************************************/		
		function setConnection($sCon_) {
			$this->sCon = $sCon_;
		}

		/******************************************************************************
		*																										*
		*	Returns the password																			*
		*																										*
		******************************************************************************/		
		function getConnection() {
			return $this->sCon;
		}

		/******************************************************************************
		*																										*
		*	Sets the database interface																*
		*																										*
		******************************************************************************/		
		function setDatabaseInterface($sData_) {
			$this->sDataI = $sData_;
		}

		/******************************************************************************
		*																										*
		*	Returns the database interface															*
		*																										*
		******************************************************************************/		
		function getDatabaseInterface() {
			return $this->sDataI;
		}

		/******************************************************************************
		*																										*
		*	Sets the SQL query																			*
		*																										*
		******************************************************************************/		
		function setSQL($sSQL_) {
			$this->sSQL = $sSQL_;
		}

		/******************************************************************************
		*																										*
		*	Returns the SQL query																		*
		*																										*
		******************************************************************************/		
		function getSQL() {
			return $this->sSQL;
		}

		/******************************************************************************
		*																										*
		*	Sets the parameters																			*
		*																										*
		******************************************************************************/		
		function setParameters($oParm_) {
			$this->_oParm = $oParm_;
		}

		/******************************************************************************
		*																										*
		*	Returns the parameters																		*
		*																										*
		******************************************************************************/		
		function getParameters() {
			return $this->_oParm;
		}

		/******************************************************************************
		*																										*
		*	Sets the database																				*
		*																										*
		******************************************************************************/		
		function setDatabase($sData_) {
			$this->sDatabase = $sData_;
		}

		/******************************************************************************
		*																										*
		*	Returns the database																			*
		*																										*
		******************************************************************************/		
		function getDatabase() {
			return $this->sDatabase;
		}

		/******************************************************************************
		*																										*
		*	Sets the code output file																	*
		*																										*
		******************************************************************************/		
		function setCodeOutput($sFile_) {
			$this->sCodeOut = $sFile_;
		}

		/******************************************************************************
		*																										*
		*	Returns the database																			*
		*																										*
		******************************************************************************/		
		function getCodeOutput() {
			return $this->sCodeOut;
		}

		/******************************************************************************
		*																										*
		*	Sets the output path																			*
		*																										*
		******************************************************************************/		
		function setOutput($sOut_) {
			$this->sOut = $sOut_;
		}

		/******************************************************************************
		*																										*
		*	Returns output path																			*
		*																										*
		******************************************************************************/		
		function getOutput() {
			return $this->sOut;
		}

		/******************************************************************************
		*																										*
		*	Sets if the report will generate debug info after it runs						*
		*																										*
		******************************************************************************/		
		function setDebug($bDesc) {
			$this->bDebug = $bDesc;
		}

		/******************************************************************************
		*																										*
		*	Returns if will debug																		*
		*																										*
		******************************************************************************/		
		function getDebug() {
			return $this->bDebug;
		}

		/******************************************************************************
		*																										*
		*	Sets message to be shown when no data returns from the query					*
		*	@param String message																		*
		*																										*
		******************************************************************************/		
		function setNoDataMsg($sMsg_="") {
			$this->_sNoDataMsg=$sMsg_;
		}

		/******************************************************************************
		*																										*
		*	Returns the no data message																*
		*	@return String message																		*
		*																										*
		******************************************************************************/		
		function getNoDataMsg() {
			return $this->_sNoDataMsg;
		}

		/******************************************************************************
		*																										*
		*	Create the output plugin																	*
		*	@param name																						*
		*																										*
		******************************************************************************/		
		function createOutputPlugin($sName_) {
			$sFullPath = $this->_sPath."/output/$sName_/PHPReportOutput.php";
			
			// check if the required plugin exists
			if(!file_exists($sFullPath))
				new PHPReportsError("There is no $sName_ output plugin ($sFullPath)");
			include_once $sFullPath; 
			
			$oOut = new PHPReportOutput($this->sXML);
			return $oOut;
		}

		/******************************************************************************
		*																										*
		*	Output plugin for the final format														*
		*	@param plugin																					*
		*																										*
		******************************************************************************/		
		function setOutputPlugin($oPlugin_) {
			$this->_oOutputPlugin=$oPlugin_;
		}

		/******************************************************************************
		*																										*
		*	Returns the output plugin																	*
		*	@return plugin																					*
		*																										*
		******************************************************************************/		
		function getOutputPlugin() {
			return $this->_oOutputPlugin;
		}

		/******************************************************************************
		*																										*
		*	Set the XML output/data file																*
		*																										*
		******************************************************************************/		
		function setXMLOutputFile($sFile_=null){
			$this->_sXMLOutputFile=$sFile_;
		}

		/******************************************************************************
		*																										*
		*	Returns the XML output/data file															*
		*																										*
		******************************************************************************/		
		function getXMLOutputFile(){
			return $this->_sXMLOutputFile;
		}

		/******************************************************************************
		*																										*
		*	File path to save the report																*
		*	Please remember to use a writable path!												*
		*																										*
		******************************************************************************/		
		function saveTo($sFile_=null){
			if(is_null($sFile_))
				return;
			$this->_sSaveTo=$sFile_;	
		}

		/******************************************************************************
		*																										*
		*	Save report																						*
		*																										*
		******************************************************************************/		
		function save(){
			if(is_null($this->_sSaveTo))
				return;
			$sIn  = $this->_sXMLOutputFile;	
			$sMD5 = md5_file($sIn);		// calculate the md5 checksum
			$sMD5	= str_pad($sMD5,50);	// padding
			$sOut = "compress.zlib://".$this->_sSaveTo;
			$fIn	= fopen($sIn,"r");
			$fOut = fopen($sOut,"w");
			
			// write the md5sum 
			fwrite($fOut,$sMD5);
			
			while($sStr=fread($fIn,1024))
				fwrite($fOut,$sStr);
			fclose($fOut);
			fclose($fIn);				
		}

		/******************************************************************************
		*																										*
		*	Preview report																					*
		*																										*
		******************************************************************************/		
		function preview($sXML_=null){
			if(is_null($sXML_))
				return;
				
			if(!file_exists($sXML_)){
				print "<b>The file $sXML_ doesn't exists.</b><br>";
				return;
			}
			
			$sPath  = getPHPReportsFilePath();
			$sXSLT  = "$sPath/xslt/PHPReportPreview.xsl";

			// XSLT processing
			$this->_oProc->setXML($sXML_);
			$this->_oProc->setXSLT($sXSLT);
			print $this->_oProc->run();
		}

		/******************************************************************************
		*																										*
		*	Put an object to the environment array.												*
		*	You can use this function to expose any kind of variable or class to your	*
		*	report (using <COL>$this->getEnv("id")</COL>). Note that for using objects	*
		*	returned by this function directly as													*
		*	<COL>$this->getEnv("id")->myFunction()</COL>											*
		*	you'll need PHP5.																				*
		*																										*
		******************************************************************************/		
		function putEnvObj($sKey_=null,$oObj_=null){
			if(is_null($sKey_) ||
				is_null($oObj_))
				return;
			$this->_aEnv[$sKey_]=$oObj_;	
		}

		/******************************************************************************
		*																										*
		*	Returns an object from the environment array.										*
		*																										*
		******************************************************************************/		
		function getEnvObj($sKey_){
			return $this->_aEnv[$sKey_];
		}

		/******************************************************************************
		*																										*
		*	Set the name of the class that will be created										*
		*	to run the report.																			*
		*	To see where this name is used, please check xslt/PHPReport.xsl				*
		*																										*
		******************************************************************************/		
		function setClassName($sClassName_="PHPReport"){
			$this->_sClassName=$sClassName_;
		}

		/******************************************************************************
		*																										*
		*	Returns the name of the class that will be created									*
		*	to run the report.																			*
		*																										*
		******************************************************************************/		
		function getClassName(){
			return is_null($this->_sClassName)?"PHPReport":$this->_sClassName;
		}

		/******************************************************************************
		*																										*
		*	Set the database connection handle														*
		*																										*
		******************************************************************************/
		function setDatabaseConnection(&$_oCon){
			$this->_oCon =& $_oCon;
		}		
	}
?>
