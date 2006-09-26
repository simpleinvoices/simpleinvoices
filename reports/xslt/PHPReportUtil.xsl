<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform" version="1.0">

<xsl:template name="replace-substring">
	<xsl:param name="text"/>
	<xsl:param name="from"/>
	<xsl:param name="to"/>
	<xsl:choose>
		<xsl:when test="contains($text,$from)">
			<xsl:value-of select="substring-before($text,$from)"/>
			<xsl:copy-of select="$to"/>
			<xsl:call-template name="replace-substring">
				<xsl:with-param name="text" select="substring-after($text,$from)"/>
				<xsl:with-param name="from" select="$from"/>
				<xsl:with-param name="to"   select="$to"/>
			</xsl:call-template>
		</xsl:when>
		<xsl:otherwise>
			<xsl:copy-of select="$text"/>
		</xsl:otherwise>
	</xsl:choose>
</xsl:template>

</xsl:stylesheet>
