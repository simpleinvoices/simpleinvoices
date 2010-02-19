<?php
$cron = new cron();
$cron->domain_id=1;
$message = $cron->run();

#echo encode::json($message);
#echo encode::xml($message);
//print_r($message);
//echo json_encode($invoice);

header('Content-type: application/xml');
try 
{
    $xml = new encode();
    $xml->xml( $message );
    echo $xml;
} 
catch (Exception $e) 
{
    echo $e->getMessage();
}
