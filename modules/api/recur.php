<?php


$invoice = invoice::select('1');
$ni = new invoice();
$ni->biller_id = $invoice['biller_id'];
$ni->customer_id = $invoice['customer_id'];
$ni->type_id = $invoice['type_id'];
$ni->preference_id = $invoice['preference_id'];
$ni->date = $invoice['date_original'];
$ni->custom_field1 = $invoice['custom_field1'];
$ni->custom_field2 = $invoice['custom_field2'];
$ni->custom_field3 = $invoice['custom_field3'];
$ni->custom_field4 = $invoice['custom_field4'];
$ni->note = $invoice['note'];
$ni->insert();

