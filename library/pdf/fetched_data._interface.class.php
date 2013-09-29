<?php
class FetchedData {
  function get_additional_data() {
    die("Unoverridden 'get_additional_data' called in ".get_class($this));
  }

  function get_content() {
    die("Unoverridden 'get_content' called in ".get_class($this));
  }

  function get_uri() {
    return "";
  }
}
?>