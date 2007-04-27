<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform" version="1.0">

<xsl:template match="GROUPS">
	<xsl:apply-templates/>
</xsl:template>

<xsl:template match="GROUP">
	// creating a new group here ...
	$oGrp_<xsl:value-of select="@NAME"/>=new PHPReportGroup(&quot;<xsl:value-of select="@NAME"/>&quot;);
	$oGrp_<xsl:value-of select="@NAME"/>-&gt;setReport($oReport);
	$oGrp_<xsl:value-of select="@NAME"/>-&gt;setFields($oFields);
	$oGrp_<xsl:value-of select="@NAME"/>-&gt;setPageBreak(&quot;<xsl:value-of select="@PAGEBREAK"/>&quot;);
	$oGrp_<xsl:value-of select="@NAME"/>-&gt;setBreakExpr(&quot;<xsl:value-of select="@EXPRESSION"/>&quot;);
	$oGrp_<xsl:value-of select="@NAME"/>-&gt;setReprintHeader(&quot;<xsl:value-of select="@REPRINT_HEADER_ON_PAGEBREAK"/>&quot;);
	$oGrp_<xsl:value-of select="@NAME"/>-&gt;setResetSuppress(&quot;<xsl:value-of select="@RESET_SUPPRESS_ON_PAGEBREAK"/>&quot;);
	
	<xsl:if test="count(/REPORT/DEBUG)&gt;0">
		$oGrp_<xsl:value-of select="@NAME"/>-&gt;setDebug(&quot;<xsl:value-of select="/REPORT/DEBUG"/>&quot;);
	</xsl:if>	
	
	$oGroup =&amp; $oGrp_<xsl:value-of select="@NAME"/>;
	
	<xsl:if test="count(preceding-sibling::*)+1=1">
		$oGrpMain_ =&amp; $oGrp_<xsl:value-of select="@NAME"/>;
	</xsl:if>
	
	<xsl:apply-templates/>
	<xsl:if test="name(..)='GROUP'">
		$oGrp_<xsl:value-of select="../@NAME"/>-&gt;addChild(&amp;$oGrp_<xsl:value-of select="@NAME"/>);
	</xsl:if>
</xsl:template>

</xsl:stylesheet>
