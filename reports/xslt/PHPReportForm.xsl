<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform" version="1.0">

<xsl:template match="FORM">
	// create the report form element here ...
	<xsl:text>$oForm=new PHPReportForm();&#10;</xsl:text>
	<xsl:apply-templates/>
	<xsl:text>&#9;$oReport->setForm($oForm);&#10;</xsl:text>
</xsl:template>

<xsl:template match="FORM_NAME">
	<xsl:text>&#9;$oForm->setName(&quot;</xsl:text>
	<xsl:value-of select="."/>
	<xsl:text>&quot;);&#10;</xsl:text>
</xsl:template>

<xsl:template match="FORM_METHOD">
	<xsl:text>&#9;$oForm->setMethod(&quot;</xsl:text>
	<xsl:value-of select="."/>
	<xsl:text>&quot;);&#10;</xsl:text>
</xsl:template>

<xsl:template match="FORM_ACTION">
	<xsl:text>&#9;$oForm->setAction(&quot;</xsl:text>
	<xsl:value-of select="."/>
	<xsl:text>&quot;);&#10;</xsl:text>
</xsl:template>

</xsl:stylesheet>
