<?php

$message = cron::run();
$smarty -> assign('message', $message);
