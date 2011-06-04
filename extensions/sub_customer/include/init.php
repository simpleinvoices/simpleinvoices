<?php

include ('extensions/sub_customer/include/class/sub_customer.php');

function getSubCustomers($customer_id)
{
    $sql="select
            * 
            from
                ". TB_PREFIX . "customers
            where
                parent_customer_id = :customer_id
            and
                domain_id = :domain_id";
	global $db_server;
	global $dbh;
	global $auth_session;

	$sth = dbQuery($sql, ':customer_id', $customer_id, ':domain_id',$auth_session->domain_id) or die(htmlsafe(end($dbh->errorInfo())));
	return $sth->fetchAll();
}
