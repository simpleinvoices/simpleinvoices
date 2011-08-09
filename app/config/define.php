<?php

define("TB_PREFIX","si_"); // default table prefix - old var $tb_prefix = "si_";

define("ENABLED","1"); //
define("DISABLED","0"); //

//invoice styles
define("total_invoice","1"); //
define("itemised_invoice","2"); //
define("consulting_invoice","3"); //

//To turn logging on set the below to true - not needed as it is set in System Defaults
#define("LOGGING",false);
define("LOGGING",true);
// this is the url of the version file on the server needs to point to www.simpleinvoices.org
define("SI_VERSION_FILE","http://simpleinvoices.googlecode.com/svn/assets/siversion.xml");

define("SI_VERSION_ID","20101001");
define("SI_VERSION","2010.2 update 1");


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
