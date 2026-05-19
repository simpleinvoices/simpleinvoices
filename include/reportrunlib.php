<?php

	$oRpt->setSQL($sSQL);
	
	//remove body
	$oRpt->setBody(false);

	ob_start();
	$oRpt->run();
	$showReport = ob_get_contents();
	ob_end_clean();
   
	//remove doctype
	$showReport =preg_replace('#<!DOCTYPE[^>]+>#','',$showReport);


	$pageActive = "reports";

	$bladeView->assign('pageActive', $pageActive);
	$bladeView->assign('showReport', $showReport);
	
?>