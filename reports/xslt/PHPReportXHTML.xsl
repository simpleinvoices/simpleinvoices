<?xml version="1.0" encoding="ISO-8859-1"?>
<!--
	Use this XSLT file to transform any XHTML (not HTML!) elements
	from the XML file into PHP code used in PHPReports
-->
<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform" version="1.0">

<xsl:template match="XHTML">
	// inserting here the XHTML element ...
	<xsl:text>$oCol</xsl:text><xsl:value-of select="count(preceding::*[name()='COL'])+1"/><xsl:text>->setExpr(</xsl:text>
	<xsl:text>&quot;</xsl:text>
	<xsl:call-template name="HTML_ELEM"/>
	<xsl:text>&quot;);&#10;</xsl:text>
</xsl:template>

<!-- all nodes of the XHTML element -->
<xsl:template match="node()[ancestor::XHTML]">
	<xsl:call-template name="HTML_ELEM"/>
</xsl:template>

<!-- all text of the XHTML element -->
<xsl:template match="text()[ancestor::XHTML]">
	<xsl:if test="string-length(.)>0 and not(node())">
		<xsl:value-of select="."/>
	</xsl:if>	
</xsl:template>

<!-- XHTML element -->
<xsl:template name="HTML_ELEM">
	<xsl:text disable-output-escaping="yes">&lt;</xsl:text>
	<xsl:value-of select="name()"/>

	<xsl:call-template name="HTML_PARMS"/>
	<xsl:text disable-output-escaping="yes">&gt;</xsl:text>
	<xsl:apply-templates/>
	
	<xsl:text disable-output-escaping="yes">&lt;/</xsl:text>
	<xsl:value-of select="name()"/>
	<xsl:text disable-output-escaping="yes">&gt;</xsl:text>
</xsl:template>

<!-- XHTML element parameters -->
<xsl:template name="HTML_PARMS">
	<xsl:for-each select="@*">
		<xsl:text> </xsl:text>
		<xsl:value-of select="name()"/>
		<xsl:text disable-output-escaping="yes">=\&quot;</xsl:text>
		<xsl:value-of select="."/>
		<xsl:text disable-output-escaping="yes">\&quot;</xsl:text>
	</xsl:for-each>	
</xsl:template>

</xsl:stylesheet>
