<?php
class Parser {
  function process(&$data) {
    die("Oops! Unoverridden 'process' method called in ".get_class($this));
  }
}
?>