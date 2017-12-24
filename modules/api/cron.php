<?php
/*
 * Typical Cron Job to run each day:
 * #SimpleInvoices recurrence - run each day at 1AM
 * 0 1 * * * /usr/bin/wget -q -O - http://localhost/api-cron >/dev/null 2>&1
 *
 * Typical expansion of mod rewrite using the .htaccess file
 * api-cron => index.php?module=api&view=cron
 */
/*
 * if (!defined("BROWSE")) define("BROWSE", "browse");
 *
 * // **********************************************************
 * // The include configs and requirements stuff section - START
 * // **********************************************************
 *
 * // Load stuff required before init.php
 * require_once "include/init_pre.php";
 *
 * // globals set in the init.php logic
 * $databaseBuilt = false;
 * $databasePopulated = false;
 *
 * // Will be set in the following init.php call to extensions that are enabled.
 * $ext_names = array();
 * $help_image_path = "images/common/";
 *
 * $api_request = true;
 * if ($api_request || $databaseBuilt || $databasePopulated || $ext_names || $help_image_path) {} // Eliminates unused warnings
 * require_once "include/init.php";
 * global $logger;
 * $logger->log("cron.php - After init.php", Zend_Log::DEBUG);
 * foreach ($ext_names as $ext_name) {
 * if (file_exists("extensions/$ext_name/include/init.php")) {
 * require_once ("extensions/$ext_name/include/init.php");
 * }
 * }
 *
 * require_once 'Cron.php';
 * require_once 'encode.php';
 * ini_set('max_execution_time', 600); //600 seconds = 10 minutes
 *
 * $message = Cron::run();
 * try {
 * if(ob_get_contents()) ob_end_clean();
 * header('Content-type: application/xml');
 * echo encode::xml( $message );
 * } catch (Exception $e) {
 * echo $e->getMessage();
 * }
 */
error_log("In api/cron.php");
ini_set('max_execution_time', 600); // 600 seconds = 10 minutes

// remove hardcoding for multi-domain usage
// $cron->domain_id=1;
$message = Cron::run();
try {
    // json
    // header('Content-type: application/json');
    // echo encode::json( $message, 'pretty' );

    // xml
    ob_end_clean();
    header('Content-type: application/xml');
    echo encode::xml($message);
} catch (Exception $e) {
    echo $e->getMessage();
}
