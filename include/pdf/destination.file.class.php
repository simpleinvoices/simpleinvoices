<?php
class DestinationFile extends Destination {
  function process($tmp_filename, $content_type) {
    $dest_filename = OUTPUT_FILE_DIRECTORY."/".$this->filename_escape($this->get_filename()).".".$content_type->default_extension;

    copy($tmp_filename, $dest_filename);
   # print("File saved as: ".$dest_filename);
  }
}
?>
