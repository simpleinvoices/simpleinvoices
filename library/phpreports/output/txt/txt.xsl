<?xml version="1.0" encoding="ISO-8859-1"?>
<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform" version="1.0">
<xsl:output method="text" encoding="ISO-8859-1" indent="no"/>
<xsl:strip-space elements="*"/>

<xsl:template match="/RP">
	<xsl:apply-templates/>
</xsl:template>

<xsl:template match="PG">
	<xsl:apply-templates/>
	<xsl:text>&#10;</xsl:text>
	<xsl:text>__formfeed__</xsl:text>
</xsl:template>

<xsl:template match="R">
	<xsl:apply-templates/>
	<xsl:text>&#10;</xsl:text>
</xsl:template>

<xsl:template match="C">
	<xsl:apply-templates/>
	<xsl:text> </xsl:text>
</xsl:template>

<xsl:template match="LI">
	<xsl:value-of select="text()"/>
</xsl:template>

<xsl:template match="XHTML">
	<xsl:call-template name="HTML_ELEM"/>
</xsl:template>

<xsl:template match="BK">
</xsl:template>

<xsl:template match="IMG">
</xsl:template>

<xsl:template match="CSS">
</xsl:template>

<xsl:template match="node()[ancestor::XHTML]">
	<xsl:call-template name="HTML_ELEM"/>
</xsl:template>

<xsl:template match="text()[ancestor::XHTML]">
	<xsl:if test="string-length(.)>0 and not(node())">
<xsl:value-of select="normalize-space(.)"/>
	<xsl:text>&#10;</xsl:text>
	</xsl:if>	
</xsl:template>

<xsl:template name="HTML_ELEM">
	<xsl:apply-templates/>
</xsl:template>

</xsl:stylesheet>
