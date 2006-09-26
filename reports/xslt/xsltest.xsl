<?xml version="1.0" encoding="ISO-8859-1"?>
<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform" version="1.0">
<xsl:output method="html" encoding="ISO-8859-1" indent="yes"/>
<xsl:param name="html"/>

<xsl:template match="/test/message">
	<xsl:value-of select="title"/>
	&#160;
	<xsl:value-of select="body"/>
</xsl:template>

</xsl:stylesheet>
