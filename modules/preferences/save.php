<?php
# Deal with op and add some basic sanity checking

require_once __DIR__ . '/../../include/class/siCurrencies.php';

$op = $_POST['op'] ?? null;

function _resolvePreferenceCurrency(int $domainId): array
{
    $currencyId = isset($_POST['currency_id']) && $_POST['currency_id'] !== ''
        ? (int) $_POST['currency_id'] : 0;

    if ($currencyId > 0) {
        $row = siCurrencies::getById($currencyId, $domainId);
        if ($row) {
            return [
                'currency_id'   => (int) $row['id'],
                'currency_sign' => CurrencySignHelper::forDisplay($row['currency_sign'] ?? ''),
            ];
        }
    }

    // Fall back to raw POST fields (custom currency or legacy path)
    $sign = CurrencySignHelper::forDisplay($_POST['pref_currency_sign'] ?? $_POST['p_currency_sign'] ?? '');

    $row = siCurrencies::findOrCreate($domainId, $sign, '', '');
    return [
        'currency_id'   => (int) ($row['id'] ?? 0),
        'currency_sign' => $sign,
    ];
}


$include_online_payment = '';
$iop_values = $_POST['include_online_payment'] ?? [];
foreach ($iop_values as $k => $v) {
    $include_online_payment .= $v;
    if ($k != array_key_last($iop_values)) {
        $include_online_payment .= ',';
    }
}

#insert invoice_preference
if (  $op === 'insert_preference' ) {

	$payment_term_id = isset($_POST['payment_term_id']) && $_POST['payment_term_id'] !== ''
		? (int)$_POST['payment_term_id'] : null;

	$curr = _resolvePreferenceCurrency($auth_session->domain_id);

	$showCurrencyCode = !empty($_POST['show_currency_code']) ? 1 : 0;

	$sql = "INSERT into
		".TB_PREFIX."preferences
		(
			domain_id,
			pref_description,
			pref_currency_sign,
			currency_id,
			show_currency_code,
			pref_inv_heading,
			pref_inv_wording,
			pref_inv_detail_heading,
			pref_inv_detail_line,
			pref_inv_payment_method,
			pref_inv_payment_line1_name,
			pref_inv_payment_line1_value,
			pref_inv_payment_line2_name,
			pref_inv_payment_line2_value,
			pref_enabled,
		        status,
		        locale,
		        language,
		        index_group,
			include_online_payment,
			payment_term_id,
			payment_bank_name,
			payment_reference
		)
	VALUES
		(
			:domain_id,
			:description,
			:currency_sign,
			:currency_id,
			:show_currency_code,
			:heading,
			:wording,
			:detail_heading,
			:detail_line,
			:payment_method,
			:payment_line1_name,
			:payment_line1_value,
			:payment_line2_name,
			:payment_line2_value,
			:enabled,
            :status,
            :locale,
            :language,
            :index_group,
			:include_online_payment,
			:payment_term_id,
			:payment_bank_name,
			:payment_reference
		 )";

	if (dbQuery($sql,
	  ':domain_id', $auth_session->domain_id,
	  ':description', $_POST['p_description'],
	  ':currency_sign', $curr['currency_sign'],
	  ':currency_id', $curr['currency_id'] ?: null,
	  ':show_currency_code', $showCurrencyCode,
	  ':heading', $_POST['p_inv_heading'],
	  ':wording', $_POST['p_inv_wording'],
	  ':detail_heading', $_POST['p_inv_detail_heading'],
	  ':detail_line', $_POST['p_inv_detail_line'],
	  ':payment_method', $_POST['p_inv_payment_method'],
	  ':payment_line1_name', $_POST['p_inv_payment_line1_name'],
	  ':payment_line1_value', $_POST['p_inv_payment_line1_value'],
	  ':payment_line2_name', $_POST['p_inv_payment_line2_name'],
	  ':payment_line2_value', $_POST['p_inv_payment_line2_value'],
	  ':status', $_POST['status'],
	  ':locale', $_POST['locale'],
	  ':language', $_POST['locale'],
	  ':index_group', empty($_POST['index_group']) ? lastInsertId() : $_POST['index_group']  ,
	  ':include_online_payment', $include_online_payment,
	  ':enabled', $_POST['pref_enabled'],
	  ':payment_term_id', $payment_term_id,
	  ':payment_bank_name', trim($_POST['payment_bank_name'] ?? ''),
	  ':payment_reference', trim($_POST['payment_reference'] ?? '')
	  )) {
		$saved = true;
		$new_pref_id = (int) lastInsertId();
        
        if (empty($_POST['index_group']))
        {
            $sql_update = "UPDATE
                    ".TB_PREFIX."preferences
                SET
                    index_group = :index_group
                WHERE 
                    pref_id = :pref_id
                AND domain_id = :domain_id
            ";
            dbQuery($sql_update, 
                ':index_group', $new_pref_id,
                ':pref_id', $new_pref_id,
                ':domain_id', $auth_session->domain_id
            );
		}
		invoice_denorm::refreshAllForPreference($new_pref_id, $auth_session->domain_id);
        //$display_block = $LANG['save_preference_success'];
	} ELSE {
		$saved = false;
		//$display_block =  $LANG['save_preference_failure'];
	}
	//header( 'refresh: 2; url=manage_preferences.php' );

}

#edit preference

else if (  $op === 'edit_preference' ) {

	if (isset($_POST['save_preference'])) {
		$payment_term_id = isset($_POST['payment_term_id']) && $_POST['payment_term_id'] !== ''
			? (int)$_POST['payment_term_id'] : null;

		$curr = _resolvePreferenceCurrency($auth_session->domain_id);
		$showCurrencyCode = !empty($_POST['show_currency_code']) ? 1 : 0;

		$sql = "UPDATE
				".TB_PREFIX."preferences
			SET
				pref_description = :description,
				pref_currency_sign = :currency_sign,
				currency_id = :currency_id,
				show_currency_code = :show_currency_code,
				pref_inv_heading = :heading,
				pref_inv_wording = :wording,
				pref_inv_detail_heading = :detail_heading,
				pref_inv_detail_line = :detail_line,
				pref_inv_payment_method = :payment_method,
				pref_inv_payment_line1_name = :line1_name,
				pref_inv_payment_line1_value = :line1_value,
				pref_inv_payment_line2_name = :line2_name,
				pref_inv_payment_line2_value = :line2_value,
				pref_enabled = :enabled,
				status = :status,
				locale = :locale,
				language = :language,
  		        index_group = :index_group,
  		        include_online_payment = :include_online_payment,
				payment_term_id = :payment_term_id,
				payment_bank_name = :payment_bank_name,
				payment_reference = :payment_reference
			WHERE
				pref_id = :id
			AND domain_id = :domain_id";

		if (dbQuery($sql, 
		  ':description', $_POST['pref_description'],
		  ':currency_sign', $curr['currency_sign'],
		  ':currency_id', $curr['currency_id'] ?: null,
		  ':show_currency_code', $showCurrencyCode,
		  ':heading', $_POST['pref_inv_heading'],
		  ':wording', $_POST['pref_inv_wording'],
		  ':detail_heading', $_POST['pref_inv_detail_heading'],
		  ':detail_line', $_POST['pref_inv_detail_line'],
		  ':payment_method', $_POST['pref_inv_payment_method'],
		  ':line1_name', $_POST['pref_inv_payment_line1_name'],
		  ':line1_value', $_POST['pref_inv_payment_line1_value'],
		  ':line2_name', $_POST['pref_inv_payment_line2_name'],
		  ':line2_value', $_POST['pref_inv_payment_line2_value'],
		  ':enabled', $_POST['pref_enabled'],
		  ':status', $_POST['status'],
		  ':locale', $_POST['locale'],
           	  ':language', $_POST['language'],
		  ':index_group', $_POST['index_group'],
		  ':include_online_payment', $include_online_payment,
		  ':payment_term_id', $payment_term_id,
		  ':payment_bank_name', trim($_POST['payment_bank_name'] ?? ''),
		  ':payment_reference', trim($_POST['payment_reference'] ?? ''),
		  ':id', (int)$_GET['id'],
		  ':domain_id', $auth_session->domain_id))
	    {
			$saved =true;
			invoice_denorm::refreshAllForPreference((int)$_GET['id'], $auth_session->domain_id);
		//	$display_block = $LANG['save_preference_success'];
		} else {
			$saved = false;
			//$display_block = $LANG['save_preference_failure'];
		}

		//header( 'refresh: 2; url=manage_preferences.php' );
	//	$refresh_total = "<META HTTP-EQUIV=REFRESH CONTENT=2;URL=index.php?module=preferences&view=manage>";

		}

}

$bladeView -> assign('saved',$saved); 

$bladeView -> assign('pageActive', 'preference');
$bladeView -> assign('active_tab', '#setting');
