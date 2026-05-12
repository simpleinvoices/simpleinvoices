<?php
# Deal with op and add some basic sanity checking

require_once __DIR__ . '/../../include/class/siCurrencies.php';

$op = $_POST['op'] ?? null;

function _resolvePreferenceCurrency(int $domainId): int
{
    $currencyId = isset($_POST['currency_id']) && $_POST['currency_id'] !== ''
        ? (int) $_POST['currency_id'] : 0;

    if ($currencyId > 0) {
        $row = siCurrencies::getById($currencyId, $domainId);
        if ($row) {
            return (int) $row['id'];
        }
    }

    return 0;
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

	$sql = "INSERT into
		".TB_PREFIX."preferences
		(
			domain_id,
			pref_description,
			currency_id,
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
		pref_inv_payment_line0_name,
		pref_inv_payment_line0_value,
		pref_inv_payment_line3_name,
		pref_inv_payment_line3_value,
		pref_inv_payment_line4_name,
		pref_inv_payment_line4_value,
		pref_inv_payment_line5_name,
		pref_inv_payment_line5_value
	)
VALUES
	(
		:domain_id,
		:description,
		:currency_id,
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
		:payment_line0_name,
		:payment_line0_value,
		:payment_line3_name,
		:payment_line3_value,
		:payment_line4_name,
		:payment_line4_value,
		:payment_line5_name,
		:payment_line5_value
	 )";

	if (dbQuery($sql,
	  ':domain_id', $auth_session->domain_id,
	  ':description', $_POST['p_description'],
	  ':currency_id', $curr ?: null,
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
	  ':payment_line0_name', trim($_POST['pref_inv_payment_line0_name'] ?? ''),
	  ':payment_line0_value', trim($_POST['pref_inv_payment_line0_value'] ?? ''),
	  ':payment_line3_name', trim($_POST['pref_inv_payment_line3_name'] ?? ''),
	  ':payment_line3_value', trim($_POST['pref_inv_payment_line3_value'] ?? ''),
	  ':payment_line4_name', trim($_POST['pref_inv_payment_line4_name'] ?? ''),
	  ':payment_line4_value', trim($_POST['pref_inv_payment_line4_value'] ?? ''),
	  ':payment_line5_name', trim($_POST['pref_inv_payment_line5_name'] ?? ''),
	  ':payment_line5_value', trim($_POST['pref_inv_payment_line5_value'] ?? '')
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

		$sql = "UPDATE
				".TB_PREFIX."preferences
			SET
				pref_description = :description,
				currency_id = :currency_id,
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
			pref_inv_payment_line0_name = :payment_line0_name,
			pref_inv_payment_line0_value = :payment_line0_value,
			pref_inv_payment_line3_name = :payment_line3_name,
			pref_inv_payment_line3_value = :payment_line3_value,
			pref_inv_payment_line4_name = :payment_line4_name,
			pref_inv_payment_line4_value = :payment_line4_value,
			pref_inv_payment_line5_name = :payment_line5_name,
			pref_inv_payment_line5_value = :payment_line5_value
			WHERE
				pref_id = :id
			AND domain_id = :domain_id";

		if (dbQuery($sql, 
		  ':description', $_POST['pref_description'],
		  ':currency_id', $curr ?: null,
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
	  ':payment_line0_name', trim($_POST['pref_inv_payment_line0_name'] ?? ''),
	  ':payment_line0_value', trim($_POST['pref_inv_payment_line0_value'] ?? ''),
	  ':payment_line3_name', trim($_POST['pref_inv_payment_line3_name'] ?? ''),
	  ':payment_line3_value', trim($_POST['pref_inv_payment_line3_value'] ?? ''),
	  ':payment_line4_name', trim($_POST['pref_inv_payment_line4_name'] ?? ''),
	  ':payment_line4_value', trim($_POST['pref_inv_payment_line4_value'] ?? ''),
	  ':payment_line5_name', trim($_POST['pref_inv_payment_line5_name'] ?? ''),
	  ':payment_line5_value', trim($_POST['pref_inv_payment_line5_value'] ?? ''),
	  ':id', (int)$_GET['id'],
		  ':domain_id', $auth_session->domain_id))
	    {
			invoice_denorm::refreshAllForPreference((int)$_GET['id'], $auth_session->domain_id);

			$start_err = null;
			$start_num = trim($_POST['set_starting_invoice_number'] ?? '');
			if ($start_num !== '' && ctype_digit($start_num)) {
				$new_start = (int) $start_num;
				$index_group = (int) $_POST['index_group'];

				$sql_max = "SELECT MAX(index_id) AS max_idx
					FROM " . TB_PREFIX . "invoices
					WHERE domain_id = :domain_id AND preference_id IN (
						SELECT pref_id FROM " . TB_PREFIX . "preferences
						WHERE index_group = :index_group AND domain_id = :domain_id2
					)";
				$sth = dbQuery($sql_max,
					':domain_id', $auth_session->domain_id,
					':index_group', $index_group,
					':domain_id2', $auth_session->domain_id);
				$row = $sth->fetch();
				$max_existing = (int) ($row['max_idx'] ?? 0);

				$current_next = index::next('invoice', $index_group, $auth_session->domain_id);

				if ($new_start <= $max_existing) {
					$start_err = "Starting number must be greater than the highest existing invoice number ($max_existing) in this numbering group.";
				} elseif ($new_start < 1) {
					$start_err = "Starting number must be at least 1.";
				} else {
					$set_id = $new_start - 1;
					$sql_idx = "SELECT id FROM " . TB_PREFIX . "index
						WHERE node = 'invoice' AND sub_node = :sub_node
						AND domain_id = :domain_id";
					$sth_idx = dbQuery($sql_idx,
						':sub_node', $index_group,
						':domain_id', $auth_session->domain_id);
					$exists = $sth_idx->fetch();

					if ($exists) {
						dbQuery(
							"UPDATE " . TB_PREFIX . "index SET id = :id
							WHERE node = 'invoice' AND sub_node = :sub_node AND domain_id = :domain_id",
							':id', $set_id,
							':sub_node', $index_group,
							':domain_id', $auth_session->domain_id
						);
					} else {
						dbQuery(
							"INSERT INTO " . TB_PREFIX . "index (id, node, sub_node, sub_node_2, domain_id)
							VALUES (:id, 'invoice', :sub_node, 0, :domain_id)",
							':id', $set_id,
							':sub_node', $index_group,
							':domain_id', $auth_session->domain_id
						);
					}
				}
			}

			$params = '&saved=1';
			if ($start_err !== null) {
				$_SESSION['si_starting_number_error'] = $start_err;
				$params .= '&start_err=1';
			}
			header('Location: index.php?module=preferences&view=details&id=' . (int)$_GET['id'] . '&action=edit' . $params);
			exit;
		} else {
			$saved = false;
		}

		}
	}

$bladeView -> assign('saved',$saved); 

$bladeView -> assign('pageActive', 'preference');
$bladeView -> assign('active_tab', '#setting');
