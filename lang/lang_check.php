<?php
  /*
   * Script: lang_check.php
   *       Calculate languages tanslation status
   *
   * Authors:
   *        Rui Gouveia
   *
   * Last edited:
   *        2007-11-01, 2008-01-26
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
?>

<style>
td {
        border-style: none none none none;
        border-color: white white white white;
        background-color: #F5F5F5;
        -moz-border-radius: 0px 0px 0px 0px;
        font-style: normal;
        font-weight: normal;
        text-decoration: none;
}
th {
        border-style: none none none none;
        border-color: white white white white;
        background-color: #F8F8F8;
        -moz-border-radius: 0px 0px 0px 0px;
        font-style: normal;
        font-weight: strong;
        text-decoration: none;
}
</style>

<table>
<tr>
<th valign="top">Language<br>code</th>
<th valign="top">Language<br>name</th>
<th valign="top">New strings<br>available</th>
<th valign="top">Total strings</th>
<th valign="top">Total strings<br>translated</th>
<th valign="top">% Done</th>
<!--th valign="top">Authors</th-->
</tr>

<?php
  // The root path of the language files.
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

$en_lang = process_lang_file('en-gb');
//echo "debug: en-gb, $en_lang[0], $en_lang[1]\n";


// Lets process the language folders.
foreach ($langs as $lang_code) {

  // Redo the XML part thanks to a sugestion by Nicolas Ruflin.
  // Nicolas, thanks for the PHP lesson.
  $xml = simplexml_load_file("$lang_code/info.xml");

  $tmp = split(',', $xml->author);
  $xml->author = join(',<br>', $tmp);
  //echo "debug: $xml->name, $xml->author\n";
  
  /*
   Process the language files
  */
  
  $count = process_lang_file($lang_code);
  //echo "debug: $lang_code, $count[0], $count[1]\n";
  
  if ($count[0] == 0) {
    $percentage = 'N/A';
  } else {
    $percentage = number_format(round(($count[1] / $count[0]) * 100, 2), 2) . '%';

    if ($percentage == '100.00%') {
      $percentage = "<strong>$percentage</strong>";
    }
  }

  // New strings available?
  if ($en_lang[0] - $count[0] == 0) {
    $new_strings = 'All Done';
  } else {
    $new_strings = $en_lang[0] - $count[0];

    if ($new_strings > 0) {
      $new_strings = "<b>$new_strings</b>";
    } else {
      $new_strings = "<font color='red'>? $new_strings ?</font>";
    }
  }

  print "
<tr>
<td>$lang_code</td>
<td>$xml->name</td>
<td align=\"center\">$new_strings</td>
<td align=\"center\">$count[0]</td>
<td align=\"center\">$count[1]</td>
<td align=\"right\">$percentage</td>
<!--td>$xml->author</td-->
</tr>
";

}
?>

</table>
