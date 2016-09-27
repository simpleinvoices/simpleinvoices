<?php

//get invoice details

$invoiceobj = new Invoice();
// why hardcode invoice number below?
$invoice = $invoiceobj->select('1');

header('Content-type: application/xml');
echo encode::xml($invoice);
print_r($invoice);



