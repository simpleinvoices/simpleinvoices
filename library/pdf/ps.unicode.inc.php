<?php
// $Header: /cvsroot/html2ps/ps.unicode.inc.php,v 1.22 2007/01/24 18:56:10 Konstantin Exp $

// TODO: make encodings-related stuff more transparent
// function &find_vector_by_ps_name($psname) {
//   global $g_utf8_converters;

//   foreach ($g_utf8_converters as $key => $value) {
//     if ($value[1] == $psname) {
//       return $value[0];
//     };
//   };

//   return 0;
// };

$GLOBALS['g_encoding_aliases'] = array(
                                       'us-ascii' => 'iso-8859-1',
                                       'cp1250'   => 'windows-1250',
                                       'cp1251'   => 'windows-1251',
                                       'cp1252'   => 'windows-1252'
                                       );

$GLOBALS['g_utf8_converters'] = array(
                                      'iso-8859-1'   => array($GLOBALS['g_iso_8859_1'],"ISO-8859-1-Encoding"),
                                      'iso-8859-2'   => array($GLOBALS['g_iso_8859_2'],"ISO-8859-2-Encoding"),
                                      'iso-8859-3'   => array($GLOBALS['g_iso_8859_3'],"ISO-8859-3-Encoding"),
                                      'iso-8859-4'   => array($GLOBALS['g_iso_8859_4'],"ISO-8859-4-Encoding"),
                                      'iso-8859-5'   => array($GLOBALS['g_iso_8859_5'],"ISO-8859-5-Encoding"),
                                      'iso-8859-6'   => array($GLOBALS['g_iso_8859_6'],"ISO-8859-6-Encoding"),
                                      'iso-8859-7'   => array($GLOBALS['g_iso_8859_7'],"ISO-8859-7-Encoding"),
                                      'iso-8859-8'   => array($GLOBALS['g_iso_8859_8'],"ISO-8859-8-Encoding"),
                                      'iso-8859-9'   => array($GLOBALS['g_iso_8859_9'],"ISO-8859-9-Encoding"),
                                      'iso-8859-10'  => array($GLOBALS['g_iso_8859_10'],"ISO-8859-10-Encoding"),
                                      'iso-8859-11'  => array($GLOBALS['g_iso_8859_11'],"ISO-8859-11-Encoding"),
                                      'iso-8859-13'  => array($GLOBALS['g_iso_8859_13'],"ISO-8859-13-Encoding"),
                                      'iso-8859-14'  => array($GLOBALS['g_iso_8859_14'],"ISO-8859-14-Encoding"),
                                      'iso-8859-15'  => array($GLOBALS['g_iso_8859_15'],"ISO-8859-15-Encoding"),
                                      'koi8-r'       => array($GLOBALS['g_koi8_r'],"KOI8-R-Encoding"),
                                      'cp866'        => array($GLOBALS['g_cp866'],"CP-866"),
                                      'windows-1250' => array($GLOBALS['g_windows_1250'],"Windows-1250-Encoding"),
                                      'windows-1251' => array($GLOBALS['g_windows_1251'],"Windows-1251-Encoding"),
                                      'windows-1252' => array($GLOBALS['g_windows_1252'],"Windows-1252-Encoding"),
                                      'symbol'       => array($GLOBALS['g_symbol'],"Symbol-Encoding"),
                                      'dingbats'     => array($GLOBALS['g_dingbats'],"Dingbats-Encoding")
                                      );
?>