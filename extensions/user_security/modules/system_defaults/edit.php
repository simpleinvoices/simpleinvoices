<?php
global $get_val, $description, $LANG, $defaults, $value, $found;

switch ($get_val) {
    // @formatter:off
    case "company_logo":
        $default     = $get_val;
        $description = "{$LANG[$default]}";
        $attribute   = htmlsafe($defaults[$default]);
        $value       = "<input type='text' size='60' name='value' value='$attribute' required />\n";
        $found       = true;
        break;

    case "company_name_item":
        $default     = $get_val;
        $description = "{$LANG[$default]}";
        $attribute   = htmlsafe($defaults[$default]);
        $value       = "<input type='text' size='60' name='value' value='$attribute' required />\n";
        $found       = true;
        break;

    case 'password_min_length':
        $default     = $get_val;
        $description = "{$LANG[$default]}";
        $attribute   = htmlsafe($defaults[$default]);
        $value       = "<input type='text' size='2' name='value' value='$attribute' required min='6' max='16' />\n";
        $found       = true;
        break;

    case 'password_lower':
        $default     = $get_val;
        $description = "{$LANG[$default]}";
        $array       = array(0 => $LANG['disabled'], 1 => $LANG['enabled']);
        $value       = dropDown($array, $defaults[$default]);
        $found       = true;
        break;

    case 'password_number':
        $default     = $get_val;
        $description = "{$LANG[$default]}";
        $array       = array(0 => $LANG['disabled'], 1 => $LANG['enabled']);
        $value       = dropDown($array, $defaults[$default]);
        $found       = true;
        break;

    case 'password_special':
        $default     = $get_val;
        $description = "{$LANG[$default]}";
        $array       = array(0 => $LANG['disabled'], 1 => $LANG['enabled']);
        $value       = dropDown($array, $defaults[$default]);
        $found       = true;
        break;

    case 'password_upper':
        $default     = $get_val;
        $description = "{$LANG[$default]}";
        $array       = array(0 => $LANG['disabled'], 1 => $LANG['enabled']);
        $value       = dropDown($array, $defaults[$default]);
        $found       = true;
        break;

    case "session_timeout":
        // The $description, $default, $value fields are required to set up the generic
        // edit template for this extension value.
        $default     = $get_val;
        $description = "{$LANG[$default]}";
        $attribute   = htmlsafe($defaults[$default]);
        $value       = "<input type='text' size='4' name='value' value='$attribute' min='15' max='999' />\n";
        $found       = true;
        break;
    // @formatter:on
}
if ($description || $value || $found) {} // Here to stop unused variable warnings.
