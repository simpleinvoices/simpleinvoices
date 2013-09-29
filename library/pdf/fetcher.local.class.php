<?php

class FetcherLocalFile extends Fetcher {
  var $_content;
  
  function FetcherLocalFile($file) {
    $this->_content = file_get_contents($file);
  }

  function get_data($dummy1) {
    return new FetchedDataURL($this->_content, array(), "");
  }
  
  function get_base_url() {
    return "";
  }

  function error_message() {
    return "";
  }
}
?>