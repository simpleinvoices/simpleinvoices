<?php
function smarty_function_totals_by_period($params, &$template) {
    $data = $template->getTemplateVars('data');
    $template->assign(this_data, $data[$params['type']]);
}
