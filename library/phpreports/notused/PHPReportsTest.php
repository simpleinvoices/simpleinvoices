<?php
	// insert your phpreports path here
	ini_set("include_path",ini_get("include_path").":/home/taq/network/phpreports/");
	require_once("PHPReportMaker.php");

	print "Running PHPReports test.\n";

	// configure your parameters here
	$sUser = "";	// database user
	$sPass = "";	// database password
	$sData = "";	// database name
	$sInte = "";	// database interface
	$sConn = "";	// database connection

	// check them
	if(strlen($sUser)<1 ||
		strlen($sPass)<1 ||
		strlen($sInte)<1 ||
		strlen($sConn)<1){
		print "ERROR: please configure this script before run!";
		return;		
	}

	// check paths
	$sIncPath	= getPHPReportsIncludePath();
	$sFilPath	= getPHPReportsFilePath();
	$sTmpPath	= getPHPReportsTmpPath();

	print "Checking paths ...\n";
	if(is_null($sIncPath) || strlen(trim($sIncPath))<=0){
		print "ERROR: No INCLUDE path defined.";
		return;
	}
	if(is_null($sFilPath) || strlen(trim($sFilPath))<=0){
		print "ERROR: No FILE path defined.";
		return;
	}
	if(is_null($sTmpPath) || strlen(trim($sTmpPath))<=0){
		print "ERROR: No TEMP path defined.";
		return;
	}
	print "Include path : $sIncPath\n";
	print "File path    : $sFilPath\n";
	print "Temp path    : $sTmpPath\n";
	
	// create some temporary files 
	print "Creating temporary file names ...\n";
	$sCode	= tempnam(null,"code");
	$sXMLOut	= tempnam(null,"xml");
	$sHTMLOut= tempnam(null,"html");

	// create the report maker object with all the debugging stuff we can
	print "Creating the report maker object ...\n";
	$oRpt = new PHPReportMaker();
	$oRpt->setXML("sales.xml");
	$oRpt->setUser($sUser);
	$oRpt->setPassword($sPass);
	$oRpt->setDatabase($sData);
	$oRpt->setDatabaseInterface($sInte);
	$oRpt->setConnection($sConn);
	$oRpt->setCodeOutput($sCode);
	$oRpt->setXMLOutputFile($sXMLOut);
	$oRpt->setOutput($sHTMLOut);

	print "Creating the default output plugin ...\n";
	$oOut = $oRpt->createOutputPlugin("default");
	if(is_null($oOut)){
		print "ERROR: could not create an output plugin.";
		return;
	}
	$oOut->setClean(false);
	$oRpt->setOutputPlugin($oOut);

	print "Running the report, please wait ...\n";
	$oRpt->run();

	// check if everything was ok
	if(!file_exists($sCode))
		print "ERROR: code file $sCode does not exists, no code to process."; 
	if(filesize($sCode)<=0)
		print "ERROR: code file $sCode does not have a valid size, no code to process."; 

	if(!file_exists($sXMLOut))
		print "ERROR: XML data file $sXMLOut does not exists, no data to process."; 
	if(filesize($sXMLOut)<=0)
		print "ERROR: XML data file $sXMLOut does not have a valid size, no data to process."; 
		
	if(!file_exists($sHTMLOut))
		print "ERROR: HTML result file $sHTMLOut does not exists, no result to show."; 
	if(filesize($sHTMLOut)<=0)
		print "ERROR: HTML result file $sHTMLOut does not have a valid size, no result to show."; 

	// show the result files	
	print "Report done, here are the files:\n";
	print "Code        : $sCode\n";
	print "XML data    : $sXMLOut\n";
	print "HTML result : $sHTMLOut\n";
?>
