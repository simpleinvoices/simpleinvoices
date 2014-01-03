<?php


$acl = new Zend_Acl();

//create the user roles
$acl->addRole(new Zend_Acl_Role('domain_administrator'));
$acl->addRole(new Zend_Acl_Role('administrator'));
$acl->addRole(new Zend_Acl_Role('user'));
$acl->addRole(new Zend_Acl_Role('viewer'));
$acl->addRole(new Zend_Acl_Role('customer'));
$acl->addRole(new Zend_Acl_Role('biller'));

//create the resources
$acl->addResource('api');
$acl->addResource('auth');
$acl->addResource('billers');
$acl->addResource('cron');
$acl->addResource('custom_fields');
$acl->addResource('customers');
$acl->addResource('documentation');
$acl->addResource('export');
$acl->addResource('extensions');
$acl->addResource('index');
$acl->addResource('install');
$acl->addResource('inventory');
$acl->addResource('invoices');
$acl->addResource('options');
$acl->addResource('payment_types');
$acl->addResource('payments');
$acl->addResource('preferences');
$acl->addResource('product_attribute');
$acl->addResource('product_value');
$acl->addResource('products');
$acl->addResource('reports');
$acl->addResource('statement');
$acl->addResource('system_defaults');
$acl->addResource('tax_rates');
$acl->addResource('user');

$acl->addResource('expense');
$acl->addResource('expense_account');

//assign roles to resources

/* alternatively, the above could be written:
$acl->allow('guest', null, 'view');
//*/

// Staff inherits view privilege from guest, but also needs additional privileges
//$acl->allow('student', null, array('customers'));
//$acl->deny('student');

// everyone see auth page
$acl->allow(null,'auth');
$acl->allow(null,'api');
$acl->allow(null,'payments','ach');
//TODO: not good !!! - no acl for invoices as can't get html2pdf to work with zend_auth :(
$acl->allow(null,'invoices');

//customers only see customer page (also invoices since no acl for it above)
$acl->allow('customer', 'customers', 'view');

// Editor inherits view, edit, submit, and revise privileges from staff,
// but also needs additional privileges
$acl->allow('domain_administrator');

// Administrator inherits nothing, but is allowed all privileges
$acl->allow('administrator');
//user - can do everything except anything in the Settings menu
$acl->allow('user');
$acl->deny('user','options');
$acl->deny('user','system_defaults');
$acl->deny('user','custom_fields');
$acl->deny('user','user');
$acl->deny('user','tax_rates');
$acl->deny('user','preferences');
$acl->deny('user','payment_types');
