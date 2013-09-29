<?php 
// $Header: /cvsroot/html2ps/xhtml.selects.inc.php,v 1.3 2005/04/27 16:27:46 Konstantin Exp $

function process_option(&$sample_html, $offset) {
  return autoclose_tag($sample_html, $offset, "(option|/select|/option)", 
                       array(), 
                       "/option");  
};

function process_select(&$sample_html, $offset) {
  return autoclose_tag($sample_html, $offset, "(option|/select)", 
                       array("option" => "process_option"), 
                       "/select");  
};

function process_selects(&$sample_html, $offset) {
  return autoclose_tag($sample_html, $offset, "(select)", 
                       array("select" => "process_select"), 
                       "");  
};

?>