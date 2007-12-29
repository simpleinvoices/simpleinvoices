<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform" version="1.0">

<xsl:template match="FIELDS">
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

	// here starts the sql fields rows ...
	$oFieldRows = Array();
	<xsl:apply-templates/>
	<xsl:text>$oGrp_</xsl:text><xsl:value-of select="$group_name"/>->setFieldRows($oFieldRows);
</xsl:template>

</xsl:stylesheet>
