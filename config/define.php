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
$environment = "dev"; //test,staging,dev,live etc..

if($environment != "production")
{
    @include("./config/".htmlspecialchars($environment).".define.php");
}


#####################
/* Error reporting */
#####################
#
#error_reporting(E_STRICT);
#error_reporting(E_ALL);
#error_reporting(E_WARNING);
error_reporting(E_ERROR);
#error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);
#error_reporting(0);

ini_set('display_startup_errors', 1);  
ini_set('display_errors', 1); 
