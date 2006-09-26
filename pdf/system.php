<?php

if (extension_loaded('pdf')) {
  $pdf = pdf_new();
  print(pdf_get_value($pdf, 'major', 0));
  print(pdf_get_value($pdf, 'minor', 0));
  print(pdf_get_value($pdf, 'revision', 0));
};

?>