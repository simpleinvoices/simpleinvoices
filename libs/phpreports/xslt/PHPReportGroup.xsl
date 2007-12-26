<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform" version="1.0">

<xsl:template match="GROUPS">
	<xsl:apply-templates/>
</xsl:template>

<xsl:template match="GROUP">
	<xsl:variable name="group_name">
		<xsl:choose>
			<xsl:when test="string-length(@NAME)&gt;0">
				<xsl:value-of select="@NAME"/>
			</xsl:when>
			<xsl:otherwise>
				<xsl:text>gn_</xsl:text><xsl:value-of select="count(ancestor-or-self::*[name()='GROUP'])"/>
			</xsl:otherwise>
		</xsl:choose>
	</xsl:variable>

	<xsl:variable name="prev_group_name">
		<xsl:choose>
			<xsl:when test="name(..)='GROUP' and string-length(../@NAME)&gt;0">
				<xsl:value-of select="../@NAME"/>
			</xsl:when>
			<xsl:when test="name(..)='GROUP' and string-length(../@NAME)&lt;=0">
				<xsl:text>gn_</xsl:text><xsl:value-of select="count(../ancestor-or-self::*[name()='GROUP'])"/>
			</xsl:when>
		</xsl:choose>
	</xsl:variable>
	
	// creating a new group here ...
	$oGrp_<xsl:value-of select="$group_name"/>=new PHPReportGroup(&quot;<xsl:value-of select="$group_name"/>&quot;);
	$oGrp_<xsl:value-of select="$group_name"/>-&gt;setReport($oReport);
	$oGrp_<xsl:value-of select="$group_name"/>-&gt;setFields($oFields);
	$oGrp_<xsl:value-of select="$group_name"/>-&gt;setPageBreak(&quot;<xsl:value-of select="@PAGEBREAK"/>&quot;);
	$oGrp_<xsl:value-of select="$group_name"/>-&gt;setBreakExpr(&quot;<xsl:value-of select="@EXPRESSION"/>&quot;);
	$oGrp_<xsl:value-of select="$group_name"/>-&gt;setReprintHeader(&quot;<xsl:value-of select="@REPRINT_HEADER_ON_PAGEBREAK"/>&quot;);
	$oGrp_<xsl:value-of select="$group_name"/>-&gt;setResetSuppress(&quot;<xsl:value-of select="@RESET_SUPPRESS_ON_PAGEBREAK"/>&quot;);
	
	<xsl:if test="count(/REPORT/DEBUG)&gt;0">
		$oGrp_<xsl:value-of select="$group_name"/>-&gt;setDebug(&quot;<xsl:value-of select="/REPORT/DEBUG"/>&quot;);
	</xsl:if>	
	
	$oGroup =&amp; $oGrp_<xsl:value-of select="$group_name"/>;
	
	<xsl:if test="count(preceding-sibling::*)+1=1">
		$oGrpMain_ =&amp; $oGrp_<xsl:value-of select="$group_name"/>;
	</xsl:if>
	
	<xsl:apply-templates/>
	<xsl:if test="name(..)='GROUP'">
		$oGrp_<xsl:value-of select="$prev_group_name"/>-&gt;addChild(&amp;$oGrp_<xsl:value-of select="$group_name"/>);
	</xsl:if>
</xsl:template>

</xsl:stylesheet>
