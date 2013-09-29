<?php
class Destination {
  var $filename;

  function Destination($filename) {
    $this->set_filename($filename);
  }

  function filename_escape($filename) { return preg_replace("/[^a-z0-9-]/i","_",$filename); }

  function get_filename() { return empty($this->filename) ? OUTPUT_DEFAULT_NAME : $this->filename; }

  function process($filename, $content_type) {
    die("Oops. Inoverridden 'process' method called in ".get_class($this));
  }

  function set_filename($filename) { $this->filename = $filename; }
}
?>