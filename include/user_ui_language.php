<?php
/**
 * Apply logged-in user's preferred UI language (si_user.preferred_language) when set.
 * Domain default from system_defaults applies when the column is empty or NULL.
 */

/**
 * Parse preferred_language from POST (user add/edit forms). Empty → null (use domain default).
 */
function si_user_preferred_language_from_post(): ?string
{
	if (!isset($_POST['preferred_language'])) {
		return null;
	}
	$v = trim((string) $_POST['preferred_language']);
	if ($v === '') {
		return null;
	}

	return si_lang_folder_exists($v) ? $v : null;
}

function si_apply_user_ui_language(): void
{
	global $language, $LANG, $config, $auth_session;

	if ((int) ($config->authentication->enabled ?? 0) !== 1) {
		return;
	}
	if (!empty($auth_session->fake_auth)) {
		return;
	}
	$uid = isset($auth_session->id) ? (int) $auth_session->id : 0;
	if ($uid < 1) {
		return;
	}
	if (!checkFieldExists(TB_PREFIX . 'user', 'preferred_language')) {
		return;
	}

	$pref = null;
	if (property_exists($auth_session, 'ui_language')) {
		$pref = trim((string) $auth_session->ui_language);
	} else {
		$domainId = (int) ($auth_session->domain_id ?? 0);
		$sth      = dbQuery(
			'SELECT preferred_language FROM ' . TB_PREFIX . 'user WHERE id = :id AND domain_id = :d LIMIT 1',
			':id',
			$uid,
			':d',
			$domainId
		);
		$row = $sth ? $sth->fetch(PDO::FETCH_ASSOC) : false;
		$pref = ($row && isset($row['preferred_language'])) ? trim((string) $row['preferred_language']) : '';
		$auth_session->ui_language = $pref;
	}

	if ($pref === '') {
		siLocal::setUserLocale(null);
		return;
	}
	if (!si_lang_folder_exists($pref)) {
		siLocal::setUserLocale(null);
		return;
	}

	siLocal::setUserLocale($pref);

	if ($pref === $language) {
		return;
	}
	$language = $pref;
	$LANG     = getLanguageArray($language);
}
