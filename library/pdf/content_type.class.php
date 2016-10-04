<?php
class ContentType {
  var $default_extension;
  var $mime_type;

  function ContentType($extension, $mime) {
    $this->default_extension = $extension;
    $this->mime_type = $mime;
  }

  function png() {
    return new ContentType('png', 'image/png');
  }

  function gz() {
    return new ContentType('gz', 'application/gzip');
  }

  function pdf() {
    return new ContentType('pdf', 'application/pdf');
  }

  function ps() {
    return new ContentType('ps', 'application/postscript');
  }
}
?>