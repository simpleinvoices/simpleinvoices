<?php
	include_once "PHPReportMaker.php";
	include_once "PHPReportsUtil.php";
	
	/******************************************************************************
	*																										*
	*	Use this file to check how the output plugins works.								*
	*	Please see outmenu.php also.																*
	*	It need to be placed on a directory reached by the web server.					*
	*																										*
	******************************************************************************/
	$sOut = $_REQUEST["output"];
	$sURL = realpath(getPHPReportsTmpPath()."/".$_REQUEST["file"]);

	if(empty($sOut)||empty($sURL))
		return;
	
	$oRpt	= new PHPReportMaker();
	$oOut = $oRpt->createOutputPlugin($sOut);
	$oOut->setInput($sURL);
	$oOut->setClean(false);
	$oOut->run();
?>
