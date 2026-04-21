<?php
/*
 * Script: admin/domain_add.php
 *   Add a new domain - administrator role only
 *
 * License: GPL v3 or above
 */

checkLogin();

if (($auth_session->role_name ?? '') !== 'administrator') {
    exit($LANG['denied_page'] ?? 'Access Denied');
}

if (!empty($_POST['name'])) {
    include './modules/admin/domain_save.php';
}

$registrationLangList = getLanguageList();
if (is_array($registrationLangList)) {
	usort(
		$registrationLangList,
		static function ($a, $b) {
			return strcasecmp((string) ($a->name ?? ''), (string) ($b->name ?? ''));
		}
	);
}
$registrationAvailableCodes = [];
foreach ($registrationLangList ?? [] as $entry) {
	if (isset($entry->shortname) && (string) $entry->shortname !== '') {
		$registrationAvailableCodes[] = (string) $entry->shortname;
	}
}
$bladeView->assign('registrationLanguageList', $registrationLangList ?? []);
$bladeView->assign(
	'registrationLanguageDefault',
	si_pick_ui_language_from_browser($registrationAvailableCodes, 'en_US')
);

$bladeView->assign('domainSaveCsrfToken', siNonce('domain_save'));
$bladeView->assign('pageActive', 'admin');
