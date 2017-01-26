<?php
function smarty_function_do_tr($params) {
    $new_tr = null;
    if ($params['number'] == 2) {
        $new_tr = "</tr><tr class='$params[class]'>";
    } else if ($params['number'] == 4) {
        $new_tr = "</tr><tr class='$params[class]'>";
    }
    if (isset($new_tr)) return $new_tr;
}
