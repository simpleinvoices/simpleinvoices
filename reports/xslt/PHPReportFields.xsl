<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform" version="1.0">

<xsl:template match="FIELDS">
	// here starts the sql fields rows ...
	$oFieldRows = Array();
	<xsl:apply-templates/>
	<xsl:text>$oGrp_</xsl:text><xsl:value-of select="../@NAME"/>->setFieldRows($oFieldRows);
</xsl:template>

</xsl:stylesheet>
