<?php

ini_set('max_execution_time', 600); //600 seconds = 10 minutes

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
    echo encode::xml( $message );
} 
catch (Exception $e) 
{
    echo $e->getMessage();
}
