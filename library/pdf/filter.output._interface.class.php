<?php
class OutputFilter {
  function content_type() {
    die("Unoverridden 'content_type' method called in ".get_class($this));
  }

  function process($tmp_filename) {
    die("Unoverridden 'process' method called in ".get_class($this));
  }
}
?>