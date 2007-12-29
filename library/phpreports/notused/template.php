<?php
	require_once("PHPReportMaker.php");

	$sParms = 
	"<ROW>".
	"<COL ALIGN='RIGHT' CELLCLASS='HEADER'>from</COL>".
	"<COL TYPE='EXPRESSION' CELLCLASS='HEADER' TEXTCLASS='BOLD' COLSPAN='4'>\$this->getParameter('from')</COL>".
	"</ROW>".
	"<ROW>".
	"<COL ALIGN='RIGHT' CELLCLASS='HEADER'>till</COL>".
	"<COL TYPE='EXPRESSION' CELLCLASS='HEADER' TEXTCLASS='BOLD' COLSPAN='4'>\$this->getParameter('till')</COL>".
	"</ROW>";

	$sGroup = 
	"<GROUP EXPRESSION='city'>".
	"<HEADER>".
	"<ROW><COL CELLCLASS='HEADER' TEXTCLASS='BOLD' TYPE='EXPRESSION' COLSPAN='50'>\$this->getValue('city')</COL></ROW>".
	"</HEADER>".
	"<FIELDS>".
	"<ROW>".
	"<COL TYPE='FIELD' CELLCLASSEVEN='EVEN' CELLCLASSODD='ODD' SUPPRESS='TRUE'>name</COL>".
	"<COL TYPE='FIELD' CELLCLASSEVEN='EVEN' CELLCLASSODD='ODD'>type</COL>".
	"<COL TYPE='FIELD' CELLCLASSEVEN='EVEN' CELLCLASSODD='ODD'>item</COL>".
	"<COL TYPE='FIELD' CELLCLASSEVEN='EVEN' CELLCLASSODD='ODD' NUMBERFORMATEX='2' ALIGN='RIGHT'>value</COL>".
	"</ROW>".
	"</FIELDS>".
	"<FOOTER>".
	"<ROW>".
	"<COL CELLCLASS='FOOTER' ALIGN='RIGHT' COLSPAN='3'>total</COL>".
	"<COL TYPE='EXPRESSION' CELLCLASS='FOOTER' TEXTCLASS='BOLD' NUMBERFORMATEX='2'>\$this->getSum('value')</COL>".
	"</ROW>".
	"</FOOTER>".
	"</GROUP>";

	$sDoc =
	"<DOCUMENT>".
	"<FOOTER>".
	"<ROW>".
	"<COL CELLCLASS='FOOTER' TEXTCLASS='BOLD' ALIGN='RIGHT' COLSPAN='3'>GRAND TOTAL</COL>".
	"<COL TYPE='EXPRESSION' CELLCLASS='FOOTER' TEXTCLASS='BOLD' NUMBERFORMATEX='2'>\$this->getSum('value')</COL>".
	"</ROW>".
	"</FOOTER>".
	"</DOCUMENT>";

	$oRpt = new PHPReportMaker();
	$oRpt->setUser("taq");
	$oRpt->setPassword("******");
	$oRpt->setSQL("select * from sales order by city,name");
	$oRpt->setDatabaseInterface("mysql");
	$oRpt->setDatabase("phpreports");
	$oRpt->setParameters(array("from"=>"today","till"=>"tomorrow"));
	$oRpt->createFromTemplate("Template report","template.xml",$sParms,$sDoc,$sGroup);
	$oRpt->run();
?>
