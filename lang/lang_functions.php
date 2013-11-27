<?php
function get_defined_langs() {

	// The root path of the language files. Change if needed.
	$dir = '.';
  
	// Open a known directory, and proceed to read its contents
	if (! is_dir($dir)) {
		exit("($dir) is not a directory.");
	}

	$langs = array();


/* 
//	Implementation - Forward Compatible
*/
	try {
		foreach (new DirectoryIterator($dir) as $entry) {
			if ($entry->isDir() && !$entry->isDot() && preg_match('/^[a-z]{2}(_[A-Z]{2})?$/', $entry)) {
				if (SI_DEBUG) {
					echo "debug: language folder: $lang_dir\n";
				}
				$langs[] = $lang_dir;
			}
		}
	} catch (UnexpectedValueException $e) {
		die($e->getMessage());
	}


/* 
// Implementation - Legacy
	if ($dh = opendir($dir)) {
  
		while (($lang_dir = readdir($dh)) !== false) {
			if (! preg_match("/^[a-z]{2}$|^[a-z]{2}_[A-Z]{2}$/", $lang_dir)) {
				continue;
			}
      
			if (SI_DEBUG) echo "debug: language folder: $lang_dir\n";
			$langs[] = $lang_dir;
		}

		closedir($dh);

	} else {
		exit("Error opening folder ($dir)\n");
	}
*/

	// Sort by lang code.
	sort($langs);

	return $langs;
}


/*
 * Access one language folder and returns an array with two values: the total strings and the total translated strings.
 */

function process_lang_file($lang_code) {
  
  $lang_file = file("$lang_code/lang.php");
  
  $count = 0;
  $count_translated = 0;
  
  foreach ($lang_file as $line) {
    $line = rtrim($line);
    
    // A string line
    if (preg_match('/^\$LANG\[/', $line)) {
      $count++;
    }
	# Each LANG string in one line only, use Current Method: preg_match('/^\$LANG\[.*;\s*\/\/\s*1/', $line)
	# Accomodate multi-line string with strict line ending, use Alt Method: preg_match('/.*;\s*\/\/\s*1$/', $line)
    if (preg_match('/^\$LANG\[.*;\s*\/\/\s*1/', $line)) {
		$count_translated++;
    } else {
	// Not translated string. Just to be sure.
		if (SI_DEBUG) echo "debug: $line\n";
    }
    
  }
  
  $ret = array($count, $count_translated);
  return $ret;
}
?>
