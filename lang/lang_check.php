<?php
  /*
   * Script: lang_check.php
   *       Calculate languages translation status
   *
   * Authors:
   *        Rui Gouveia
   *
   * Last edited:
   *        2007-11-01, 2008-01-26, 2008-05-12, 2008-08-28
   *
   * License:
   *        GPL v3
   * 
   * Usage:
   *      To execute the lang check run the command below 
   *      and view the lang_check.html file in your browser
   *
   *      php -q lang_check.php > lang_check.html
   *
   */


  /*
   * Get the language codes ('en', 'pt', etc) that exists in this folder.
   */

function get_defined_langs() {

  // The root path of the language files. Change if needed.
  $dir = '.';
  
  // Open a known directory, and proceed to read its contents
  if (! is_dir($dir)) {
    exit("($dir) is not a directory.");
  }

  $langs = array();

  if ($dh = opendir($dir)) {
    while (($lang_dir = readdir($dh)) !== false) {
      if (! ereg("^[a-z]{2,3}$|^[a-z]{2,3}-[a-z]{2,3}$", $lang_dir)) {
	continue;
      }
      
      //echo "debug: language folder: $lang_dir\n";
      $langs[] = $lang_dir;
    }
  
    closedir($dh);
  } else {
    exit("Error opening folder ($dir)\n");
  }

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
      
      if (preg_match('/^\$LANG\[.*;\s*\/\/\s*1/', $line)) {
	$count_translated++;
      } else {
	// Not translated string. Just to be sure.
	//echo "debug: $line\n";
      }
    }
  }
  
  $ret = array($count, $count_translated);
  return $ret;
  }


// Header
print str_repeat("=", 106);
print "\n";
print sprintf("%-10s", 'Lang. Code') . " | ";
print sprintf("%-29s", 'Lang. name') . " | ";
print sprintf("%-11s", 'New strings') . " | ";
print sprintf("%15s", 'Strings in file') . " | ";
print sprintf("%16s", 'Total translated') . " | ";
print sprintf("%8s", '% Done') . " | ";
//print sprintf("%10s", 'Authors');
print "\n";
print str_repeat("=", 106);
print "\n";


// The main language. Needed to compare the % done of the other languages.
$en_lang = process_lang_file('en_GB');
//echo "debug: en-gb, $en_lang[0], $en_lang[1]\n";

// Lets process the language folders.
foreach (get_defined_langs() as $lang_code) {

  // Redo the XML part thanks to a sugestion by Nicolas Ruflin.
  // Nicolas, thanks for the PHP lesson.
  $xml = simplexml_load_file("$lang_code/info.xml");

  $tmp = split(',', $xml->author);
  $xml->author = join(', ', $tmp);
  //echo "debug: $xml->name, $xml->author\n";
  
  /*
   Process the language files
  */
  
  $count = process_lang_file($lang_code);
  //echo "debug: $lang_code, $count[0], $count[1]\n";
  
  if ($count[0] == 0) {
    $percentage = 'N/A';
  } else {
    // The percentage is compared with the total strings of the english language.
    $percentage = number_format(round(($count[1] / $en_lang[0]) * 100, 2), 2) . '%';
  }

  // New strings available?
  if ($en_lang[0] - $count[0] == 0) {
    $new_strings = 'All Done';
  } else {
    $new_strings = $en_lang[0] - $count[0];
  }



  print sprintf("%-10s", $lang_code) . " | ";
  print sprintf("%-29s", utf8_decode($xml->name." (".$xml->englishname.")")) . " | ";
  print sprintf("%-11s", $new_strings) . " | ";
  print sprintf("%15s", $count[0]) . " | ";
  print sprintf("%16s", $count[1]) . " | ";
  print sprintf("%8s", $percentage) . " | ";
  //print sprintf("%10s", $xml->author) . " | ";
  print "\n";
}

// Footer
print str_repeat("-", 106);
print "\n";
?>
