<?php
	if(intval(substr(phpversion(),0,1)<5)){
		print "THIS IS AN ERROR MESSAGE!!!\n";
		print "XSL support are only ok with PHP5.\n";
		print "Please use Sablotron with PHP < 5\n";
		print "Aborting script ...\n";
		return;
	}

	$oXML = new DomDocument();
	$oXML->load("../xsltest.xml");

	$oXSL = new DomDocument();
	$oXSL->load("../xslt/xsltest.xsl");

	$oProc = new XSLTProcessor();
	$oProc->importStyleSheet($oXSL);
	$oProc->setParameter("","html","ok");
	print $oProc->transformToXML($oXML);
?>
