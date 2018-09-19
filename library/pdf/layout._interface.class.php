<?php
class LayoutEngine {
  function process(&$box, &$media, &$driver, &$context) {
    die("Oops. Inoverridden 'process' method called in ".get_class($this));
  }
}
