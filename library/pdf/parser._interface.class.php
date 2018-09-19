<?php
class Parser {
  function &process($html, &$pipeline, &$media) {
    die("Oops! Unoverridden 'process' method called in ".get_class($this));
  }
}
