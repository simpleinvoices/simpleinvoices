<?xml version="1.0" encoding="ISO-8859-1"?>
<!-- 
	This file needs rewriting!
	Don't mind about some stupid things I put here, I'll fix it soon. :-)
//-->
<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform" version="1.0">
	<xsl:output method="html" encoding="iso-8859-1" indent="no"/>

	<xsl:apply-templates select="REPORT"/>

	<xsl:template match="/REPORT">
	<html>
		<head>
			<title><xsl:value-of select="TITLE"/></title>
			<xsl:if test="string-length(CSS)>0">
				<link rel="stylesheet" type="text/css">
					<xsl:attribute name="href">
						<xsl:value-of select="CSS"/>
					</xsl:attribute>
				</link> 
			</xsl:if>
		</head>
		<body>
			<xsl:if test="string-length(@MARGINWIDTH)>0">
				<xsl:attribute name="MARGINWIDTH"><xsl:value-of select="@MARGINWIDTH"/></xsl:attribute>
			</xsl:if>
			<xsl:if test="string-length(@MARGINHEIGHT)>0">
				<xsl:attribute name="MARGINHEIGHT"><xsl:value-of select="@MARGINWIDTH"/></xsl:attribute>
			</xsl:if>
			<xsl:if test="string-length(BACKGROUND_COLOR)>0">
				<xsl:attribute name="BGCOLOR"><xsl:value-of select="BACKGROUND_COLOR"/></xsl:attribute>
			</xsl:if>
			<xsl:if test="string-length(BACKGROUND_IMAGE)>0">
				<xsl:attribute name="BACKGROUND"><xsl:value-of select="BACKGROUND_IMAGE"/></xsl:attribute>
			</xsl:if>

			<xsl:apply-templates select="/REPORT/DOCUMENT/HEADER"/>
			<xsl:apply-templates select="/REPORT/PAGE"/>
			<xsl:apply-templates select="/REPORT/DOCUMENT/FOOTER"/>
		</body>
	</html>
	</xsl:template>

	<!-- document header - separate table! -->
	<xsl:template match="/REPORT/DOCUMENT/HEADER">
		<table>
		<xsl:call-template name="MAKE_TABLE_CONFIG"/>
		<xsl:apply-templates select="ROW"/>
		</table>
	</xsl:template>

	<!-- document footer - separate table! -->
	<xsl:template match="/REPORT/DOCUMENT/FOOTER">
		<table>
		<xsl:call-template name="MAKE_TABLE_CONFIG"/>
		<xsl:apply-templates select="ROW"/>
		</table>
	</xsl:template>

	<!-- table configuration -->
	<xsl:template name="MAKE_TABLE_CONFIG">
		<xsl:call-template name="BORDER"/>
		<xsl:call-template name="WIDTH"/>
		<xsl:call-template name="HEIGHT"/>
		<xsl:call-template name="ALIGN"/>
		<xsl:call-template name="CELLPADDING"/>
		<xsl:call-template name="CELLSPACING"/>
	</xsl:template>

	<!-- row -->
	<xsl:template match="ROW">
		<tr>
			<xsl:apply-templates select="COL"/>
		</tr>
	</xsl:template>

	<!-- column -->
	<xsl:template match="COL">
		<xsl:if test="string-length(@VISIBLE)&lt;1 or @VISIBLE='TRUE'">
			<td>
				<xsl:call-template name="CELLCLASS"/>
				<xsl:call-template name="ROWSPAN"/>
				<xsl:call-template name="COLSPAN"/>
				<xsl:call-template name="WIDTH"/>
				<xsl:call-template name="HEIGHT"/>
				<xsl:call-template name="ALIGN"/>
				<xsl:call-template name="VALIGN"/>
				<xsl:apply-templates select="HR"/>
				<xsl:apply-templates select="IMG"/>
				<xsl:apply-templates select="LINK"/>
				<xsl:apply-templates select="BOOKMARK"/>
				<span>
					<xsl:call-template name="TEXTCLASS"/>
					<xsl:value-of select="text()"/>
				</span>	
			</td>
		</xsl:if>	
	</xsl:template> 

	<!-- page -->
	<xsl:template match="PAGE">
		<table>
			<xsl:call-template name="MAKE_TABLE_CONFIG"/>
			<xsl:apply-templates select="HEADER"/>
			<xsl:apply-templates select="/REPORT/GROUPS"/>
			<xsl:apply-templates select="FOOTER"/>
		</table>
	</xsl:template>

	<!-- fields -->
	<xsl:template match="FIELDS">
		<xsl:apply-templates select="ROW"/>
	</xsl:template>

	<!-- groups -->
	<xsl:template match="GROUPS">
		<xsl:apply-templates select="GROUP"/>
	</xsl:template>

	<!-- group -->
	<xsl:template match="GROUP">
		<xsl:apply-templates select="HEADER"/>
		<xsl:apply-templates select="FIELDS"/>
		<xsl:apply-templates select="GROUP"/>
		<xsl:apply-templates select="FOOTER"/>
	</xsl:template>

	<!-- header -->
	<xsl:template match="HEADER">
		<xsl:apply-templates select="ROW"/>
	</xsl:template>

	<!-- footer -->
	<xsl:template match="FOOTER">
		<xsl:apply-templates select="ROW"/>
	</xsl:template>

	<!-- cell class -->
	<xsl:template name="CELLCLASS">
		<xsl:if test="string-length(@CELLCLASS)>0">
			<xsl:attribute name="CLASS">
				<xsl:value-of select="@CELLCLASS"/>
			</xsl:attribute>
		</xsl:if>
	</xsl:template>

	<!-- text class -->
	<xsl:template name="TEXTCLASS">
		<xsl:if test="string-length(@TEXTCLASS)>0">
			<xsl:attribute name="CLASS">
				<xsl:value-of select="@TEXTCLASS"/>
			</xsl:attribute>
		</xsl:if>
	</xsl:template>
	
	<!-- row span -->
	<xsl:template name="ROWSPAN">
		<xsl:if test="string-length(@ROWSPAN)>0">
			<xsl:attribute name="ROWSPAN">
				<xsl:value-of select="@ROWSPAN"/>
			</xsl:attribute>
		</xsl:if>
	</xsl:template>		
	
	<!-- col span -->	
	<xsl:template name="COLSPAN">
		<xsl:if test="string-length(@COLSPAN)>0">
			<xsl:attribute name="COLSPAN">
				<xsl:value-of select="@COLSPAN"/>
			</xsl:attribute>
		</xsl:if>
	</xsl:template>		

	<!-- align -->
	<xsl:template name="ALIGN">
		<xsl:if test="string-length(@ALIGN)>0">
			<xsl:attribute name="ALIGN">
				<xsl:value-of select="@ALIGN"/>
			</xsl:attribute>
		</xsl:if>
	</xsl:template>
	
	<!-- vertical align -->	
	<xsl:template name="VALIGN">
		<xsl:if test="string-length(@VALIGN)>0">
			<xsl:attribute name="VALIGN">
				<xsl:value-of select="@VALIGN"/>
			</xsl:attribute>
		</xsl:if>
	</xsl:template>

	<!-- height -->
	<xsl:template name="HEIGHT">
		<xsl:if test="string-length(@HEIGHT)>0">
			<xsl:attribute name="height">
				<xsl:value-of select="@HEIGHT"/>
			</xsl:attribute>
		</xsl:if>
	</xsl:template>
	
	<!-- width -->	
	<xsl:template name="WIDTH">
		<xsl:if test="string-length(@WIDTH)>0">
			<xsl:attribute name="width">
				<xsl:value-of select="@WIDTH"/>
			</xsl:attribute>
		</xsl:if>
	</xsl:template>
	
	<!-- border -->	
	<xsl:template name="BORDER">
		<xsl:if test="string-length(@BORDER)>0">
			<xsl:attribute name="border">
				<xsl:value-of select="@BORDER"/>
			</xsl:attribute>
		</xsl:if>
	</xsl:template>	
	
	<!-- cell padding -->	
	<xsl:template name="CELLPADDING">
		<xsl:if test="string-length(@CELLPADDING)>0">
			<xsl:attribute name="cellpadding">
				<xsl:value-of select="@CELLPADDING"/>
			</xsl:attribute>
		</xsl:if>
	</xsl:template>
	
	<!-- cell spacing -->		
	<xsl:template name="CELLSPACING">
		<xsl:if test="string-length(@CELLSPACING)>0">
			<xsl:attribute name="cellspacing">
				<xsl:value-of select="@CELLSPACING"/>
			</xsl:attribute>
		</xsl:if>
	</xsl:template>

	<xsl:template match="IMG">
		<img>
			<xsl:attribute name="src">
				<xsl:value-of select="."/>
			</xsl:attribute>
			<xsl:if test="string-length(@WIDTH)>0">
				<xsl:attribute name="width">
					<xsl:value-of select="@WIDTH"/>
				</xsl:attribute>
			</xsl:if>
			<xsl:if test="string-length(@HEIGHT)>0">
				<xsl:attribute name="height">
					<xsl:value-of select="@HEIGHT"/>
				</xsl:attribute>
			</xsl:if>
			<xsl:if test="string-length(@BORDER)>0">
				<xsl:attribute name="border">
					<xsl:value-of select="@BORDER"/>
				</xsl:attribute>
			</xsl:if>
			<xsl:if test="string-length(@ALT)>0">
				<xsl:attribute name="alt">
					<xsl:value-of select="@ALT"/>
				</xsl:attribute>
			</xsl:if>
		</img>
	</xsl:template>

	<xsl:template match="LINK">
		[link]
	</xsl:template>
	
	<xsl:template match="BOOKMARK">
		[bookmark]
	</xsl:template>
</xsl:stylesheet>

