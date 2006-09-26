<?xml version="1.0" encoding="ISO-8859-1"?>
<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform" version="1.0">
<xsl:output method="html" encoding="ISO-8859-1" indent="no"/>

<xsl:include href="../common/PHPReportPage.xsl"/>
<xsl:include href="../common/PHPReportRow.xsl"/>
<xsl:include href="../common/PHPReportCol.xsl"/>
<xsl:include href="../common/PHPReportXHTML.xsl"/>
<xsl:include href="../common/PHPReportBookmark.xsl"/>
<xsl:include href="../common/PHPReportImg.xsl"/>
<xsl:include href="../common/PHPReportCSS.xsl"/>

<xsl:param name="curpage"/>
<xsl:param name="incr"/>
<xsl:param name="first"/>
<xsl:param name="last"/>
<xsl:param name="next"/>
<xsl:param name="prev"/>
<xsl:param name="xmlfile"/>
<xsl:param name="l1"/>
<xsl:param name="l2"/>
<xsl:param name="url"/>

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
			<xsl:apply-templates select="/RP/PG[number(@PN)=$curpage]"/>
			<br clear="all"/>
			<xsl:call-template name="PAGELINKS"/>
		</BODY>
	</HTML>
</xsl:template>

<xsl:template name="PAGELINKS">
	<table border="0" cellspacing="0" cellpadding="3" align="left" style="margin-left:25px;">
		<tr>
		<xsl:call-template name="MAKEPAGELINKS"/>
		</tr>
	</table>
</xsl:template>

<xsl:template name="MAKEPAGELINKS">
	<!-- first -->
	<xsl:if test="number($curpage)&gt;number($incr)">
		<td class="PHPREPORTS_PAGE_CELL">
			<a class="PHPREPORTS_PAGE_LINK">
				<xsl:attribute name="HREF"><xsl:value-of select="$url"/>?curpage=1<xsl:call-template name="PARM"/></xsl:attribute>	
				<xsl:value-of disable-output-escaping="yes" select="$first"/> 
			</a>
		</td>
	</xsl:if>	
	
	<!-- back -->
	<xsl:if test="$curpage>$incr">
		<td class="PHPREPORTS_PAGE_CELL">
		<a class="PHPREPORTS_PAGE_LINK">
			<xsl:attribute name="HREF"><xsl:value-of select="$url"/>?curpage=<xsl:value-of select="number($l1)-1"/><xsl:call-template name="PARM"/></xsl:attribute>	
			<xsl:value-of disable-output-escaping="yes" select="$prev"/> 
		</a>
		</td>
	</xsl:if>
	
	<!-- show the other pages here -->
	<xsl:call-template name="PAGES"/>
	
	<!-- next --> 
	<xsl:if test="number($l2)+1&lt;count(/RP/PG)">
		<td class="PHPREPORTS_PAGE_CELL">
		<a class="PHPREPORTS_PAGE_LINK">
			<xsl:attribute name="HREF"><xsl:value-of select="$url"/>?curpage=<xsl:value-of select="number($l2)+1"/><xsl:call-template name="PARM"/></xsl:attribute>	
			<xsl:value-of disable-output-escaping="yes" select="$next"/> 
		</a>
		</td>
	</xsl:if>
	
	<!-- last -->
	<xsl:if test="number($l1)+number($incr)&lt;=count(/RP/PG)">
		<td class="PHPREPORTS_PAGE_CELL">
		<a class="PHPREPORTS_PAGE_LINK">
			<xsl:attribute name="HREF"><xsl:value-of select="$url"/>?curpage=<xsl:value-of select="count(/RP/PG)"/><xsl:call-template name="PARM"/></xsl:attribute>	
			<xsl:value-of disable-output-escaping="yes" select="$last"/> 
		</a>
		</td>
	</xsl:if>	
</xsl:template>

<xsl:template name="PAGES">
	<!-- <xsl:for-each select="/RP/PG[number(@PN)>=number($curpage) and number(@PN)&lt;(number($curpage)+number($incr)+1)]"> -->
	<xsl:for-each select="/RP/PG[number(@PN)>=number($l1) and number(@PN)&lt;=number($l2)]">
		<td class="PHPREPORTS_PAGE_CELL">
		<a>
			<xsl:attribute name="CLASS">
				<xsl:choose>
					<xsl:when test="number(@PN)=$curpage">
						PHPREPORTS_PAGE_LINK_BOLD
					</xsl:when>	
					<xsl:otherwise>
						PHPREPORTS_PAGE_LINK
					</xsl:otherwise>
				</xsl:choose>
			</xsl:attribute>	
			<xsl:attribute name="HREF"><xsl:value-of select="$url"/>?curpage=<xsl:value-of select="@PN"/><xsl:call-template name="PARM"/></xsl:attribute>	
			<xsl:value-of select="@PN"/>
		</a>
		</td>	
	</xsl:for-each>
</xsl:template>

<xsl:template name="PARM">
	<xsl:text disable-output-escaping="yes">&amp;xmlfile=</xsl:text><xsl:value-of select="$xmlfile"/>
	<xsl:text disable-output-escaping="yes">&amp;incr=</xsl:text><xsl:value-of select="$incr"/>
	<xsl:text disable-output-escaping="yes">&amp;first=</xsl:text><xsl:value-of select="$first"/>
	<xsl:text disable-output-escaping="yes">&amp;last=</xsl:text><xsl:value-of select="$last"/>
	<xsl:text disable-output-escaping="yes">&amp;next=</xsl:text><xsl:value-of select="$next"/>
	<xsl:text disable-output-escaping="yes">&amp;prev=</xsl:text><xsl:value-of select="$prev"/>
	<xsl:text disable-output-escaping="yes">&amp;l1=</xsl:text><xsl:value-of select="$l1"/>
	<xsl:text disable-output-escaping="yes">&amp;l2=</xsl:text><xsl:value-of select="$l2"/>
</xsl:template>

<xsl:template match="CSS">
</xsl:template>

</xsl:stylesheet>
