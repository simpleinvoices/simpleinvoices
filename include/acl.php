<?php


$acl = new Zend_Acl();

//create the user roles
$acl->addRole(new Zend_Acl_Role('administrator'));
$acl->addRole(new Zend_Acl_Role('domain_administrator'));
$acl->addRole(new Zend_Acl_Role('user'));
$acl->addRole(new Zend_Acl_Role('operator'));
$acl->addRole(new Zend_Acl_Role('viewer'));
$acl->addRole(new Zend_Acl_Role('customer'));
$acl->addRole(new Zend_Acl_Role('biller'));

//create the resources
$acl->addResource('api');
$acl->addResource('auth');
$acl->addResource('billers');
// actions: biller_view, biller_edit
$acl->addResource('cron');
$acl->addResource('custom_fields');
// actions: custom_fields_view, custom_fields_edit
$acl->addResource('customers');
// actions: customer_view, customer_edit
$acl->addResource('documentation');
$acl->addResource('export');
$acl->addResource('extensions');
$acl->addResource('index');
$acl->addResource('install');
$acl->addResource('inventory');
$acl->addResource('invoices');
// actions: view (when view=details means edit)
$acl->addResource('options');
$acl->addResource('payment_types');
// actions: payment_types_view, payment_types_edit
$acl->addResource('payments');
$acl->addResource('preferences');
// actions: preferences_view, preferences_edit
$acl->addResource('product_attribute');
$acl->addResource('product_value');
$acl->addResource('products');
// actions: product_view, product_edit
$acl->addResource('reports');
$acl->addResource('statement');
$acl->addResource('system_defaults');
$acl->addResource('tax_rates');
// actions: tax_rates_view, tax_rates_edit
$acl->addResource('user');
// actions: user_view, user_edit

$acl->addResource('expense');
// actions: view, edit
$acl->addResource('expense_account');
// actions: view, edit

// If no action exists, then check with $acl->isAllowed($auth_session->role_name, $module, $acl_view)
// Otherwise, check with $acl->isAllowed($auth_session->role_name, $module, $acl_action)
// All checks are set in include/check_permission.php

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


// operator - is a user Role with restrictions as below:
// Cannot edit invoices, cron (recurrences), add/edit billers/customers

$acl->allow('operator');
$acl->deny('operator','options');
$acl->deny('operator','system_defaults');
$acl->deny('operator','custom_fields');
$acl->deny('operator','user');
$acl->deny('operator','tax_rates');
$acl->deny('operator','preferences');
$acl->deny('operator','payment_types');
$acl->deny('operator','cron');

$acl->allow('operator','invoices','manage');
$acl->deny('operator','invoices','view');

$acl->allow('operator', 'billers', 'manage');
$acl->allow('operator', 'billers', 'view');
$acl->deny('operator',  'billers', 'add');
$acl->deny('operator',  'billers', 'edit');

$acl->allow('operator', 'customers', 'manage');
$acl->allow('operator', 'customers', 'view');
$acl->deny('operator',  'customers', 'add');
$acl->deny('operator',  'customers', 'edit');



