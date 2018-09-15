<?php

class DestinationNull extends Destination {
  function __construct() {
      parent::__construct('');
  }

  function process($filename, $content_type) {
error_log("null.php process");
    // Do nothing
  }
}
