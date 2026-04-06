<?php

$menu = false;
$redirect_after_install = null;
$install_error = false;

if (isset($_POST['op']) && $_POST['op'] === 'install_database') {
	$install_successful = true;

	if (checkTableExists() == false) {
		$import = new import();
		$import->file = "./databases/mysql/structure.sql";
		$import->pattern_find = array('si_','DOMAIN-ID','LOCALE','LANGUAGE');
		$import->pattern_replace = array(TB_PREFIX,'1','en_GB','en_GB');
		$install_successful = (bool) $db->query($import->collate());
	}

	if ($install_successful && checkTableExists(TB_PREFIX."customers") == true) {
		$need_essential = !isset($install_data_exists) || $install_data_exists == false;
		if ($need_essential) {
			$importjson = new importjson();
			$importjson->file = "./databases/json/essential_data.json";
			$importjson->pattern_find = array('si_','DOMAIN-ID','LOCALE','LANGUAGE');
			$importjson->pattern_replace = array(TB_PREFIX,'1','en_GB','en_GB');
			$install_successful = (bool) $db->query($importjson->collate());
		}
	}

	if ($install_successful && checkTableExists(TB_PREFIX."biller") == true && checkDataExists() == true) {
		$redirect_after_install = 'index.php?module=index&view=index';
	} elseif (isset($_POST['op'])) {
		$install_error = true;
	}
}

$smarty->assign('redirect_after_install', $redirect_after_install);
$smarty->assign('install_error', $install_error);
?>
