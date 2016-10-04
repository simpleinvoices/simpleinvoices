<?php
class PostTreeFilter {
  function process(&$tree) {
    die("Oops. Inoverridden 'process' method called in ".get_class($this));
  }
}
?>