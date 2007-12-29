<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform" version="1.0">

<xsl:template match="HEADER">
	<xsl:variable name="group_name">
		<xsl:choose>
			<xsl:when test="string-length(../@NAME)&gt;0">
				<xsl:value-of select="../@NAME"/>
			</xsl:when>
			<xsl:otherwise>
				<xsl:text>gn_</xsl:text><xsl:value-of select="count(../ancestor-or-self::*[name()='GROUP'])"/>
			</xsl:otherwise>
		</xsl:choose>
	</xsl:variable>

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
			$oGrp_<xsl:value-of select="$group_name"/>->setHeader($oHeader);
		</xsl:when>
	</xsl:choose>
</xsl:template>

</xsl:stylesheet>
