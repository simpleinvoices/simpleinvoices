<?php

ini_set('max_execution_time', 600); //600 seconds = 10 minutes

$inventory = new inventory();
$inventory->domain_id=1;
$message = $inventory->check_reorder_level();

try 
{

    //json
    //header('Content-type: application/json');
    #echo encode::json( $message, 'pretty' );
    
    //xml
    ob_end_clean();
    header('Content-type: application/xml');
    echo encode::xml( $message );
} 
catch (Exception $e) 
{
    echo $e->getMessage();
}
