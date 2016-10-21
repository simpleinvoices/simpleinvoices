<?php

//get invoice details

// why hardcode invoice number below?
$invoice = Invoice::select('1');

header('Content-type: application/xml');
echo encode::xml($invoice);
print_r($invoice);
