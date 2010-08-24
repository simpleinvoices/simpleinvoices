<?php

class FetcherMemory extends Fetcher {
  var $base_path;
  var $base_url;
  var $content;

  function FetcherMemory($content, $base_path) {
    $this->content   = $content;
    $this->base_path = $base_path;
    $this->base_url  = $base_path;
  }

  function get_base_url() {
    return $this->base_path;
  }

  function &get_data($url) {
    if ($url != $this->base_path) {
      $null = null;
      return $null;
    };

    $data =& new FetchedDataFile($this->content, $this->base_path);
    return $data;
  }

  function set_base_url($base_url) {
    $this->base_url = $base_url;
  }
}


?>