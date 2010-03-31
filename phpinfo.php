<?php

/*
* Script: phpinfo.php
* 	Show the servers PHP settings
*
* License:
*	 GPL v3 or above
*/

$secure = true;

if($secure)
{
    die("
        =============================<br/>
        Simple Invoices security warning<br/>
        =============================<br/>
        <br/>
        PHPINFO is disabled by default for security reasons. 
        <br/>
        To view your phpinfo contents, edit the file phpinfo.php
        in your Simple Invoices directory and change line 11 from: <br />
        <br />
        \$secure = true;<br /><br />
        to<br /><br />
        \$secure = false;<br /><br />
        and refresh this page.
        Once you have finished using the information from phpinfo it
        is advised to re-edit the phpinfo.php and change it back to \$secure = true;
    ");
} else {
    #print all the PHP info for your system
    phpinfo();

}