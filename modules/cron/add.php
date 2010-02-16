<?php

$cron = new cron();
$cron->domain_id=1;
$cron->invoice_id=1;
$cron->start_date='2009-02-16';
$cron->end_date='';
$cron->recurrence='1';
$cron->recurrence_type='year';
$cron->email_biller='';
$cron->email_customer='';
$cron->insert();
