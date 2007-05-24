<?php
/*
 * Read language informations
 * 1. reads default-language file
 * 2. reads requested language file
 * 3. make some editing (Upper-Case etc.)
 * 
 * Not in each translated file need to be each all translations, only in the default-lang-file (english)
 */


//http_negotiate_language($langs, $result);
//print_r($result);
unset($LANG);
$LANG = getLanguageArray();

function getLanguageArray() {
	$langPath = "./lang/";
	$langFile = "/lang.php";
	$language = getenv("HTTP_ACCEPT_LANGUAGE");
	$language = "en";
	
	include($langPath."en".$langFile);
	
	if(file_exists($langPath.substr($language,0,2).$langFile)) {
		include($langPath.substr($language,0,2).$langFile);
	}
	
	if(file_exists($langPath.substr($language,0,5).$langFile)) {
		include($langPath.substr($language,0,5).$langFile);
	}
	
	return $LANG;
}

?>