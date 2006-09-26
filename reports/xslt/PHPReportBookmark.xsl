<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform" version="1.0">

<xsl:template match="BOOKMARK">
	// bookmark here
	$oBm = new PHPReportBookmark();
	$oBm->setType (&quot;<xsl:value-of select="@TYPE"  />&quot;);
	$oBm->setCellClass(&quot;<xsl:value-of select="@CELLCLASS" />&quot;);
	$oBm->setTextClass(&quot;<xsl:value-of select="@TEXTCLASS" />&quot;);
	<xsl:text>$oBm->setExpr(</xsl:text>
	<xsl:choose>
		<xsl:when test="@TYPE='EXPRESSION'">
			<xsl:text>&quot;return </xsl:text>
			<xsl:call-template name="replace-substring">
				<xsl:with-param name="text">
					<xsl:call-template name="replace-substring">
						<xsl:with-param name="text" select="text()"/>
						<xsl:with-param name="from">$</xsl:with-param>
						<xsl:with-param name="to">\$</xsl:with-param>
					</xsl:call-template>
				</xsl:with-param>
				<xsl:with-param name="from">"</xsl:with-param>
				<xsl:with-param name="to">\"</xsl:with-param>
			</xsl:call-template>
			<xsl:text>;&quot;</xsl:text>
		</xsl:when>
		<xsl:otherwise>
			<xsl:text>&quot;</xsl:text>
			<xsl:value-of select="text()"/>
			<xsl:text>&quot;</xsl:text>
		</xsl:otherwise>
	</xsl:choose>		
	<xsl:text>);&#10;</xsl:text>

	$oCol<xsl:value-of select="count(preceding::*[name()='COL'])+1"/>->addBookmark($oBm);&#10;
</xsl:template>

</xsl:stylesheet>
