<?php
class FetchedDataFile extends FetchedDataHTML {
  var $content;
  var $path;

  function FetchedDataFile($content, $path) {
    $this->content = $content;
    $this->path    = $path;
  }

  function detect_encoding() {
    // First, try to get encoding from META http-equiv tag
    //
    $encoding = $this->_detect_encoding_using_meta($this->content);

    // At last, fall back to default encoding
    //
    if (is_null($encoding)) { $encoding = "iso-8859-1";  }

    return $encoding;
  }

  function get_additional_data($key) {
    return null;
  }

  function get_content() {
    return $this->content;
  }

  function set_content($data) {
    $this->content = $data;
  }
}
?>