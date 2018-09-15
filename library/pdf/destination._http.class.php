<?php
class DestinationHTTP extends Destination {  
  function __construct($filename) {
    parent::__construct($filename);
  }

  function headers($content_type) {
    die("Unoverridden 'header' method called in ".get_class($this));
  }

  function process($tmp_filename, $content_type) {
    header("Content-Type: ".$content_type->mime_type);
    
    $headers = $this->headers($content_type);
    foreach ($headers as $header) {
      header($header);
    };

    // NOTE: readfile does not work well with some Windows machines
    // echo(file_get_contents($tmp_filename));
    readfile($tmp_filename);
  }
}
