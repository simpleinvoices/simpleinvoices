<?php
/*
 * Read language information
 * 1. Load base catalogue (en_US if present, else en_GB) so missing keys in a locale still resolve.
 * 2. Overlay the selected language file when it exists and is not already the base file.
 * 3. Extension lang files use the same base-then-overlay order.
 */


//http_negotiate_language($langs, $result);
//print_r($result);
unset($LANG);

/*if upgrading from old version then getDefaultLang wont work during install*/
if (checkTableExists(TB_PREFIX.'system_defaults'))
{
	$language = getDefaultLanguage();
} else {
 	$language = "en_GB";
}  

function getLanguageArray($lang='') {
	global $config;

	if($lang){
		$language=$lang;
	}
	else{
		global $language;
	}

	$langPath = "./lang/";
	$langFile = "/lang.php";

	$fallbackUs = $langPath . 'en_US' . $langFile;
	$fallbackGb = $langPath . 'en_GB' . $langFile;
	$baseLoaded = null;
	if (file_exists($fallbackUs)) {
		include $fallbackUs;
		$baseLoaded = 'en_US';
	} elseif (file_exists($fallbackGb)) {
		include $fallbackGb;
		$baseLoaded = 'en_GB';
	} else {
		$LANG = [];
	}

	$selectedFile = $langPath . $language . $langFile;
	if (file_exists($selectedFile) && ($baseLoaded === null || $language !== $baseLoaded)) {
		include $selectedFile;
	}

	foreach ($config->extension as $extension) {
		if ($extension->enabled != "1") {
			continue;
		}
		$extLangDir = "./extensions/{$extension->name}/lang";
		$extBaseLoaded = null;
		if (file_exists("{$extLangDir}/en_US/lang.php")) {
			include_once "{$extLangDir}/en_US/lang.php";
			$extBaseLoaded = 'en_US';
		} elseif (file_exists("{$extLangDir}/en_GB/lang.php")) {
			include_once "{$extLangDir}/en_GB/lang.php";
			$extBaseLoaded = 'en_GB';
		}
		$extSelected = "{$extLangDir}/{$language}/lang.php";
		if (file_exists($extSelected) && ($extBaseLoaded === null || $language !== $extBaseLoaded)) {
			include_once $extSelected;
		}
	}
	
	return $LANG;
}

function getLanguageList() {
	$xmlFile = "info.xml";
	$langPath = "lang/";
	$folders = null;
	
	if($handle = opendir($langPath)) {
		
		//TODO: catch ., .. and other bad folders
		for($i=0;$file = readdir($handle);$i++) {
			$folders[$i] = $file;
		}
		closedir($handle);
	}
	
	$languages = null;
	$i = 0;
	
	foreach($folders as $folder) {
		$file = $langPath.$folder."/".$xmlFile;
		if(file_exists($file)) {
			//echo $file."<br />";
			$values = simplexml_load_file($file);
			$languages[$i] = $values;
			$i++;
			//print_r($values);
			//echo $values->name;
		}
	}
	
	return $languages;
}

/**
 * Installed UI languages for account forms, sorted by display name.
 *
 * @return array<int, SimpleXMLElement>|array{}
 */
function si_get_ui_language_list_sorted(): array
{
	$list = getLanguageList();
	if (!is_array($list)) {
		return [];
	}
	usort(
		$list,
		static function ($a, $b) {
			return strcasecmp((string) ($a->name ?? ''), (string) ($b->name ?? ''));
		}
	);

	return $list;
}

/**
 * True if lang/{code}/lang.php exists (validated UI language folder name).
 */
function si_lang_folder_exists(string $code): bool
{
	$code = trim($code);
	if ($code === '' || !preg_match('/^[a-zA-Z0-9_]+$/', $code)) {
		return false;
	}

	return is_file(__DIR__ . '/../lang/' . $code . '/lang.php');
}

/**
 * Language chosen at public registration - must exist on disk; fallback en_GB.
 */
function si_normalize_registration_language(?string $code): string
{
	$c = trim((string) $code);

	return ($c !== '' && si_lang_folder_exists($c)) ? $c : 'en_GB';
}

/**
 * Parsed HTTP Accept-Language entries, highest quality first.
 *
 * @return array<int, array{tag: string, q: float}>
 */
function si_parse_accept_language_entries(): array
{
	$raw = $_SERVER['HTTP_ACCEPT_LANGUAGE'] ?? '';
	$raw = is_string($raw) ? trim($raw) : '';
	if ($raw === '') {
		return [];
	}
	$out = [];
	foreach (explode(',', $raw) as $piece) {
		$piece = trim($piece);
		if ($piece === '') {
			continue;
		}
		$q       = 1.0;
		$langTag = $piece;
		if (preg_match('/;\s*q\s*=\s*([0-9.]+)/i', $piece, $qm)) {
			$q = (float) $qm[1];
			$langTag = trim(preg_replace('/;\s*q\s*=\s*[0-9.]+/i', '', $piece));
		}
		$langTag = trim((string) preg_replace('/;.*$/', '', $langTag));
		if ($langTag !== '') {
			$out[] = ['tag' => $langTag, 'q' => $q];
		}
	}
	usort(
		$out,
		static function ($a, $b) {
			if ($a['q'] === $b['q']) {
				return 0;
			}

			return ($a['q'] < $b['q']) ? 1 : -1;
		}
	);

	return $out;
}

/**
 * Map one Accept-Language tag to possible locale folder names (e.g. de-DE → de_DE, de).
 *
 * @return list<string>
 */
function si_browser_tag_to_locale_candidates(string $tag): array
{
	$tag = trim($tag);
	if ($tag === '') {
		return [];
	}
	$out = [];
	if (preg_match('/^([A-Za-z]{2,3})(?:[-_]([A-Za-z]{2}))?/i', $tag, $m)) {
		$lang   = strtolower($m[1]);
		$region = isset($m[2]) ? strtoupper($m[2]) : null;
		if ($region !== null && $region !== '') {
			$out[] = $lang . '_' . $region;
		}
		$out[] = $lang;
	}

	return array_values(array_unique($out));
}

/**
 * Choose an installed UI language from the browser's Accept-Language header.
 * Falls back to $fallback (default en_US) when installed, then en_GB, then the first available code.
 *
 * @param list<string> $availableCodes Shortnames from getLanguageList() (info.xml)
 */
function si_pick_ui_language_from_browser(array $availableCodes, string $fallback = 'en_US'): string
{
	$codes = array_values(array_filter(array_unique(array_map('trim', $availableCodes))));
	if ($codes === []) {
		return si_normalize_registration_language($fallback);
	}
	$map = [];
	foreach ($codes as $c) {
		$map[strtolower($c)] = $c;
	}
	foreach (si_parse_accept_language_entries() as $entry) {
		foreach (si_browser_tag_to_locale_candidates($entry['tag']) as $cand) {
			$key = strtolower($cand);
			if (isset($map[$key])) {
				return $map[$key];
			}
		}
	}
	// Bare "en" without region: prefer en_US, then en_GB, then any en_*.
	foreach (si_parse_accept_language_entries() as $entry) {
		$t = strtolower(trim($entry['tag']));
		if (preg_match('/^[a-z]{2}$/', $t) && $t === 'en') {
			foreach (['en_US', 'en_GB'] as $try) {
				if (isset($map[strtolower($try)])) {
					return $map[strtolower($try)];
				}
			}
			foreach ($codes as $c) {
				if (stripos($c, 'en_') === 0) {
					return $c;
				}
			}
		}
	}
	if (isset($map[strtolower($fallback)])) {
		return $map[strtolower($fallback)];
	}
	foreach (['en_US', 'en_GB'] as $try) {
		if (isset($map[strtolower($try)])) {
			return $map[strtolower($try)];
		}
	}

	return $codes[0];
}

/**
 * Return the locale-appropriate currency info from preset groups.
 * Maps locale codes (en_GB, de_DE, etc.) to currency presets used by
 * CurrencySignHelper so the first-run wizard starts with a sensible default.
 *
 * @return array{sign: string, code: string, position: string}|null
 */
function si_locale_to_currency_info(string $locale): ?array
{
	$locale = strtolower(trim($locale));

	// Locale → ISO 4217 currency code mapping
	$localeCurrencyMap = [
		'en_us' => 'USD', 'es_mx' => 'MXN',
		'en_au' => 'AUD', 'en_gb' => 'GBP',
		'de_de' => 'EUR', 'fr_fr' => 'EUR', 'es_es' => 'EUR', 'it_it' => 'EUR',
		'nl_nl' => 'EUR', 		'pt_br' => 'BRL', 'pt_pt' => 'EUR', 'fi_fi' => 'EUR', 'el_gr' => 'EUR',
		'sk_sk' => 'EUR', 'sl_si' => 'EUR', 'et_ee' => 'EUR', 'lv_lv' => 'EUR',
		'mt_mt' => 'EUR', 'hr_hr' => 'EUR', 'ca_es' => 'EUR', 'eu_es' => 'EUR',
		'gl_es' => 'EUR', 'oc_es' => 'EUR', 'sq_al' => 'EUR',
		'de_ch' => 'CHF',
		'pl_pl' => 'PLN', 'cs_cz' => 'CZK', 'hu_hu' => 'HUF', 'ro_ro' => 'RON',
		'bg_bg' => 'BGN', 'tr_tr' => 'TRY', 'ru_ru' => 'RUB',
		'sv_se' => 'SEK', 'nb_no' => 'NOK', 'da_dk' => 'DKK',
		'id_id' => 'IDR', 'vi_vn' => 'VND',
		'zh_cn' => 'CNY', 'zh_hk' => 'HKD', 'zh_tw' => 'TWD',
		'ja_jp' => 'JPY', 'ko_kr' => 'KRW',
		'af_za' => 'ZAR', 'ar_sa' => 'SAR', 'sr_rs' => 'RSD',
		'ta_in' => 'INR', 'hi_in' => 'INR',
	];

	$code = $localeCurrencyMap[$locale] ?? null;
	if ($code !== null) {
		// CurrencySignHelper may not be loaded yet at language.php include time.
		// Callers are expected to require/include it before invoking this function.
		if (class_exists('CurrencySignHelper')) {
			foreach (CurrencySignHelper::getPresetGroups() as $group) {
				foreach ($group['presets'] as $p) {
					if (($p['code'] ?? '') === $code) {
						return [
							'sign'     => $p['value'],
							'code'     => $p['code'],
							'position' => $p['position']
								?? CurrencySignHelper::defaultPositionForSign($p['value'], $p['code']),
						];
					}
				}
			}
		}
	}

	return null;
}

/**
 * Return the default tax ID labels (primary and secondary) for a given locale.
 * e.g. en_gb → ['primary' => 'VAT Registration Number', 'secondary' => 'CRN (Company Registration Number)']
 *
 * @return array{primary: string, secondary: string}|null
 */
function si_locale_to_tax_id_label(string $locale): ?array
{
	$locale = strtolower(trim($locale));

	// Locale → [primary label, secondary label]
	$map = [
		'en_us' => ['EIN', 'State Tax ID'],
		'en_gb' => ['VAT Registration Number', 'CRN (Company Registration Number)'],
		'en_au' => ['ABN', 'ACN'],
		'en_ca' => ['Business Number', 'GST/HST Number'],
		'de_de' => ['USt-IdNr.', 'Handelsregisternummer'],
		'de_ch' => ['UID', 'Handelsregisternummer'],
		'fr_fr' => ['Numéro TVA', 'SIRET'],
		'es_es' => ['NIF', 'CIF'],
		'it_it' => ['Partita IVA', 'Codice Fiscale'],
		'nl_nl' => ['BTW-nummer', 'KVK-nummer'],
		'pt_br' => ['CNPJ', 'Inscrição Estadual'],
		'pt_pt' => ['NIF', 'NIPC'],
		'pl_pl' => ['NIP', 'REGON'],
		'sv_se' => ['Momsreg.nr', 'Organisationsnummer'],
		'da_dk' => ['CVR', 'SE-nummer'],
		'nb_no' => ['Org.nr.', 'MVA'],
		'fi_fi' => ['Y-tunnus', 'ALV-numero'],
		'cs_cz' => ['DIČ', 'IČO'],
		'hu_hu' => ['Adószám', 'Cégjegyzékszám'],
		'ro_ro' => ['CIF', 'Reg. Com.'],
		'bg_bg' => ['ЕИК', 'ДДС номер'],
		'ru_ru' => ['ИНН', 'КПП'],
		'tr_tr' => ['Vergi No', 'Ticaret Sicil No'],
		'ja_jp' => ['法人番号', '適格請求書発行事業者登録番号'],
		'ko_kr' => ['사업자등록번호', '법인등록번호'],
		'zh_cn' => ['统一社会信用代码', '税务登记号'],
		'zh_hk' => ['商業登記號碼', 'CR Number'],
		'zh_tw' => ['統一編號', '營利事業登記編號'],
		'id_id' => ['NPWP', 'NIB'],
		'vi_vn' => ['MST', 'GPKD'],
		'af_za' => ['VAT Number', 'Company Registration Number'],
		'ar_sa' => ['VAT Registration Number', 'Commercial Registration'],
		'hi_in' => ['GSTIN', 'PAN'],
		'ta_in' => ['GSTIN', 'PAN'],
		'sk_sk' => ['IČ DPH', 'IČO'],
		'sl_si' => ['Davčna št.', 'Matična št.'],
		'et_ee' => ['KMKR', 'Registrikood'],
		'lv_lv' => ['PVN Nr.', 'Reģ. Nr.'],
		'mt_mt' => ['VAT Number', 'Company Registration Number'],
		'hr_hr' => ['OIB', 'MBS'],
		'ca_es' => ['NIF', 'CIF'],
		'eu_es' => ['IFK', 'IFZ'],
		'gl_es' => ['NIF', 'CIF'],
		'oc_es' => ['NIF', 'CIF'],
		'sq_al' => ['NUIS', 'NIPT'],
		'sr_rs' => ['PIB', 'Matični broj'],
		'el_gr' => ['ΑΦΜ', 'ΓΕΜΗ'],
		'es_mx' => ['RFC', 'Registro Público de Comercio'],
	];

	$pair = $map[$locale] ?? null;
	if ($pair === null) {
		return null;
	}

	return ['primary' => $pair[0], 'secondary' => $pair[1]];
}

$LANG = getLanguageArray();
//TODO: if (getenv("HTTP_ACCEPT_LANGUAGE") != available language) AND (config lang != en) ) {
// then use config lang
// }
//
