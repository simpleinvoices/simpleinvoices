<?php
  /*
   * Script: lang_check.php
   *       Calculate languages tanslation status
   *
   * Authors:
   *        Rui Gouveia
   *
   * Last edited:
   *        2007-11-01
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
<th>Lang.</th>
<th>Language name</th>
<th>Tot. strings</th>
<th>Tot. translated</th>
<th>% Done</th>
<!--th>Authors</th-->
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

    //echo "debug: $lang_dir\n";
    
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

// Lets process the language folders.

// Sort by lang code.
sort($langs);

foreach ($langs as $lang_dir) {

  $info_file = file_get_contents("$lang_dir/info.xml");
  
  // I'm not an expert in XML, so I'll do this the sysadmin way...
  
  $lang_code = $lang_dir;
  
  ereg("<name>(.*)</name>", $info_file, $regs);
  $lang_name = $regs[1];
  
  ereg("<author>(.*)</author>", $info_file, $regs);
  $lang_authors = $regs[1];
  $tmp = split(',', $lang_authors);
  $lang_authors = join(',<br>', $tmp);
  
  //echo "debug: $lang_name, $lang_authors\n";
  
  $lang_file = file("$lang_dir/lang.php");
  
  // Process the language files
  $count = 0;
  $count_translated = 0;
  
  foreach ($lang_file as $line) {
    $line = rtrim($line);
    
    //echo "debug: $line";

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

    //echo "debug: $count, $count_translated, '$line'\n";
  }

  if ($count == 0) {
    $percentage = 'N/A';
  } else {
    $percentage = round(($count_translated / $count) * 100, 2) . '%';

    if ($percentage == '100%') {
      $percentage = "<strong>$percentage</strong>";
    }
  }

  print "
<tr>
<td>$lang_code</td>
<td>$lang_name</td>
<td align=\"center\">$count</td>
<td align=\"center\">$count_translated</td>
<td align=\"right\">$percentage</td>
<!--td>$lang_authors</td-->
</tr>
";

}
?>

</table>
