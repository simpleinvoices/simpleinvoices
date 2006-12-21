<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform" version="1.0">

<xsl:template match="IMG">
	<img>
		<xsl:attribute name="SRC">
			<xsl:value-of select="."/>
		</xsl:attribute>	
		<xsl:if test="count(@WIDTH)>0">
			<xsl:attribute name="WIDTH">
				<xsl:value-of select="@WIDTH"/>
			</xsl:attribute>
		</xsl:if>
		<xsl:if test="count(@HEIGHT)>0">
			<xsl:attribute name="HEIGHT">
				<xsl:value-of select="@HEIGHT"/>
			</xsl:attribute>
		</xsl:if>
		<xsl:if test="count(@BORDER)>0">
			<xsl:attribute name="BORDER">
				<xsl:value-of select="@BORDER"/>
			</xsl:attribute>
		</xsl:if>
		<xsl:if test="count(@ALT)>0">
			<xsl:attribute name="ALT">
				<xsl:value-of select="@ALT"/>
			</xsl:attribute>
		</xsl:if>
	</img>	
</xsl:template>

</xsl:stylesheet>
