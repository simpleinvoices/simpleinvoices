<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform" version="1.0">

<xsl:template match="ROW">
	<xsl:text>
	// adding a new row ...
	$oRow = new PHPReportRow();
	</xsl:text>
	<xsl:apply-templates/>
	<xsl:choose>
		<xsl:when test="name(..)='HEADER'">
			<xsl:text>array_push($oHeader,$oRow);&#10;</xsl:text>
		</xsl:when>
		<xsl:when test="name(..)='FOOTER'">
			<xsl:text>array_push($oFooter,$oRow);&#10;</xsl:text>
		</xsl:when>
		<xsl:when test="name(..)='FIELDS'">
			<xsl:text>array_push($oFieldRows,$oRow);&#10;</xsl:text>
		</xsl:when>
		<xsl:otherwise>
			// unknow element: <xsl:value-of select="name(..)"/>
		</xsl:otherwise>
	</xsl:choose>
</xsl:template>

</xsl:stylesheet>
