<?php
global $smarty;

$message = Cron::run();
$smarty->assign('message', $message);
