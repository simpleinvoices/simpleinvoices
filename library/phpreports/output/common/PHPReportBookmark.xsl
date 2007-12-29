<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform" version="1.0">

<xsl:template match="BK">
	<a>
		<xsl:attribute name="NAME">
			<xsl:value-of select="@HREF"/>
		</xsl:attribute>	
	</a>	
</xsl:template>

</xsl:stylesheet>
