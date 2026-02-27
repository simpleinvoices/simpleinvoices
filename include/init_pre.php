<?php

/**
* Function: filenameEscape
* 
* Escapes a filename
* 
* Parameters:
* str		- the string to escape
*
* Returns:
* The escaped string.
**/

function filenameEscape($str)
{
    // Returns an escaped value.
    $safe_str = preg_replace('/[^a-z0-9\-_\.]/i','_',$str);
    return $safe_str;
}
