<?xml version="1.0"?>

<xsl:stylesheet version="1.0"
  xmlns:xhtml="http://www.w3.org/1999/xhtml"
  xmlns="http://www.w3.org/1999/xhtml"
  xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
  xmlns:xs="http://www.w3.org/2001/XMLSchema"
  xmlns:fn="http://www.w3.org/2005/02/xpath-functions"
  xmlns:xdt="http://www.w3.org/2005/02/xpath-datatypes"
exclude-result-prefixes="xhtml xsl fn xs xdt">

<xsl:output
omit-xml-declaration="yes"
method="html"
doctype-public="-//W3C//DTD XHTML 1.0 Transitional//EN"
doctype-system="http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"/>

<xsl:attribute-set name="ricoTable"> 
  <xsl:attribute name="cellspacing">0</xsl:attribute> 
  <xsl:attribute name="cellpadding">0</xsl:attribute> 
</xsl:attribute-set> 

<!-- the identity template -->

<xsl:template match="*">
  <xsl:copy>
  <xsl:copy-of select="@*"/>
  <xsl:apply-templates/>
  </xsl:copy>
</xsl:template>


<!-- Transform head section -->

<xsl:template match="xhtml:head">
  <xsl:copy>
  <xsl:apply-templates mode="head"/>
<script type="text/javascript">
//<![CDATA[
if (typeof ricoInit!='undefined') {
  if (window.addEventListener)
    window.addEventListener('load', ricoInit, false);
  else if (window.attachEvent)
    window.attachEvent('onload', ricoInit);
}
// ]]>
</script>
  </xsl:copy>
</xsl:template>

<xsl:template match="*[name()!='script']" mode="head">
  <xsl:copy>
  <xsl:copy-of select="@*|node()"/>
  </xsl:copy>
</xsl:template>

<xsl:template match="xhtml:script" mode="head">
  <xsl:copy>
  <xsl:copy-of select="@*"/>
  <xsl:value-of select="." disable-output-escaping="yes"/>
  </xsl:copy>
</xsl:template>


<!-- Transform tables with class ricoSimpleGrid -->
  
<xsl:template match="xhtml:table[@class='ricoSimpleGrid']">
<xsl:choose>

<xsl:when test="xhtml:thead">
<xsl:call-template name="processTable">
<xsl:with-param name="id" select="@id"/>
<xsl:with-param name="headRows" select="xhtml:thead/xhtml:tr"/>
<xsl:with-param name="bodyRows" select="xhtml:tbody/xhtml:tr"/>
</xsl:call-template>
</xsl:when>

<xsl:when test="xhtml:tbody">
<xsl:call-template name="processTable">
<xsl:with-param name="id" select="@id"/>
<xsl:with-param name="headRows" select="xhtml:tbody/xhtml:tr[1]"/>
<xsl:with-param name="bodyRows" select="xhtml:tbody/xhtml:tr[position() &gt; 1]"/>
</xsl:call-template>
</xsl:when>

<xsl:otherwise>
<xsl:call-template name="processTable">
<xsl:with-param name="id" select="@id"/>
<xsl:with-param name="headRows" select="xhtml:tr[1]"/>
<xsl:with-param name="bodyRows" select="xhtml:tr[position() &gt; 1]"/>
</xsl:call-template>
</xsl:otherwise>

</xsl:choose>
</xsl:template>


<!-- Perform the actual table transformation -->
  
<xsl:template name="processTable">
<xsl:param name="id" />
<xsl:param name="headRows" />
<xsl:param name="bodyRows" />

<xsl:variable name="headIdx">
<xsl:choose>
<xsl:when test="$headRows[@class='ricoHeading']">
<xsl:value-of select="count($headRows[@class='ricoHeading']/preceding-sibling::*)+1"/>
</xsl:when>
<xsl:otherwise>
<xsl:value-of select="count($headRows)"/>
</xsl:otherwise>
</xsl:choose>
</xsl:variable>

<xsl:variable name="headMain" select="$headRows[position()=$headIdx]"/>
<xsl:variable name="headCols" select="$headMain/xhtml:th | $headMain/xhtml:td"/>

<!--
<p><xsl:value-of select="$id"/>
<br />headRowCnt: <xsl:value-of select="count($headRows)"/>
<br />headIdx: <xsl:value-of select="$headIdx"/>
<br />bodyRowCnt: <xsl:value-of select="count($bodyRows)"/>
</p>
-->

<xsl:element name="div">
<xsl:attribute name="id"><xsl:value-of select="concat($id,'_outerDiv')"/></xsl:attribute>
<xsl:attribute name="class">ricoLG_outerDiv</xsl:attribute>
<xsl:attribute name="onload"></xsl:attribute>

<!-- Create frozen (left) pane -->

<xsl:element name="div">
<xsl:attribute name="id"><xsl:value-of select="concat($id,'_frozenTabsDiv')"/></xsl:attribute>
<xsl:attribute name="class">ricoLG_frozenTabsDiv</xsl:attribute>

<xsl:call-template name="convertTHead">
<xsl:with-param name="rows" select="$headRows"/>
<xsl:with-param name="headIdx" select="$headIdx"/>
<xsl:with-param name="frozen" select="1"/>
<xsl:with-param name="id" select="concat($id,'_tab0h')"/>
</xsl:call-template>

<xsl:call-template name="convertTBody">
<xsl:with-param name="rows" select="$bodyRows"/>
<xsl:with-param name="cols" select="$headCols"/>
<xsl:with-param name="id" select="concat($id,'_tab0')"/>
<xsl:with-param name="frozen" select="1"/>
</xsl:call-template>

</xsl:element>

<xsl:element name="div">
<xsl:attribute name="id"><xsl:value-of select="concat($id,'_innerDiv')"/></xsl:attribute>
<xsl:attribute name="class">ricoLG_innerDiv</xsl:attribute>

<xsl:element name="div">
<xsl:attribute name="id"><xsl:value-of select="concat($id,'_scrollTabsDiv')"/></xsl:attribute>
<xsl:attribute name="class">ricoLG_scrollTabsDiv</xsl:attribute>

<xsl:call-template name="convertTHead">
<xsl:with-param name="rows" select="$headRows"/>
<xsl:with-param name="headIdx" select="$headIdx"/>
<xsl:with-param name="frozen" select="0"/>
<xsl:with-param name="id" select="concat($id,'_tab1h')"/>
</xsl:call-template>

</xsl:element>
</xsl:element>

<xsl:element name="div">
<xsl:attribute name="id"><xsl:value-of select="concat($id,'_scrollDiv')"/></xsl:attribute>
<xsl:attribute name="class">ricoLG_scrollDiv</xsl:attribute>

<xsl:call-template name="convertTBody">
<xsl:with-param name="rows" select="$bodyRows"/>
<xsl:with-param name="cols" select="$headCols"/>
<xsl:with-param name="id" select="concat($id,'_tab1')"/>
<xsl:with-param name="frozen" select="0"/>
</xsl:call-template>
</xsl:element>

</xsl:element>

</xsl:template>


<!-- Convert thead section -->

<xsl:template name="convertTHead">
<xsl:param name = "rows" />
<xsl:param name = "headIdx" />
<xsl:param name = "frozen" />
<xsl:param name = "id" />
<xsl:element name="table" use-attribute-sets="ricoTable">
<xsl:attribute name="id"><xsl:value-of select="$id"/></xsl:attribute>
<xsl:attribute name="class">ricoLG_table ricoLG_top
<xsl:if test="$frozen">ricoLG_left</xsl:if>
<xsl:if test="not($frozen)">ricoLG_right</xsl:if>
</xsl:attribute>
<xsl:element name="thead">
  <xsl:for-each select="$rows">
    <xsl:choose>
    <xsl:when test="position() = $headIdx">
      <xsl:apply-templates select="." mode="convertHeadRow">
      <xsl:with-param name="id" select="concat($id,'_main')"/>
      <xsl:with-param name="frozen" select="$frozen"/>
      </xsl:apply-templates>
    </xsl:when>
    <xsl:otherwise>
      <xsl:apply-templates select="." mode="convertHeadRow">
      <xsl:with-param name="id" select="concat($id,'_',position())"/>
      <xsl:with-param name="frozen" select="$frozen"/>
      </xsl:apply-templates>
    </xsl:otherwise>
    </xsl:choose>
  </xsl:for-each>
</xsl:element>
<tbody />
</xsl:element>
</xsl:template>


<xsl:template match="*" mode="convertHeadRow">
<xsl:param name = "id" />
<xsl:param name = "frozen" />
  <xsl:variable name="cells" select="xhtml:th | xhtml:td"/>
  <xsl:element name="tr">
  <xsl:if test="$id">
    <xsl:attribute name="id"><xsl:value-of select="$id"/></xsl:attribute>
  </xsl:if>
  <xsl:attribute name="class">ricoLG_hdg</xsl:attribute>
  <xsl:for-each select="$cells[@class='ricoFrozen' and $frozen or not(@class='ricoFrozen') and not($frozen)]">
      <xsl:copy>
        <xsl:copy-of select="@*"/>
        <div class='ricoLG_col' style='width:100px'>
          <xsl:element name="div">
          <xsl:attribute name="class">ricoLG_cell <xsl:value-of select="@class"/></xsl:attribute>
            <xsl:copy-of select="* | @*[name()!='class'] | text()"/>
          </xsl:element>
        </div>
      </xsl:copy>
    </xsl:for-each>
  </xsl:element>
</xsl:template>


<!-- Convert tbody section -->

<xsl:template name="convertTBody">
<xsl:param name = "rows" />
<xsl:param name = "cols" />
<xsl:param name = "id" />
<xsl:param name = "frozen" />
<xsl:element name="table" use-attribute-sets="ricoTable">
<xsl:attribute name="id"><xsl:value-of select="$id"/></xsl:attribute>
<xsl:attribute name="class">ricoLG_table ricoLG_bottom
<xsl:if test="$frozen">ricoLG_left</xsl:if>
<xsl:if test="not($frozen)">ricoLG_right</xsl:if>
</xsl:attribute> 
<xsl:element name="tbody">
  <tr>
  <xsl:for-each select="$cols">
    <xsl:if test="@class='ricoFrozen' and $frozen or not(@class='ricoFrozen') and not($frozen)">
      <xsl:variable name="colpos" select="position()"/>
      <td>
        <div class='ricoLG_col' style='width:100px'>
          <xsl:for-each select="$rows">
            <xsl:element name="div">
            <xsl:attribute name="class">ricoLG_cell <xsl:value-of select="xhtml:td[$colpos]/@class"/></xsl:attribute>
              <xsl:copy-of select="xhtml:td[$colpos]/* | xhtml:td[$colpos]/@*[name()!='class'] | xhtml:td[$colpos]/text()"/>
            </xsl:element>
          </xsl:for-each>
        </div>
      </td>
    </xsl:if>
  </xsl:for-each>
  </tr>
</xsl:element>
</xsl:element>
</xsl:template>

</xsl:stylesheet> 
