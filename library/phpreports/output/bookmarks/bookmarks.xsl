<?xml version="1.0" encoding="ISO-8859-1"?>
<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform" version="1.0">
<xsl:output method="html" encoding="ISO-8859-1" indent="no"/>
<xsl:param name="css"/>

<xsl:template match="/RP">
	<html>
		<head>
			<xsl:if test="string-length(@CSS)>0 or string-length($css)>0">
				<LINK REL="stylesheet" TYPE="text/css">
					<xsl:attribute name="HREF">
						<xsl:choose>
							<xsl:when test="string-length($css)>0">
								<xsl:value-of select="$css"/>
							</xsl:when>	
							<xsl:otherwise>
								<xsl:value-of select="@CSS"/>
							</xsl:otherwise>
						</xsl:choose>	
					</xsl:attribute>
				</LINK>	
			</xsl:if>
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
			<script language="JavaScript">
				function goToBookmark(iBookmark) {
					parent.frames[1].location.hash="#"+iBookmark;
				}	
			</script>
		</head>
		<body>
		<table>
			<xsl:apply-templates select="/RP/PG/R/C/BK"/>
		</table>	
		</body>
	</html>
</xsl:template>

<xsl:template match="BK">
	<tr>
		<td>
			<xsl:if test="string-length(@CC)>0">
				<xsl:attribute name="CLASS">
					<xsl:value-of select="@CC"/>
				</xsl:attribute>	
			</xsl:if>	
			<a target="report">
				<xsl:attribute name="HREF">
					<xsl:text>javascript://</xsl:text>
				</xsl:attribute>	
				<xsl:if test="string-length(@TC)>0">
					<xsl:attribute name="CLASS">
						<xsl:value-of select="@TC"/>
					</xsl:attribute>
				</xsl:if>
				<xsl:attribute name="onClick">goToBookmark(<xsl:value-of select="@HREF"/>);</xsl:attribute>
				<xsl:value-of select="text()"/>
			</a>
		</td>
	</tr>
</xsl:template>

</xsl:stylesheet>
