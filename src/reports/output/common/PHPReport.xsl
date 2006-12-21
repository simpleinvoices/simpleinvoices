<?xml version="1.0" encoding="ISO-8859-1"?>
<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform" version="1.0">
<xsl:output method="html" encoding="ISO-8859-1" indent="yes" doctype-public="-//W3C//DTD HTML 4.01//EN"/> 

<xsl:include href="PHPReportPage.xsl"/>
<xsl:include href="PHPReportRow.xsl"/>
<xsl:include href="PHPReportCol.xsl"/>
<xsl:include href="PHPReportXHTML.xsl"/>
<xsl:include href="PHPReportBookmark.xsl"/>
<xsl:include href="PHPReportImg.xsl"/>
<xsl:include href="PHPReportCSS.xsl"/>

<!-- template for all text elements -->
<xsl:template match="text()">
	<xsl:value-of select="normalize-space()"/>
</xsl:template>

<xsl:template match="RP">
	<HTML>
		<HEAD>
			<TITLE><xsl:value-of select="@TITLE"/></TITLE>
			<xsl:if test="string-length(@CSS)>0">
				<LINK REL="stylesheet" TYPE="text/css">
					<xsl:attribute name="HREF">
						<xsl:value-of select="@CSS"/>
					</xsl:attribute>
				</LINK>	
			</xsl:if>
			<xsl:call-template name="CSS_MEDIA"/>
			<STYLE TYPE="text/css">
				P.breakhere { page-break-before:always; border:0px; margin:0px; background:#FFFF00; }
			</STYLE>
		</HEAD>
		<BODY>
			<xsl:if test="string-length(@BGCOLOR)>0">
				<xsl:attribute name="BGCOLOR">
					<xsl:value-of select="@BGCOLOR"/>
				</xsl:attribute>	
			</xsl:if>
			<xsl:if test="string-length(@BACKGROUND)>0">
				<xsl:attribute name="BACKGROUND">
					<xsl:value-of select="@BACKGROUND"/>
				</xsl:attribute>	
			</xsl:if>
			<xsl:apply-templates/>
		</BODY>
	</HTML>
</xsl:template>

<xsl:template match="CSS">
</xsl:template>

</xsl:stylesheet>
