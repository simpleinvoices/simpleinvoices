<?php

$cron = new cron();
$cron->domain_id=1;
$cron->invoice_id=1;
echo $cron->check();
