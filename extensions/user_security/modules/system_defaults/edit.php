<?php
// The $description, $default, $value field are required to set up the generic
// edit template for this extension value.
$description = "{$LANG['session_timeout']}";
$default     = "session_timeout";
$attribute   = htmlsafe($defaults[$default]);
$value       = '<input type="text" size="4" name="value" value="' . $attribute . '"
                       pattern="[1-9][0-9]|[1-9][0-9][0-9]"
                       title="The entry must be 10 through 999 minutes.">' . "\n";
