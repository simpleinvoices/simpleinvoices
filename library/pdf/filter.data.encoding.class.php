<?php
class DataFilterEncoding extends DataFilter {
  function DataFilterEncoding($encoding) {
    $this->encoding = $encoding;
  }

  function getEncoding() {
    return $this->encoding;
  }

  function process(&$data) {
    // Remove control symbols if any
    $data->set_content(preg_replace('/[\x00-\x07]/', "", $data->get_content()));

    if (empty($this->encoding)) {
      $encoding = $data->detect_encoding();

      if (is_null($encoding)) {
        $encoding = DEFAULT_ENCODING;
      };
      $converter = Converter::create();
      $data->set_content($converter->to_utf8($data->get_content(), $encoding));
    } else {
      $converter = Converter::create();
      $data->set_content($converter->to_utf8($data->get_content(), $this->encoding));
    };

    return $data;
  }

  function _convert(&$data, $encoding) {
    error_no_method('_convert', get_class($this));
  }
}
?>