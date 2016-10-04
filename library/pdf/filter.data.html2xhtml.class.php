<?php

/**
 * Converts tags to lower case
 */

class DataFilterHTML2XHTML extends DataFilter {
  function process(&$data) {
    $data->set_content(html2xhtml($data->get_content()));
    return $data;
  }
}

?>