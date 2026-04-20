<?php
/*
 * Installation-wide app branding (si_global_config). Administrator role only.
 */

checkLogin();

if (($auth_session->role_name ?? '') !== 'administrator') {
    exit($LANG['denied_page'] ?? 'Access Denied');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && (string) ($_POST['op'] ?? '') === 'save_global_app_settings') {
    if (!checkTableExists(TB_PREFIX . 'global_config')) {
        exit($LANG['admin_app_settings_table_missing'] ?? 'The global app settings table is not installed. Apply SQL patches first.');
    }
    saveGlobalAppSettingsFromPost($_POST);
    global $siUrl;
    $base = rtrim((string) ($siUrl ?? ''), '/');
    $path = 'index.php?module=admin&view=app_settings&saved=1';
    $dest = $base !== '' ? $base . '/' . $path : $path;
    // index.php may have sent the layout header into this output buffer before this module runs;
    // drop buffered output so Location can be sent (see modules/admin/domain_save.php).
    while (ob_get_level() > 0) {
        ob_end_clean();
    }
    if (!headers_sent()) {
        header('Location: ' . $dest, true, 303);
        exit;
    }
    // Extremely rare fallback: show success without redirect
    $appSettingsSaved = true;
} else {
    $appSettingsSaved = isset($_GET['saved']) && (string) ($_GET['saved'] ?? '') === '1';
}

$settings = getGlobalAppSettingsForAdminForm();

$footerLinkGroups = [];
for ($n = 1; $n <= 4; $n++) {
    $footerLinkGroups[] = [
        'n' => $n,
        'label' => $settings['app_footer_link' . $n . '_label'] ?? '',
        'url' => $settings['app_footer_link' . $n . '_url'] ?? '',
    ];
}

$bladeView->assign('globalApp', $settings);
$bladeView->assign('footerLinkGroups', $footerLinkGroups);
$bladeView->assign('appSettingsSaved', $appSettingsSaved);
$bladeView->assign('pageActive', 'admin');
