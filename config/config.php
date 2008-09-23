<?php

define("TB_PREFIX","si_"); // default table prefix - old var $tb_prefix = "si_";

#####################
/* Authentication options */
#####################
//if you want to make Simple Invoices secure and require a username and password set this to true
//$authenticationOn = "true";
$authenticationOn = "false";
//if you are using a .httaccess file
$http_auth = ""; //value: "name:password@"

//To turn logging on set the below to true - not needed as it is set in System Defaults
define("LOGGING",false);
#define("LOGGING",true);

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
    @include("./config/".htmlspecialchars($environment).".config.php");
}

?>
