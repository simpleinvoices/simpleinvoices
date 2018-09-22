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
   *      php -q lang_check.php > lang_check.txt
   *
   */


  /*
   * Get the language codes ('en', 'pt', etc) that exists in this folder.
   */

define("SI_DEBUG", false);
define("SI_AUTHOR", false);
$uline = 106;
if(SI_AUTHOR) $uline += 11;

include_once("lang_functions.php");

// Header
print str_repeat("=", $uline);
print "\n";
print sprintf("%-10s", 'Lang. Code') . " | ";
print sprintf("%-29s", 'Lang. name') . " | ";
print sprintf("%-11s", 'New strings') . " | ";
print sprintf("%15s", 'Strings in file') . " | ";
print sprintf("%16s", 'Total translated') . " | ";
print sprintf("%8s", '% Done') . " | ";
if(SI_AUTHOR) print sprintf("%10s", 'Authors');
print "\n";
print str_repeat("=", $uline);
print "\n";


// The main language. Needed to compare the % done of the other languages.
$en_lang = process_lang_file('en_US');
if (SI_DEBUG) echo "debug: en_US, $en_lang[0], $en_lang[1]\n";

// Lets process the language folders.
foreach (get_defined_langs() as $lang_code) {

  // Redo the XML part thanks to a sugestion by Nicolas Ruflin.
  // Nicolas, thanks for the PHP lesson.
  $xml = simplexml_load_file("$lang_code/info.xml");

  $tmp = explode(',', $xml->author);
  $xml->author = join(', ', $tmp);
  if (SI_DEBUG) echo "debug: $xml->name, $xml->author\n";
  
  /*
   Process the language files
  */
  
  $count = process_lang_file($lang_code);
  if (SI_DEBUG) echo "debug: $lang_code, $count[0], $count[1]\n";
  
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
  IF(SI_AUTHOR) print sprintf("%10s", trim($xml->author)) . " | ";
  print "\n";
}

// Footer
print str_repeat("-", $uline);
print "\n";

