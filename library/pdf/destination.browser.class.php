<?php
class DestinationBrowser extends DestinationHTTP {
  function headers($content_type) {
    return array(
                 "Content-Disposition: inline; filename=".$this->filename_escape($this->get_filename()).".".$content_type->default_extension,
                 "Content-Transfer-Encoding: binary",
                 "Cache-Control: private"
                 );
  }
}
?>