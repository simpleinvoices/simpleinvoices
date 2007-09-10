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

function getLanguageArray() {
	global $language;
	$langPath = "./lang/";
	$langFile = "/lang.php";
	//$getLanguage = getenv("HTTP_ACCEPT_LANGUAGE");
	$getLanguage = $language;
	
	//include($langPath."en".$langFile);
	include($langPath.$language.$langFile);

	/*
	if(file_exists($langPath.substr($getLanguage,0,2).$langFile)) {
		include($langPath.substr($getLanguage,0,2).$langFile);
	}
	
	if(file_exists($langPath.substr($getLanguage,0,5).$langFile)) {
		include($langPath.substr($getLanguage,0,5).$langFile);
	}
	*/
	
	return $LANG;
}

$LANG = getLanguageArray();
//TODO: if (getenv("HTTP_ACCEPT_LANGUAGE") != available language) AND (config lang != en) ) {
// then use config lang
// }
//
?>
