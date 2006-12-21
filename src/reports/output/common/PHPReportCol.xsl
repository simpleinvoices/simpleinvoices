<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform" version="1.0">

<xsl:template match="C">
	<TD>
	<!-- WIDTH -->
	<xsl:if test="string-length(@WI)>0">
		<xsl:attribute name="WIDTH">
			<xsl:value-of select="@WI"/>
		</xsl:attribute>
	</xsl:if>
	
	<!-- HEIGHT -->
	<xsl:if test="string-length(@HE)>0">
		<xsl:attribute name="HEIGHT">
			<xsl:value-of select="@HE"/>
		</xsl:attribute>
	</xsl:if>
	
	<!-- ALIGN -->
	<xsl:if test="string-length(@AL)>0">
		<xsl:attribute name="ALIGN">
			<xsl:value-of select="@AL"/>
		</xsl:attribute>
	</xsl:if>
	
	<!-- VALIGN -->
	<xsl:if test="string-length(@VA)>0">
		<xsl:attribute name="VALIGN">
			<xsl:value-of select="@VA"/>
		</xsl:attribute>
	</xsl:if>
			
	<!-- COLSPAN -->
	<xsl:if test="string-length(@CS)>0">
		<xsl:attribute name="COLSPAN">
			<xsl:value-of select="@CS"/>
		</xsl:attribute>
	</xsl:if>

	<!-- ROWSPAN -->
	<xsl:if test="string-length(@RS)>0">
		<xsl:attribute name="ROWSPAN">
			<xsl:value-of select="@RS"/>
		</xsl:attribute>
	</xsl:if>

	<!-- CELLCLASS -->
	<xsl:if test="string-length(@CC)>0">
		<xsl:attribute name="CLASS">
			<xsl:value-of select="@CC"/>
		</xsl:attribute>
	</xsl:if>
	
	<!-- TEXTCLASS - open it here -->
	<xsl:if test="string-length(@TC)>0">
		<xsl:text disable-output-escaping="yes">&lt;SPAN CLASS=&quot;</xsl:text>
		<xsl:value-of select="@TC"/>
		<xsl:text disable-output-escaping="yes">&quot;&gt;</xsl:text>
	</xsl:if>
	
	<xsl:call-template name="LINK_START"/>	
	<xsl:apply-templates/>
	<xsl:call-template name="LINK_END"/>	
	
	<!-- TEXTCLASS - close it here -->
	<xsl:if test="string-length(@TC)>0">
		<xsl:text disable-output-escaping="yes">&lt;/SPAN&gt;</xsl:text>
	</xsl:if>
	
	</TD>
</xsl:template>

<!-- avoid the LINK here -->
<xsl:template match="LI">
</xsl:template>

<xsl:template name="LINK_START">
	<xsl:if test="count(LI)&gt;0">
		<xsl:text disable-output-escaping="yes">&lt;a HREF=&quot;</xsl:text>
		<xsl:value-of select="LI/@HREF"/>
		<xsl:text disable-output-escaping="yes">&quot;</xsl:text>

		<xsl:if test="string-length(LI/@TITLE)&gt;0">
			<xsl:text disable-output-escaping="yes"> TITLE=&quot;</xsl:text><xsl:value-of select="LI/@TITLE"/><xsl:text disable-output-escaping="yes">&quot;</xsl:text>
		</xsl:if>
		
		<xsl:if test="string-length(LI/@TARGET)&gt;0">
			<xsl:text disable-output-escaping="yes"> TARGET=&quot;</xsl:text><xsl:value-of select="LI/@TARGET"/><xsl:text disable-output-escaping="yes">&quot;</xsl:text>
		</xsl:if>
		
		<xsl:text disable-output-escaping="yes">&gt;</xsl:text>
		<xsl:value-of select="LI"/>
	</xsl:if>	
</xsl:template>

<xsl:template name="LINK_END">
	<xsl:if test="count(LI)&gt;0">
		<xsl:text disable-output-escaping="yes">&lt;/a&gt;</xsl:text>
	</xsl:if>	
</xsl:template>

</xsl:stylesheet>

