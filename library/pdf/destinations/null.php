<?php

class DestinationNull extends Destination {
  function DestinationNull() {
    $this->Destination('');
  }

  function process($filename, $content_type) {
    // Do nothing
  }
}

?>