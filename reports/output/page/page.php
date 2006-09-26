<?php
	// MUST be on the include_path
	require_once("PHPReportsUtil.php");
	
	$sPath  = getPHPReportsFilePath();
	$sXML	  = $_REQUEST["xmlfile"];
	$sXSLT  = $sPath."/output/page/page.xsl";
	$aParm  = Array();

	$l1	  = intval($_REQUEST["l1"]);
	$l2	  = intval($_REQUEST["l2"]);
	$incr	  = intval($_REQUEST["incr"]);
	$curpage= intval($_REQUEST["curpage"]);

	if($curpage>$incr) {
		$l1=$curpage-(($curpage%$incr==0?$incr:$curpage%$incr))+1;
		$l2=$l1+($incr-1);
	}else if($curpage<=$incr){
		$l1=1;
		$l2=$l1+($incr-1);
	}

	$aParm["xmlfile"] = $_REQUEST["xmlfile"];
	$aParm["curpage"]	= $curpage;
	$aParm["incr"]		= $incr;
	$aParm["l1"]		= $l1;
	$aParm["l2"]		= $l2;
	$aParm["first"]	= $_REQUEST["first"];
	$aParm["last"]		= $_REQUEST["last"];
	$aParm["next"]		= $_REQUEST["next"];
	$aParm["prev"]		= $_REQUEST["prev"];
	$aParm["url"]		= $_REQUEST["url"];

	$oProcFactory = new XSLTProcessorFactory();
	$oProc = $oProcFactory->get();
	$oProc->setXML($sXML);
	$oProc->setXSLT($sXSLT);
	$oProc->setParms($aParm);
	$sRst = $oProc->run();
	print $sRst;
?>
