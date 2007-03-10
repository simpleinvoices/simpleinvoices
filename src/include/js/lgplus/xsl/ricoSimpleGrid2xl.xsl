<xsl:stylesheet version="1.0"
  xmlns="urn:schemas-microsoft-com:office:spreadsheet"
  xmlns:xhtml="http://www.w3.org/1999/xhtml"
  xmlns:xsl="http://www.w3.org/1999/XSL/Transform" 
	xmlns:msxsl="urn:schemas-microsoft-com:xslt"
	xmlns:o="urn:schemas-microsoft-com:office:office"
	xmlns:x="urn:schemas-microsoft-com:office:excel"
  xmlns:ss="urn:schemas-microsoft-com:office:spreadsheet">

<xsl:output method="xml" indent="yes" omit-xml-declaration="no" media-type="application/xml"/>

<xsl:template match="/">
  <xsl:processing-instruction name="mso-application">
  <xsl:text>progid="Excel.Sheet"</xsl:text> 
  </xsl:processing-instruction>

  <Workbook xmlns="urn:schemas-microsoft-com:office:spreadsheet"
   xmlns:o="urn:schemas-microsoft-com:office:office"
   xmlns:x="urn:schemas-microsoft-com:office:excel"
   xmlns:ss="urn:schemas-microsoft-com:office:spreadsheet"
   xmlns:html="http://www.w3.org/TR/REC-html40">

 <Styles>
  <Style ss:ID="Default" ss:Name="Normal">
   <Alignment ss:Vertical="Bottom"/>
   <Borders/>
   <Font/>
   <Interior/>
   <NumberFormat/>
   <Protection/>
  </Style>
  <Style ss:ID="s21">
   <Font ss:Bold="1"/>
   <Alignment ss:Horizontal="Center" ss:Vertical="Bottom"/>
  </Style>
  <Style ss:ID="s22">
   <Alignment ss:Horizontal="Left" ss:Vertical="Bottom"/>
   <Font ss:Bold="1"/>
   <Interior ss:Color="#99CCFF" ss:Pattern="Solid"/>
  </Style>
  <Style ss:ID="s23" ss:Name="Currency">
   <NumberFormat
    ss:Format="_(&quot;$&quot;* #,##0.00_);_(&quot;$&quot;* \(#,##0.00\);_(&quot;$&quot;* &quot;-&quot;??_);_(@_)"/>
  </Style>
  <Style ss:ID="s24">
   <NumberFormat ss:Format="_(* #,##0.00_);_(* \(#,##0.00\);_(* &quot;-&quot;??_);_(@_)"/>
  </Style>
  <Style ss:ID="s25">
   <Alignment ss:Horizontal="Center" ss:Vertical="Bottom"/>
  </Style>
 </Styles>

  <xsl:apply-templates mode="top"/> 

  </Workbook>
</xsl:template>


<xsl:template match="*" mode="top">
  <xsl:choose>

  <xsl:when test="xhtml:table[@class='ricoSimpleGrid']">
  <xsl:apply-templates mode="grid"/> 
  </xsl:when>

  <xsl:otherwise>
  <xsl:apply-templates select="*" mode="top"/> 
  </xsl:otherwise>
  
  </xsl:choose>
</xsl:template>


<xsl:template match="*" mode="grid">

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

 <Worksheet>
 <xsl:attribute name="ss:Name">
   <xsl:value-of select='$id'/>
 </xsl:attribute>
  <Table>

  <xsl:apply-templates select="$headRows" mode="convertHeadRow"/>
  <xsl:apply-templates select="$bodyRows" mode="convertBodyRow"/>

  </Table>
 </Worksheet>

</xsl:template>


<xsl:template match="*" mode="convertHeadRow">
   <Row>
    <xsl:apply-templates select="xhtml:td | xhtml:th" mode="convertHeadCell"/>
   </Row>
</xsl:template>


<xsl:template match="*" mode="convertHeadCell">
  <xsl:element name="Cell">
  <xsl:attribute name="ss:StyleID">s22</xsl:attribute>
  <xsl:if test="@colspan">
  <xsl:attribute name="ss:MergeAcross"><xsl:value-of select="number(@colspan)-1"/></xsl:attribute>
  </xsl:if>
    <Data ss:Type="String">
    <xsl:value-of select="."/>
    </Data>
  </xsl:element>
</xsl:template>


<xsl:template match="*" mode="convertBodyRow">
   <Row>
    <xsl:apply-templates select="xhtml:td | xhtml:th" mode="convertBodyCell"/>
   </Row>
</xsl:template>


<xsl:template match="*" mode="convertBodyCell">
    <Cell><Data ss:Type="String"><xsl:value-of select="."/></Data></Cell>
</xsl:template>

</xsl:stylesheet>