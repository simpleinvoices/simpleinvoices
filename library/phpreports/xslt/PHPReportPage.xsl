<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform" version="1.0">

<xsl:template match="PAGE">
	<xsl:text>
	// inserting here the page element ...
	$oPage  = new PHPReportPage($sXMLOutputFile);</xsl:text>
	$oPage->setReport(&amp;$oReport);
	$oPage->setLimit($oReport->getMaxRowBuffer());

	// page attributes
	<xsl:if test="string-length(@CLASS)&gt;0">
		$oPage->setClass(<xsl:value-of select="@CLASS"/>);
	</xsl:if>
	<xsl:if test="string-length(@SIZE)&gt;0">
		$oPage->setSize(<xsl:value-of select="@SIZE"/>);
	</xsl:if>
	<xsl:if test="string-length(@WIDTH)&gt;0">
		$oPage->setWidth(<xsl:value-of select="@WIDTH"/>);
	</xsl:if>
	<xsl:if test="string-length(@HEIGHT)&gt;0">
		$oPage->setHeight(<xsl:value-of select="@HEIGHT"/>);
	</xsl:if>
	<xsl:if test="string-length(@CELLPADDING)&gt;0">
		$oPage->setCellPadding(<xsl:value-of select="@CELLPADDING"/>);
	</xsl:if>
	<xsl:if test="string-length(@CELLSPACING)&gt;0">
		$oPage->setCellSpacing(<xsl:value-of select="@CELLSPACING"/>);
	</xsl:if>
	<xsl:if test="string-length(@BORDER)&gt;0">
		$oPage->setBorder(<xsl:value-of select="@BORDER"/>);
	</xsl:if>
	<xsl:if test="string-length(@ALIGN)&gt;0">
		$oPage->setAlign(<xsl:value-of select="@ALIGN"/>);
	</xsl:if>
	<xsl:if test="count(/REPORT/DEBUG)&gt;0">
		$oPage->setDebug(&quot;<xsl:value-of select="/REPORT/DEBUG"/>&quot;);
	</xsl:if>
	
	$oGroup =&amp; $oPage;
	<xsl:apply-templates/>
	<!--$oPage->setFields($oFields);
	<xsl:if test="string-length(/REPORT/TEMP)>0">
	$oPage->setTemp(&quot;<xsl:value-of select="/REPORT/TEMP"/>&quot;);
	</xsl:if>
	-->
	<xsl:text>$oReport->setPage($oPage);&#10;</xsl:text>
</xsl:template>

</xsl:stylesheet>
