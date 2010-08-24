<?xml version="1.0" encoding="ISO-8859-1"?>
<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform" version="1.0">
<xsl:output method="html" encoding="ISO-8859-1" indent="yes" doctype-public="-//W3C//DTD HTML 4.01//EN"/> 

<xsl:param name="body"/>

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
	<xsl:if test="$body='true'">
	<HTML>
		<HEAD>
			<xsl:if test="1=1">
			</xsl:if>
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
				P.breakhere { page-break-before:always;border:0px;margin:0px;background:transparent; }
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
	</xsl:if>
	<xsl:if test="$body='false'">
		<STYLE TYPE="text/css">
			P.breakhere { page-break-before:always;border:0px;margin:0px;background:transparent; }
		</STYLE>
		<xsl:apply-templates/>
	</xsl:if>	
</xsl:template>

<xsl:template match="CSS">
</xsl:template>

<xsl:template match="FORM">
	<FORM>
		<xsl:if test="string-length(@NAME)>0">
			<xsl:attribute name="NAME"><xsl:value-of select="@NAME"/></xsl:attribute>
		</xsl:if>
		<xsl:if test="string-length(@METHOD)>0">
			<xsl:attribute name="METHOD"><xsl:value-of select="@METHOD"/></xsl:attribute>
		</xsl:if>
		<xsl:if test="string-length(@ACTION)>0">
			<xsl:attribute name="ACTION"><xsl:value-of select="@ACTION"/></xsl:attribute>
		</xsl:if>
		<xsl:apply-templates/>
	</FORM>
</xsl:template>

</xsl:stylesheet>
