<?php

//get invoice details

$invoice = invoice::select('1');
//echo json_encode($invoice);
/header('Content-type: application/xml');
echo encode::xml($invoice);
print_r($invoice);



