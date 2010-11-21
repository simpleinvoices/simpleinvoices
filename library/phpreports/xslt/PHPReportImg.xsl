<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform" version="1.0">

<xsl:template match="IMG">
	// image here
	$oImg = new PHPReportImg();
	$oImg->setURL(&quot;<xsl:value-of select="."/>&quot;);
	$oImg->setWidth(<xsl:value-of select="@WIDTH"/>);
	$oImg->setHeight(<xsl:value-of select="@HEIGHT"/>);
	$oImg->setBorder(<xsl:value-of select="@BORDER"/>);
	$oImg->setAlt(&quot;<xsl:value-of select="@ALT"/>&quot;);
	$oCol<xsl:value-of select="count(preceding::*[name()='COL'])+1"/>->addImg($oImg);&#10;
</xsl:template>

</xsl:stylesheet>
