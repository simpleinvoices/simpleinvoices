<?php

function smarty_function_do_tr($params, &$smarty) {
	if ($params['number'] == 2 || $params['number'] == 4) {
		return "</tr><tr class='" . htmlsafe($params['class']) . "'>";
	}
}
