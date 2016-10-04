<?php

define("TB_PREFIX","si_"); // default table prefix - old var $tb_prefix = "si_";

define("ENABLED","1");
define("DISABLED","0");

//invoice styles
define("TOTAL_INVOICE","1");
define("ITIMISED_INVOICE","2");
define("CONSULTING_INVOICE","3");

//To turn logging on set the below to true - not needed as it is set in System Defaults
define("LOGGING",false);
//define("LOGGING",true);

// Include another config file if required
if (is_file('./config/custom.config.php')) {
    define("CONFIG_FILE_PATH", "config/custom.config.php");
} else {
    define("CONFIG_FILE_PATH", "config/config.php");
}

####################
/* Environment*/
####################
/*
This allows you to have another local config file for your dev or other purposes
ie. dev.config.php
any config.php setting in this extra file(which wont be kept in svn) will overwrite config.php values
- this way everyone can have their own conf setting without messing with anyone else's setting
RELEASE TODO: make sure $environment is set back to live
*/
$environment = "production"; //test,staging,dev,live etc..
if ($environment) {} // remove unused warning.
