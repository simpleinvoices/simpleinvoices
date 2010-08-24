<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform" version="1.0">

<xsl:template match="PG">
	<TABLE>
	<xsl:attribute name="id">
		<xsl:text>pg</xsl:text><xsl:value-of select="count(preceding-sibling::*)+1"/>
	</xsl:attribute>
	<xsl:if test="string-length(@CL)>0">
		<xsl:attribute name="CLASS">
			<xsl:value-of select="@CL"/>
		</xsl:attribute>
	</xsl:if>	
	<xsl:if test="string-length(@AL)>0">
		<xsl:attribute name="ALIGN">
			<xsl:value-of select="@AL"/>
		</xsl:attribute>
	</xsl:if>	
	<xsl:if test="string-length(@BO)>0">
		<xsl:attribute name="BORDER">
			<xsl:value-of select="@BO"/>
		</xsl:attribute>
	</xsl:if>	
	<xsl:if test="string-length(@WI)>0">
		<xsl:attribute name="WIDTH">
			<xsl:value-of select="@WI"/>
		</xsl:attribute>
	</xsl:if>	
	<xsl:if test="string-length(@HE)>0">
		<xsl:attribute name="HEIGHT">
			<xsl:value-of select="@HE"/>
		</xsl:attribute>
	</xsl:if>	
	<xsl:if test="string-length(@PA)>0">
		<xsl:attribute name="CELLPADDING">
			<xsl:value-of select="@PA"/>
		</xsl:attribute>
	</xsl:if>	
	<xsl:if test="string-length(@SP)>0">
		<xsl:attribute name="CELLSPACING">
			<xsl:value-of select="@SP"/>
		</xsl:attribute>
	</xsl:if>	
	<xsl:apply-templates/>
	</TABLE>
	<xsl:if test="count(/RP/PG)!=count(preceding-sibling::*[name()=name(current())])+1">
		<BR CLEAR="ALL"/><BR/> 
		<P CLASS="breakhere">&#160;</P>	
	</xsl:if>	
</xsl:template>

</xsl:stylesheet>
