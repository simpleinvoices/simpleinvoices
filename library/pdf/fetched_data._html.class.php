<?php
class FetchedDataHTML extends FetchedData {
  function detect_encoding() {
    die("Unoverridden 'detect_encoding' called in ".get_class($this));
  }

  function _detect_encoding_using_meta() {
    if (preg_match("#<\s*meta[^>]+content=(['\"])?text/html;\s*charset=([\w\d-]+)#is",$this->get_content(),$matches)) {
      return strtolower($matches[2]);
    } else {
      return null;
    };
  }
}
?>