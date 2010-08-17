<?php

$cron = new cron();
$cron->domain_id=1;
$message = $cron->run();

$smarty -> assign('message', $message);
