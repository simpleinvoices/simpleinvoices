<?php
function smarty_function_holiday_logo($params) {
    // @formatter:off
    $holidays = array("_newyears."     => "1",
                      "_valentines."   => "2",
                      "_easter."       => "4",
                      "_independence." => "7",
                      "_thanksgiving." => "11",
                      "_christmas."    => "12");
    // @formatter:on

    $logo = $params['logo'];
    $parts = explode('.', $logo);
    if (count($parts) == 2) {
        $len_full_url = strlen($_SERVER['FULL_URL']);
        $rel_path = substr($parts[0], $len_full_url + 1);
        $now = new DateTime();
        $curr_month = $now->format('m');
        foreach($holidays as $holiday => $month) {
            if ($curr_month == $month) {
                $tmp_logo = $rel_path . $holiday . $parts[1];
                if (file_exists($tmp_logo)) {
                    if (empty($_SERVER['FULL_URL'])) {
                        $logo = $tmp_logo;
                    } else {
                        $logo = $_SERVER['FULL_URL'] . $tmp_logo;
                    }
                }
                break;
            }
        }
    }
    return $logo;
}
