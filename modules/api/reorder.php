<?php

ini_set('max_execution_time', 600); //600 seconds = 10 minutes

$message = Inventory::check_reorder_level();

try {
    ob_end_clean();
    header('Content-type: application/xml');
    echo encode::xml( $message );
} catch (Exception $e) {
    echo $e->getMessage();
}
