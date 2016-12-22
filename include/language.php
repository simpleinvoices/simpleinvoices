<?php
/*
 * Read language informations
 * 1. reads default-language file
 * 2. reads requested language file
 * 3. make some editing (Upper-Case etc.)
 * Not in each translated file need to be each all translations, only in the default-lang-file (english)
 */
global $LANG, $databaseBuilt, $zendDb;
unset($LANG);
$LANG = array();

if ($databaseBuilt) {
    $tables = $zendDb->listTables(); // TEST: print db tables
    // if upgrading from old version then getDefaultLang wont work during install
    if (in_array(TB_PREFIX . 'system_defaults', $tables)) {
        $language = getDefaultLanguage();
    } else {
        $language = "en_GB";
    }
} else {
    $language = "en_GB";
}

function getLanguageArray($lang = '') {
    global $ext_names, $LANG;

    if (!empty($lang)) {
        $language = $lang;
    } else {
        global $language;
    }

    $langPath = "./lang/";
    $langFile = "/lang.php";
    include ($langPath . "en_GB" . $langFile);
    if (file_exists($langPath . $language . $langFile)) {
        include ($langPath . $language . $langFile);
    }

    foreach ($ext_names as $ext_name) {
        if (file_exists("./extensions/$ext_name/lang/$language/lang.php")) {
            include_once ("./extensions/$ext_name/lang/$language/lang.php");
        }
    }

    return $LANG;
}

function getLanguageList() {
    $xmlFile = "info.xml";
    $langPath = "lang/";
    $folders = null;

    if ($handle = opendir($langPath)) {
        // TODO: catch ., .. and other bad folders
        for ($i = 0; $file = readdir($handle); $i++) {
            $folders[$i] = $file;
        }
        closedir($handle);
    }

    $languages = null;
    $i = 0;

    foreach ($folders as $folder) {
        $file = $langPath . $folder . "/" . $xmlFile;
        if (file_exists($file)) {
            $values = simplexml_load_file($file);
            $languages[$i] = $values;
            $i++;
        }
    }

    return $languages;
}

$LANG = getLanguageArray();

//TODO: if (getenv("HTTP_ACCEPT_LANGUAGE") != available language) && (config lang != en) ) {
// then use config lang
// }
