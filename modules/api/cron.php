<?php
/* 
 *  Typical Cron Job to run each day:
 *      #SimpleInvoices recurrence - run each day at 1AM
 *          0 1 * * * /usr/bin/wget -q -O - http://localhost/api-cron  >/dev/null 2>&1
 *
 *  Typical expansion of mod rewrite using the .htaccess file
 *      api-cron => index.php?module=api&view=cron
 */
ini_set('max_execution_time', 600); //600 seconds = 10 minutes

$message = Cron::run();
try {
    ob_end_clean();
    header('Content-type: application/xml');
    echo encode::xml( $message );
} catch (Exception $e) {
    echo $e->getMessage();
}
