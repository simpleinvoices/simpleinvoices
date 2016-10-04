<?php
class LayoutEngine {
  function process(&$tree, &$media) {
    die("Oops. Inoverridden 'process' method called in ".get_class($this));
  }
}
?>