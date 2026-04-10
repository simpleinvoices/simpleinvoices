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

$LANG = getLanguageArray();
//TODO: if (getenv("HTTP_ACCEPT_LANGUAGE") != available language) AND (config lang != en) ) {
// then use config lang
// }
//
