<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform" version="1.0">

<xsl:template match="HEADER">
	$oHeader = Array();
	<xsl:apply-templates/>
	<xsl:choose>
		<xsl:when test="name(..)='DOCUMENT'">
			$oDoc->setHeader($oHeader);
		</xsl:when>
		<xsl:when test="name(..)='PAGE'">
			$oPage->setHeader($oHeader);
		</xsl:when>
		<xsl:when test="name(..)='GROUP'">
			$oGrp_<xsl:value-of select="../@NAME"/>->setHeader($oHeader);
		</xsl:when>
	</xsl:choose>
</xsl:template>

</xsl:stylesheet>
