<?php
class PreTreeFilter {
  function process(&$tree, $data, &$pipeline) {
    die("Oops. Inoverridden 'process' method called in ".get_class($this));
  }
}
?>