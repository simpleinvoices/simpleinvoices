<?php

require_once(HTML2PS_DIR.'filter.data.encoding.class.php');

class DataFilterUCS2 extends DataFilterEncoding {
  function _convert(&$data, $encoding) {
    $converter = Converter::create();
    $data->set_content($converter->to_ucs2($data->get_content(), $encoding));
  }
}
?>