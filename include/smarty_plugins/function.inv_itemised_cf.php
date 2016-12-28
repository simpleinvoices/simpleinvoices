<?php
function smarty_function_inv_itemised_cf($params) {
    if ($params['field'] != null) {
        echo "<td width=50%>" . htmlsafe($params[label]) . ": " . htmlsafe($params[field]) . "</td>";
    }
}
