<?php
	if(intval(substr(phpversion(),0,1)>=5)){
		print "THIS IS A WARNING MESSAGE!!!\n";
		print "Sablotron support on PHP5 is deprecated.\n";
		print "Please use the PHP XSL extension on PHP >= 5.\n";
		print "Script will continue, but may fail.\n";
	}
		
	$oXSLT	= xslt_create();
	$aArg		= Array();
	$aParm	= Array();
	
	$aParm["html"] = "ok";
	print xslt_process($oXSLT,"file://../sabtest.xml","file://../xslt/sabtest.xsl",null,$aArg,$aParm);
	xslt_free($oXSLT);
?>
