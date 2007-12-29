import libxml2
import libxslt

styledoc = libxml2.parseFile("xslt/xsltest.xsl")
style = libxslt.parseStylesheetDoc(styledoc)
doc = libxml2.parseFile("xsltest.xml")
result = style.applyStylesheet(doc, None)
print result.content
style.freeStylesheet()
doc.freeDoc()
result.freeDoc()
