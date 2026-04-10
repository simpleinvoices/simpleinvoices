<?php

// Wizard handler — processes sample-data insertions from the Getting Started wizard.
// All ops redirect (via template meta-refresh) back to the dashboard at the correct step.

checkLogin();

$op          = $_POST['op'] ?? null;
$json_path   = realpath(__DIR__ . '/../../databases/json/sample_data.json');
$sample      = ($json_path && file_exists($json_path)) ? json_decode(file_get_contents($json_path), true) : [];

$wizard_success   = false;
$wizard_next_step = 1;

if ($op === 'insert_sample_biller') {
    $b = $sample['si_biller'][0] ?? [];
    $_POST = [
        'name'                   => $b['name']                   ?? 'Sample Biller',
        'street_address'         => $b['street_address']         ?? '',
        'street_address2'        => $b['street_address2']        ?? '',
        'city'                   => $b['city']                   ?? '',
        'state'                  => $b['state']                  ?? '',
        'zip_code'               => $b['zip_code']               ?? '',
        'country'                => $b['country']                ?? '',
        'phone'                  => $b['phone']                  ?? '',
        'mobile_phone'           => $b['mobile_phone']           ?? '',
        'fax'                    => $b['fax']                    ?? '',
        'email'                  => $b['email']                  ?? '',
        'logo'                   => '',
        'footer'                 => '',
        'paypal_business_name'   => '',
        'paypal_notify_url'      => '',
        'paypal_return_url'      => '',
        'eway_customer_id'       => '',
        'paymentsgateway_api_id' => '',
        'notes'                  => '',
        'custom_field1'          => '',
        'custom_field2'          => '',
        'custom_field3'          => '',
        'custom_field4'          => '',
        'enabled'                => 1,
    ];
    if (insertBiller()) {
        $wizard_success   = true;
        $wizard_next_step = 2;
    }
}

if ($op === 'insert_sample_customer') {
    $c = $sample['si_customers'][0] ?? [];
    $_POST = [
        'name'           => $c['name']           ?? 'Sample Customer',
        'attention'      => $c['attention']      ?? '',
        'department'     => $c['department']     ?? '',
        'street_address' => $c['street_address'] ?? '',
        'street_address2'=> $c['street_address2'] ?? '',
        'city'           => $c['city']           ?? '',
        'state'          => $c['state']          ?? '',
        'zip_code'       => $c['zip_code']       ?? '',
        'country'        => $c['country']        ?? '',
        'phone'          => $c['phone']          ?? '',
        'mobile_phone'   => $c['mobile_phone']   ?? '',
        'fax'            => $c['fax']            ?? '',
        'email'          => $c['email']          ?? '',
        'notes'          => '',
        'custom_field1'  => '',
        'custom_field2'  => '',
        'custom_field3'  => '',
        'custom_field4'  => '',
        'enabled'        => 1,
    ];
    if (insertCustomer()) {
        $wizard_success   = true;
        $wizard_next_step = 3;
    }
}

if ($op === 'insert_sample_product') {
    $p = $sample['si_products'][0] ?? [];
    $_POST = [
        'description'          => $p['description'] ?? 'Sample Product',
        'unit_price'           => $p['unit_price']  ?? '0',
        'cost'                 => null,
        'reorder_level'        => null,
        'default_tax_id'       => '',
        'custom_field1'        => '',
        'custom_field2'        => '',
        'custom_field3'        => '',
        'custom_field4'        => '',
        'notes'                => '',
        'enabled'              => 1,
        'notes_as_description' => '',
        'show_description'     => '',
    ];
    if (insertProduct()) {
        $wizard_success   = true;
        $wizard_next_step = 4;
    }
}

$bladeView->assign('wizard_success',   $wizard_success);
$bladeView->assign('wizard_next_step', $wizard_next_step);
$bladeView->assign('pageActive', 'dashboard');
$bladeView->assign('active_tab', '#home');
