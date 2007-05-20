<?php

function smarty_function_do_tr($params, &$smarty)
{
	if ($params['number'] == 2 ) {
		$new_tr = "</tr><tr class='$params[class]'>";
		return $new_tr;
	}
	
        if ($params['number'] == 4 ) {
                $new_tr = "</tr><tr class='$params[class]'>";
                return $new_tr;
        }

}


?>
