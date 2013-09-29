<?php

class OutputFilterGZip extends OutputFilter {
  function content_type() {
    return null;
    //    return ContentType::gz();
  }

  function process($tmp_filename) {
    $output_file = $tmp_filename.'.gz';

    $file = gzopen($output_file, "wb");
    gzwrite($file, file_get_contents($tmp_filename));
    gzclose($file);

    unlink($tmp_filename);
    return $output_file;
  }
}
?>