<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform" version="1.0">

<xsl:template name="CSS_MEDIA">
	<xsl:for-each select="CSS">
		<LINK REL="stylesheet" TYPE="text/css">
			<xsl:attribute name="HREF">
				<xsl:value-of select="."/>
			</xsl:attribute>
			<xsl:if test="string-length(@MEDIA)&gt;0">
				<xsl:attribute name="MEDIA">
					<xsl:value-of select="@MEDIA"/>
				</xsl:attribute>
			</xsl:if>	
		</LINK>
	</xsl:for-each>
</xsl:template>

</xsl:stylesheet>
