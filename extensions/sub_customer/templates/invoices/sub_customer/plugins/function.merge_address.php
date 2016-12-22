<?php

/**
 * Function: merge_address
 * 
 * Merges the city, state, and zip info onto one live and takes into account the commas 
 *
 * Arguments:
 * field1          -       normally city
 * field2          -       noramlly state
 * field3          -       normally zip  
 * street1         -      street 1 added print the word "Address:" on the first line of the invoice
 * street2         -      street 2 added print the word "Address:" on the first line of the invoice
 * class1          -      the css class for the first td
 * class2          -      the css class for the second td
 * colspan          -      the td colspan of the last td
 */
function smarty_function_merge_address($params, &$smarty) {
    global $LANG;
    $skip_section = false;
    $ma = '';
    // If any among city, state or zip is present with no street at all
    if (($params['field1' ] != null || $params['field2' ] != null || $params['field3'] != null) &&
        ($params['street1'] == null && $params['street2'] == null)) {
            $ma .= "<tr>
                      <td class='".htmlsafe($params[class1])."'>$LANG[address]:</td>
                      <td class='".htmlsafe($params[class2])."' colspan='".htmlsafe($params[colspan])."'>";
    $skip_section = true;
    }
    // If any among city, state or zip is present with atleast one street value
    if (($params['field1'] != null || $params['field2'] != null || $params['field3'] != null) && ( ! $skip_section )) {
        $ma .=  "<tr>
                   <td class='".htmlsafe($params[class1])."'></td>
                   <td class='".htmlsafe($params[class2])."' colspan='".htmlsafe($params[colspan])."'>";
    }
    if ($params['field1'] != null) {
        $ma .=  htmlsafe($params[field1]);
    }
    
    if ($params['field1'] != null && $params['field2'] != null  ) {
        $ma .=  ", ";
    }
    
    if ($params['field2'] != null) {
        $ma .=  htmlsafe($params[field2]);
    }
    
    if (($params['field1'] != null || $params['field2'] != null) && ($params['field3'] != null)) {
        $ma .=  ", ";
    }
    
    if ($params['field3'] != null) {
        $ma .=  htmlsafe($params[field3]);
    }
        
    $ma .= "</td></tr>";
    echo $ma;
}
