<?php

ini_set('max_execution_time', 600); //600 seconds = 10 minutes

$cron = new cron();
$cron->domain_id=1;
$message = $cron->run();

try 
{

    ob_clean();
    //json
    header('Content-type: application/json')
    echo encode::json( $message, 'pretty' );
    
    //xml
    //header('Content-type: application/xml');
    //echo encode::xml( $message );
} 
catch (Exception $e) 
{
    echo $e->getMessage();
}