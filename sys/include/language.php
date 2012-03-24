<?php
/*
 * Read language informations
 * 1. reads default-language file
 * 2. reads requested language file
 * 3. make some editing (Upper-Case etc.)
 * 
 * Not in each translated file need to be each all translations, only in the default-lang-file (english)
 */

global $cust_language;  
//http_negotiate_language($langs, $result);
//print_r($result);
unset($LANG);


$tables = Zend_Db_Table::getDefaultAdapter()->listTables(); //TEST: print db tables 


/*if upgrading from old version then getDefaultLang wont work during install*/
if ($cust_language != '') {
    $language = $cust_language;
} 
else 
{
  if(in_array(TB_PREFIX.'system_defaults',$tables))
  {
      $system_defaults = new SimpleInvoices_Db_Table_SystemDefaults();
      $language = $system_defaults->findByName('language');
      if ($language === NULL) $language = "en_GB";
  } else {
      $language = "en_GB";
  }  
}  

function getLanguageArray() {
	global $language;
	global $config;

	$langPath =  "sys/lang/";
	$langFile = "/lang.php";
	//$getLanguage = getenv("HTTP_ACCEPT_LANGUAGE");
    //$system_defaults = new SimpleInvoices_Db_Table_SystemDefaults();
    //$language = $system_defaults->findByName('language');

	//include english as default - so if the selected lang doesnt have the required lang then it still loads
	include($langPath."en_GB".$langFile);

	include($langPath.$language.$langFile);

	foreach($config->extension as $extension)
	{
		/*
		* If extension is enabled then continue and include the requested file for that extension if it exists
		*/	
		if($extension->enabled == "1")
		{
			//echo "Enabled:".$value['name']."<br><br>";
			$name = $extension->get('name');
			$file = "./sys/extensions/{$name}/lang/{$language}{$langFile}";
			if(file_exists($file))
			{
				include_once($file);
			}
		}
	}
	
	return $LANG;
}

function getLanguageList($path) {
	$xmlFile = "info.xml";
///	$langPath = "lang/";  aducom 
      
    if(empty($path)){
        $langPath =  "sys/lang/";
    } else {

        $langPath =  $path;
    }
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
