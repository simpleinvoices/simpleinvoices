<?php 
// $Header: /cvsroot/html2ps/xhtml.tables.inc.php,v 1.9 2006/10/28 12:24:16 Konstantin Exp $

function process_cell(&$sample_html, $offset) {
  $r = autoclose_tag($sample_html, $offset, 
                       "(table|td|th|tr|thead|tbody|tfoot|/td|/th|/table|/thead|/tbody|/tfoot|/tr)",
                       array("table" => "process_table"),
                       "/td");
  return $r;
};

function process_header_cell(&$sample_html, $offset) {
  return autoclose_tag($sample_html, $offset, 
                       "(table|td|th|tr|thead|tbody|tfoot|/td|/th|/table|/thead|/tbody|/tfoot|/tr)",
                       array("table" => "process_table"),
                       "/th");
};

function process_cell_without_row(&$html, $offset) {
  // Insert missing <tr> tag and fall to the 'process_row'

  // get the LAST tag before offset point; it should be the TD tag outside the row
  preg_match("#<[^>]+>$#",substr($html,0,$offset),$matches);

  // Now 'matches' contains the bad TD tag (opening)

  // Insert the TR tag before the TD found
  $html = substr_replace($html, "<tr>".$matches[0], $offset - strlen($matches[0]), strlen($matches[0]));

  // Restart row processing from the beginning of inserted TR (not inclusing the TR tag itself!, as it will cause the closing
  // tag to be inserted automatically)
  //
  $r = process_row($html, $offset - strlen($matches[0]) + strlen("<tr>"));

  return $r;
};

function process_row(&$sample_html, $offset) {
  return autoclose_tag_cleanup($sample_html, $offset, 
                               "(td|th|thead|tbody|tfoot|tr|/table|/thead|/tbody|/tfoot|/tr)",
                               array("td" => "process_cell",
                                     "th" => "process_header_cell"),
                               "/tr");
};


function process_rowgroup($group, &$sample_html, $offset) {
  return autoclose_tag_cleanup($sample_html, $offset, 
                               "(thead|tbody|tfoot|td|th|tr|/table|/{$group})",
                               array("tr" => "process_row",
                                     "td" => "process_cell",
                                     "th" => "process_header_cell"),
                               "/{$group}");
}

function process_thead(&$html, $offset) { return process_rowgroup('thead', $html, $offset); }
function process_tbody(&$html, $offset) { return process_rowgroup('tbody', $html, $offset); }
function process_tfoot(&$html, $offset) { return process_rowgroup('tfoot', $html, $offset); }

function process_col(&$html, $offset) {
  // As COL is self-closing tag, we just continue processing
  return $offset;
}

function process_col_without_colgroup(&$html, $offset) {
  // Insert missing <colgroup> tag and fall to the 'process_colgroup'

  // get the LAST tag before offset point; it should be the COL tag outside the COLGROUP
  preg_match("#<[^>]+>$#",substr($html,0,$offset),$matches);

  // Now 'matches' contains this COL tag (self-closing)

  // Insert the COLGROUP tag before the COL found
  $sample_html = substr_replace($html, "<colgroup>".$matches[0], $offset - strlen($matches[0]), strlen($matches[0]));

  // Restart colgroup processing from the beginning of inserted COLGROUP
  return process_colgroup($html, $offset - strlen($matches[0]));
}

function process_colgroup(&$html, $offset) {
  return autoclose_tag_cleanup($html, $offset, 
                               "(col|colgroup|thead|tbody|tfoot|tr|td|th|/colgroup)",
                               array("col"      => "process_col"),
                               "/colgroup");
}

function process_table(&$html, $offset) {
  return autoclose_tag_cleanup($html, $offset, 
                               "(col|colgroup|thead|tbody|tfoot|tr|td|th|/table)",
                               array("col"      => "process_col_without_colgroup",
                                     "colgroup" => "process_colgroup",
                                     "thead"    => "process_thead",
                                     "tbody"    => "process_tbody",
                                     "tfoot"    => "process_tfoot",
                                     "tr"       => "process_row",
                                     "td"       => "process_cell_without_row",
                                     "th"       => "process_cell_without_row"),
                               "/table");
};

function process_tables(&$sample_html, $offset) {
  return autoclose_tag($sample_html, $offset, 
                       "(table)",
                       array("table" => "process_table"),
                       "");
};

?>