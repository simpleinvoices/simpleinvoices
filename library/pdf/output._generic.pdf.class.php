<?php
// $Header: /cvsroot/html2ps/output._generic.pdf.class.php,v 1.1 2005/12/13 18:24:45 Konstantin Exp $

class OutputDriverGenericPDF extends OutputDriverGeneric {
  var $pdf_version;

  function OutputDriverGenericPDF() {
    $this->OutputDriverGeneric();
    $this->set_pdf_version("1.3");
  }

  function content_type() { return ContentType::pdf(); }

  function get_pdf_version() { 
    return $this->pdf_version; 
  }

  function reset($media) {
    OutputDriverGeneric::reset($media);
  }

  function set_pdf_version($version) {
    $this->pdf_version = $version;
  }
}
?>