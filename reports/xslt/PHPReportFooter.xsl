<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform" version="1.0">

<xsl:template match="FOOTER">
	$oFooter = Array();
	<xsl:apply-templates/>
	<xsl:choose>
		<xsl:when test="name(..)='DOCUMENT'">
			$oDoc->setFooter($oFooter);
		</xsl:when>
		<xsl:when test="name(..)='PAGE'">
			$oPage->setFooter($oFooter);
		</xsl:when>
		<xsl:when test="name(..)='GROUP'">
			$oGrp_<xsl:value-of select="../@NAME"/>->setFooter($oFooter);
		</xsl:when>
	</xsl:choose>
</xsl:template>

</xsl:stylesheet>
