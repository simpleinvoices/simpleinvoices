<?php
class DataFilterUTF8 extends DataFilter {
  function DataFilterUTF8($encoding) {
    $this->encoding = $encoding;
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
}
?>