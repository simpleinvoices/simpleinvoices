<?php

define("TB_PREFIX","si_"); // default table prefix - old var $tb_prefix = "si_";

define("ENABLED","1"); // 
define("DISABLED","0"); // 

//invoice styles
define("total_invoice","1"); // 
define("itemised_invoice","2"); // 
define("consulting_invoice","3"); // 

//===============================================================================
// CUSTOMER LOGIN SECION
//===============================================================================
//Customer login -- enable to allow customers to login
define("ENABLE_CUSTOMER_LOGIN","true"); //
// For eu regulations only 'locked' invoices are allowed to communicate to 
// customers. By setting option below to true means that customers can 
// look at their invoices, but that the invoice might change at any
// time. 
define("VIEW_DRAFT_INVOICES","false");
//============================================== end customer login section =====


//To turn logging on set the below to true - not needed as it is set in System Defaults
#define("LOGGING",false);
define("LOGGING",true);


####################
/* Environment*/
####################
/*
This allows you to have another local config file for your dev or other purposes
ie. dev.config.php 
any config.php setting in this extra file(which wont be kept in svn) will overwrite config.php values
- this way everyone can have there own conf setting without messing with anyones setting
RELEASE TODO: make sure $environment is set back to live
*/
$environment = "production"; //test,staging,dev,live etc..
