<?xml version="1.0" encoding="ISO-8859-1"?>
<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform" version="1.0">
<xsl:output method="text" encoding="iso-8859-1" indent="no"/>

<xsl:param name="output_format"/>
<xsl:param name="classname"/>

<xsl:include href="PHPReportRpt.xsl"/>
<xsl:include href="PHPReportForm.xsl"/>
<xsl:include href="PHPReportPage.xsl"/>
<xsl:include href="PHPReportUtil.xsl"/>
<xsl:include href="PHPReportRow.xsl"/>
<xsl:include href="PHPReportFields.xsl"/>
<xsl:include href="PHPReportHeader.xsl"/>
<xsl:include href="PHPReportFooter.xsl"/>
<xsl:include href="PHPReportGroup.xsl"/>
<xsl:include href="PHPReportLink.xsl"/>
<xsl:include href="PHPReportBookmark.xsl"/>
<xsl:include href="PHPReportImg.xsl"/>
<xsl:include href="PHPReportCol.xsl"/>
<xsl:include href="PHPReportXHTML.xsl"/>

<xsl:template match="/">
<xsl:if test="$output_format='file'">
&lt;?php
</xsl:if>
class <xsl:value-of select="$classname"/> {

// A pre-existing database connection to use instead of connecting
// to the database ourselves
var $_oCon = NULL;
function setDatabaseConnection(&amp;$_oCon){
	$this->_oCon =&amp; $_oCon;
}

function run($sXMLOutputFile=null,$aEnv_=null) {
	$sPath		= getPHPReportsFilePath();

	if(is_null($sPath))
		exit("I can't find the paths needed to run. Please refer to the PDF manual to see how to set it.");

	include_once $sPath."/php/PHPReportEvent.php"; 
	include_once $sPath."/php/PHPReportRpt.php"; 
	include_once $sPath."/php/PHPReportXMLElement.php"; 
	include_once $sPath."/php/PHPReportForm.php"; 
	include_once $sPath."/php/PHPReportRow.php";
	include_once $sPath."/php/PHPReportColParm.php"; 
	include_once $sPath."/php/PHPReportLink.php"; 
	include_once $sPath."/php/PHPReportBookmark.php"; 
	include_once $sPath."/php/PHPReportImg.php"; 
	include_once $sPath."/php/PHPReportCol.php"; 
	include_once $sPath."/php/PHPReportField.php"; 
	include_once $sPath."/php/PHPReportGroup.php"; 
	include_once $sPath."/php/PHPReportPage.php"; 

	<xsl:apply-templates/>
	return $oPage->getFileName();
}

}
<xsl:if test="$output_format='file'">
?&gt;
</xsl:if>
</xsl:template>

<!-- template for all text elements -->
<xsl:template match="text()">
	<xsl:value-of select="normalize-space()"/>
</xsl:template>

</xsl:stylesheet>
