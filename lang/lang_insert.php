<?php
/* **************
//	Purpose: To insert untranslated strings into language file
//			 and arrange the non title_* keys in alphabetical order
//	Author : Ap.Muthu
//	Release: 2013-10-18
//	Updated: 2013-10-19
//
//	Usage  : http://si_domain.com/lang/lang_insert.php?l=vi_VN
//			 The Source HTML would be the raw text for the lang.php file
*/

$lang_cmp = (isset($_REQUEST['l']) ? trim($_REQUEST['l']) : (isset($argv[1]) ? trim($argv[1]) : false));

// $lang_cmp = "nb_NO";

include "en_GB/lang.php";
$LANG_en = $LANG;

unset($LANG);

$preamble = '';
$nl = chr(10);

include "$lang_cmp/lang.php";

$h = fopen("$lang_cmp/lang.php", "r");
while ($line = fgets($h)) {
    if ((substr($line, 0, 6) != '$LANG[') && (substr($line, 0, 2) !='?>')) {
        $preamble .= $line;
//    } elseif ((substr($line, 0, 6) == '$LANG[') && (substr($line, -5) == '";//0')) {
    } elseif ( (!(preg_match('/^\$LANG\[.*;\s*\/\/\s*1/', $line)))
		&& (preg_match('/^\$LANG\[.*;\s*\/\/\s*0/', $line)) ) {
// Untranslated strings in lang file
        $ukeyarr = explode("'", $line, 3);
        $ukey = $ukeyarr[1];
		unset($ukeyarr);
		unset($LANG[$ukey]);
    }
}

foreach ($LANG_en AS $k => $v) {
    if (! isset($LANG[$k])) {
        // Untranslated String
        $LANG[$k][0] = $v;
        $LANG[$k][1] = 0;
    } else {
        // Translated String
        $v = $LANG[$k];
        unset($LANG[$k]);
        $LANG[$k][0] = $v;
        $LANG[$k][1] = 1;
    }
}

$LANG_gen = Array();
$LANG_title = Array();
foreach ($LANG as $k => $v) {
    $basestr = '$LANG[' . "'" . $k ."'" .  '] = "' . $v[0] . '";//' . $v[1];
    if (substr($k, 0, 5) == 'title') $LANG_title[$k] = $basestr;
    else $LANG_gen[$k] = $basestr;
}

ksort($LANG_gen);

echo $preamble;

foreach ($LANG_gen as $v) echo $v . $nl;
echo $nl;
foreach ($LANG_title as $v) echo $v . $nl;

echo $nl;
echo '?>';
echo $nl;

?>
