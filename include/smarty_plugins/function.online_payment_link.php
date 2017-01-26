<?php
function smarty_function_online_payment_link($params, &$smarty) {
    global $siUrl;

    $domain_id = domain_id::get($params['domain_id']);
    $url = getURL();
    if (in_array("paypal", explode(",", $params['type']))) {
        $link = "<a href=\"https://www.paypal.com/xclick/?business="        . urlencode($params['business']) .
                                                        "&item_name="        . urlencode($params['item_name']) .
                                                        "&invoice="          . urlencode($params['invoice']) .
                                                        "&amount="           . urlencode(number_format($params['amount'], 2, '.', '')) .
                                                        "&currency_code="    . urlencode($params['currency_code']) .
                                                        "&notify_url="       . urlencode($params['notify_url']) .
                                                        "&return="           . urlencode($params['return_url']) .
                                                        "&no_shipping=1"     .
                                                        "&no_note=1"         .
                                                        "&custom=domain_id:" . urlencode($domain_id) . "; \">";

        if ($params['include_image'] == "true") {
            $link .= "<img border='0' src='" . urlsafe($url) . "/images/common/pay_with_paypal.gif'/>";
        } else {
            $link .= htmlsafe($params['link_wording']);
        }
        $link .= "</a>";
        echo $link;
    }

    if (in_array("eway_shared", explode(",", $params['type']))) {
        // @formatter:off
        $link = "<a href=\"https://www.paypal.com/xclick/?business="                   . urlencode($params['business'] .
                                                        "&item_name="                  . urlencode($params['item_name']) .
                                                        "&invoice="                    . urlencode($params['invoice']) .
                                                        "&amount="                     . urlencode(number_format($params['amount'], 2, '.', '')) .
                                                        "&currency_code="              . $params['currency_code']) .
                                                        "&return=http://vcsweb.com.au" .
                                                        "&no_shipping=1" .
                                                        "&no_note=1\">";
        // $formatter:on
        if ($params['include_image'] == "true") {
            $link .= "<img border='0' src='" . urlsafe($url) . "/images/common/pay_with_eway.gif'/>";
        } else {
            $link .= htmlsafe($params['link_wording']);
        }
        $link .= "</a>";
        echo $link;
    }

    if (in_array("paymentsgateway", explode(",", $params['type']))) {
        // @formatter:off
        $link = "<a href='https://swp.paymentsgateway.net/co/default.aspx?pg_api_login_id="                . urlencode($params['api_id']) .
                                                                        "&pg_billto_postal_name_company="  . urlencode($params['customer']['name']) .
                                                                        "&pg_version_number=1.0"           .
                                                                        "&pg_total_amount="                . urlencode(number_format($params['amount'], 2, '.', '')) .
                                                                        "&pg_transaction_order_number="    . urlencode($params['invoice']) .
                                                                        "&pg_billto_postal_name_first="    . urlencode($params['customer']['attention']) .
                                                                        "&pg_billto_postal_name_last=-"    .
                                                                        "&pg_billto_postal_street_line1="  . urlencode($params['customer']['street_address']) .
                                                                        "&pg_billto_postal_street_line2="  . urlencode($params['customer']['street_address2']) .
                                                                        "&pg_billto_postal_city="          . urlencode($params['customer']['city']) .
                                                                        "&pg_billto_postal_stateprov="     . urlencode($params['customer']['state']) .
                                                                        "&pg_billto_postal_postalcode="    . urlencode($params['customer']['zip_code']) .
                                                                        "&pg_billto_telecom_phone_number=" . urlencode($params['customer']['phone']) .
                                                                        "&pg_billto_online_email="         . $params['customer']['email'] .
                                                                        "&pg_consumerorderid="             . $params['invoice'] .
                                                                        "&pg_return_url="                  . $siUrl . "/api-ach" .
                                                                        "&pg_save_client=2'>";
        // @formatter:on
        if ($params['include_image'] == "true") {
            $link .= "<img border='0' src='" . urlsafe($url) . "/images/common/pay_with_ach.gif'/>";
        } else {
            $link .= htmlsafe($params['link_wording']);
        }
        $link .= "</a>";
        echo $link;
    }
}
