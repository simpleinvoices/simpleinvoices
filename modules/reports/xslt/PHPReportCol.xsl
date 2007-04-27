<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform" version="1.0">

<xsl:template match="COL">
	<xsl:variable name="PHPCODE" select="'TYPE,NUMBERFORMAT,NUMBERFORMATEX,SUPPRESS,CELLCLASSEVEN,CELLCLASSODD,CELLCLASSEXPRESSION'"/>
	<!-- horrible compatible if code - remove it after people dont use more VISIBLE stuff! -->
	<xsl:if test="string-length(@VISIBLE)=0 or @VISIBLE!='FALSE'">
		// creating a new column here - column <xsl:value-of select="count(preceding::*[name()='COL'])+1"/> of <xsl:value-of select="count(//COL)"/><xsl:text>&#10;</xsl:text>
		
		<xsl:text>&#9;$oCol</xsl:text><xsl:value-of select="count(preceding::*[name()='COL'])+1"/><xsl:text>=new PHPReportCol();&#10;</xsl:text>
		<xsl:text>&#9;$oCol</xsl:text><xsl:value-of select="count(preceding::*[name()='COL'])+1"/><xsl:text>->setType(&quot;</xsl:text><xsl:value-of select="@TYPE"/><xsl:text>&quot;);&#10;</xsl:text>
		
		<!-- processing the parameters ... -->
		<xsl:for-each select="@*">
			<xsl:if test="contains($PHPCODE,name())&lt;1 or name()='CELLCLASS'">
				<xsl:text>&#9;$oCol</xsl:text><xsl:value-of select="count(preceding::*[name()='COL'])+1"/><xsl:text>->addParm(new PHPReportColParm(&quot;</xsl:text><xsl:value-of select="name()"/><xsl:text>&quot;,&quot;</xsl:text><xsl:value-of select="."/><xsl:text>&quot;));&#10;</xsl:text>
			</xsl:if>	
		</xsl:for-each> 

		<!-- suppress -->
		<xsl:if test="string-length(@SUPPRESS)>0">
			<xsl:text>&#9;$oCol</xsl:text><xsl:value-of select="count(preceding::*[name()='COL'])+1"/><xsl:text>->suppress(&quot;</xsl:text><xsl:value-of select="@SUPPRESS"/><xsl:text>&quot;);&#10;</xsl:text>
		</xsl:if>
		
		<!-- number format -->
		<xsl:if test="string-length(@NUMBERFORMAT)>0">
			<xsl:text>&#9;$oCol</xsl:text><xsl:value-of select="count(preceding::*[name()='COL'])+1"/><xsl:text>->setNumberFormat(&quot;</xsl:text><xsl:value-of select="@NUMBERFORMAT"/><xsl:text>&quot;);&#10;</xsl:text>
		</xsl:if>
		
		<!-- number format -->
		<xsl:if test="string-length(@NUMBERFORMATEX)>0">
			<xsl:text>&#9;$oCol</xsl:text><xsl:value-of select="count(preceding::*[name()='COL'])+1"/><xsl:text>->setNumberFormatEx(</xsl:text><xsl:value-of select="@NUMBERFORMATEX"/><xsl:text>);&#10;</xsl:text>
		</xsl:if>
		
		<!-- even class -->
		<xsl:if test="string-length(@CELLCLASSEVEN)>0">
			<xsl:text>&#9;$oCol</xsl:text><xsl:value-of select="count(preceding::*[name()='COL'])+1"/><xsl:text>->setEvenClass(&quot;</xsl:text><xsl:value-of select="@CELLCLASSEVEN"/><xsl:text>&quot;);&#10;</xsl:text>
		</xsl:if>
		
		<!-- odd class -->
		<xsl:if test="string-length(@CELLCLASSODD)>0">
			<xsl:text>&#9;$oCol</xsl:text><xsl:value-of select="count(preceding::*[name()='COL'])+1"/><xsl:text>->setOddClass(&quot;</xsl:text><xsl:value-of select="@CELLCLASSODD"/><xsl:text>&quot;);&#10;</xsl:text>
		</xsl:if>

		<!-- CELLCLASS EXPRESSION //-->
		<xsl:if test="string-length(@CELLCLASSEXPRESSION)>0">
			<xsl:text>&#9;$oCol</xsl:text><xsl:value-of select="count(preceding::*[name()='COL'])+1"/><xsl:text>->setCellClassExpr(</xsl:text>
			<xsl:text>&quot;return </xsl:text>
			<xsl:call-template name="replace-substring">
				<xsl:with-param name="text">
					<xsl:call-template name="replace-substring">
						<xsl:with-param name="text" select="@CELLCLASSEXPRESSION"/>
						<xsl:with-param name="from">$</xsl:with-param>
						<xsl:with-param name="to">\$</xsl:with-param>
					</xsl:call-template>
				</xsl:with-param>
				<xsl:with-param name="from">"</xsl:with-param>
				<xsl:with-param name="to">\"</xsl:with-param>
			</xsl:call-template>
			<xsl:text>;&quot;</xsl:text>
			<xsl:text>);&#10;</xsl:text>
		</xsl:if>
		
		<!-- column value here -->
		<xsl:text>&#9;$oCol</xsl:text><xsl:value-of select="count(preceding::*[name()='COL'])+1"/><xsl:text>->setExpr(</xsl:text>
		<xsl:choose>
			<xsl:when test="@TYPE='EXPRESSION' or @TYPE='RAW_EXPRESSION'">
				<!-- <xsl:text>eval(&quot;return </xsl:text> -->
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
				<xsl:if test="count(XHTML)&lt;1">
					<xsl:value-of select="text()"/>
				</xsl:if>	
				<xsl:text>&quot;</xsl:text>
			</xsl:otherwise>
		</xsl:choose>		
		<xsl:text>);&#10;</xsl:text>
		<xsl:text>&#9;$oCol</xsl:text><xsl:value-of select="count(preceding::*[name()='COL'])+1"/><xsl:text>->setGroup(&amp;$oGroup);&#10;</xsl:text>
		<xsl:apply-templates select="LINK|BOOKMARK|XHTML|IMG"/>
		<xsl:text>&#9;$oRow->addCol(&amp;$oCol</xsl:text><xsl:value-of select="count(preceding::*[name()='COL'])+1"/><xsl:text>);&#10;</xsl:text>
	</xsl:if>	
</xsl:template>

</xsl:stylesheet>
