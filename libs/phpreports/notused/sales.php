<?php
	require_once("PHPReportMaker.php");
	
	/******************************************************************************
	*																										*
	*	Use this file to see a sample of PHPReports.											*
	*	Please check the PDF manual for see how to use it.									*
	*	It need to be placed on a directory reached by the web server.					*
	*																										*
	******************************************************************************/
	$oRpt = new PHPReportMaker();
	$oRpt->setUser("taq");
	$oRpt->setPassword("******");
	$oRpt->setXML("sales.xml");
	$oOut = $oRpt->createOutputPlugin("default");
	$oRpt->setOutputPlugin($oOut);
	$oRpt->run();
?>
