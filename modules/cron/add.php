<?php

$cron = new cron();
$cron->domain_id=1;
$cron->invoice_id=1;
$cron->start_date='2010-02-02';
$cron->end_date='';
$cron->recurrence='7';
$cron->recurrence_type='days';
$cron->email_biller='';
$cron->email_customer='';
$cron->insert();
