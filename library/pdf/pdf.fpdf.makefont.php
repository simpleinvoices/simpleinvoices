<?php

require_once(HTML2PS_DIR.'ot.class.php');

/**
 * @return Array font metrics hash or null of TTF file could not be parsed
 */
function ReadTTF($fontfile, $map) {
  if (!is_readable($fontfile)) { return null; };

  /**
   * Open font file and read metrics information
   */
  $font = new OpenTypeFile();
  $font->open($fontfile);
  
  $head =& $font->getTable('head');
  $name =& $font->getTable('name');
  $cmap =& $font->getTable('cmap');
  $hmtx =& $font->getTable('hmtx');
  $hhea =& $font->getTable('hhea');
  $post =& $font->getTable('post');
  $subtable =& $cmap->findSubtable(OT_CMAP_PLATFORM_WINDOWS,
                                   OT_CMAP_PLATFORM_WINDOWS_UNICODE);  
  
  /**
   * Prepare initial data
   */
  $widths = array();

  for ($i=0; $i<256; $i++) {
    $code = chr($i);
    if (!isset($map[$code])) {
      $widths[] = 1000;
      continue;
    };
    $ucs2 = $map[$code];

    /**
     * If the font is monospaced, only one entry need be in the array,
     * but  that entry  is required.  The  last entry  applies to  all
     * subsequent glyphs.
     */
    $glyphIndex = $subtable->lookup($ucs2);

    if (!is_null($glyphIndex)) {
      $realIndex = min($glyphIndex, $hhea->_numberOfHMetrics-1);
      $widths[]  = floor($hmtx->_hMetrics[$realIndex]['advanceWidth']*1000/$head->_unitsPerEm);
    } else {
      $widths[] = 1000;
    };
  };

  $font_info = array();

  /**
   * Here we use a hack; as, acording to OT specifications,
   *
   * When  translated  to  ASCII,  these  [...]  strings  must  be
   * identical; no  longer than 63  characters; and restricted  to the
   * printable ASCII subset,  codes 33 through 126, except  for the 10
   * characters: '[', ']', '(', ')', '{', '}', '<', '>', '/', '%'.
   *
   * we can assume that UCS-2 encoded string we receive can be easily 
   * translated to ASCII by removing the high-byte of all two-byte characters 
   */
  $ps_name_ucs2 = $name->lookup(OT_CMAP_PLATFORM_WINDOWS, 
                                OT_CMAP_PLATFORM_WINDOWS_UNICODE, 
                                OT_CMAP_LANGUAGE_WINDOWS_ENGLISH_AMERICAN, 
                                OT_NAME_ID_POSTSCRIPT_NAME);
  $ps_name_ascii = "";
  for ($i=0; $i<strlen($ps_name_ucs2); $i+=2) {
    $ps_name_ascii .= $ps_name_ucs2{$i+1};
  };

  $font_info['FontName']           = $ps_name_ascii;

  $font_info['Weight']             = $name->lookup(null, null, null, OT_NAME_ID_SUBFAMILY_NAME);
  $font_info['ItalicAngle']        = $post->_italicAngle;
  $font_info['IsFixedPitch']       = (bool)$post->_isFixedPitch;
  // $font_info['CapHeight']         
  // $font_info['StdVW']            
  $font_info['Ascender']           = floor($hhea->_ascender*1000/$head->_unitsPerEm);
  $font_info['Descender']          = floor($hhea->_descender*1000/$head->_unitsPerEm);
  $font_info['UnderlineThickness'] = floor($post->_underlineThickness*1000/$head->_unitsPerEm);
  $font_info['UnderlinePosition']  = floor($post->_underlinePosition*1000/$head->_unitsPerEm);
  $font_info['FontBBox']           = array($head->_xMin*1000/$head->_unitsPerEm,
                                           $head->_yMin*1000/$head->_unitsPerEm,
                                           $head->_xMax*1000/$head->_unitsPerEm,
                                           $head->_yMax*1000/$head->_unitsPerEm);
  $font_info['Widths']             = $widths;

  $font->_delete();
  unset($font);
  
  return $font_info;
}

/**
 * @return Array font metrics hash or null of AFM file is missing
 */
function ReadAFM($file, $map) {
  if (!is_readable($file)) { return null; };

  $afm_lines = file($file);
  $widths=array();
  $fm=array();

  foreach ($afm_lines as $l) {
    $e=explode(' ',rtrim($l));

    if (count($e)<2) {
      continue;
    };

    $code=$e[0];
    $param=$e[1];

    if ($code=='C') {
      //Character metrics
      $cc=(int)$e[1];
      $w=$e[4];
      $gn=$e[7];
      if (substr($gn,-4)=='20AC') {
        $gn='Euro';
      };
      
      $widths[$gn]=$w;

      if ($gn=='.notdef') {
        $fm['MissingWidth']=$w;
      };
    }
    elseif($code=='FontName')
      $fm['FontName']=$param;
    elseif($code=='Weight')
      $fm['Weight']=$param;
    elseif($code=='ItalicAngle')
      $fm['ItalicAngle']=(double)$param;
    elseif($code=='Ascender')
      $fm['Ascender']=(int)$param;
    elseif($code=='Descender')
      $fm['Descender']=(int)$param;
    elseif($code=='UnderlineThickness')
      $fm['UnderlineThickness']=(int)$param;
    elseif($code=='UnderlinePosition')
      $fm['UnderlinePosition']=(int)$param;
    elseif($code=='IsFixedPitch')
      $fm['IsFixedPitch']=($param=='true');
    elseif($code=='FontBBox')
      $fm['FontBBox']=array($e[1],$e[2],$e[3],$e[4]);
    elseif($code=='CapHeight')
      $fm['CapHeight']=(int)$param;
    elseif($code=='StdVW')
      $fm['StdVW']=(int)$param;
  }
  
  if(!isset($fm['FontName'])) {
    die('FontName not found');
  };

  if (!isset($widths['.notdef'])) {
    $widths['.notdef']=600;
  };

  if (!isset($widths['Delta']) and isset($widths['increment'])) {
    $widths['Delta']=$widths['increment'];
  };
      
  // Order widths according to map
  for ($i=0; $i<=255; $i++) {
    if(!isset($widths[$map[chr($i)]])) {
      error_log('<B>Warning:</B> character '.$map[chr($i)].' is missing<BR>');
      $widths[$i]=$widths['.notdef'];
    } else {
      $widths[$i]=$widths[$map[chr($i)]];
    };
  };

  $fm['Widths']=$widths;
  return $fm;
}

function MakeFontDescriptor($fm,$symbolic) {
  //Ascent
  $asc=(isset($fm['Ascender']) ? $fm['Ascender'] : 1000);
  $fd="array('Ascent'=>".$asc;

  //Descent
  $desc=(isset($fm['Descender']) ? $fm['Descender'] : -200);
  $fd.=",'Descent'=>".$desc;

  //CapHeight
  if (isset($fm['CapHeight'])) {
    $ch=$fm['CapHeight'];
  }  elseif(isset($fm['CapXHeight'])) {
    $ch=$fm['CapXHeight'];
  } else {
    $ch=$asc;
  };
  $fd.=",'CapHeight'=>".$ch;

  //Flags
  $flags=0;
  if (isset($fm['IsFixedPitch']) and $fm['IsFixedPitch']) {
    $flags+=1<<0;
  };

  if ($symbolic) {
    $flags+=1<<2;
  };

  if (!$symbolic) {
    $flags+=1<<5;
  };

  if (isset($fm['ItalicAngle']) and $fm['ItalicAngle']!=0) {
    $flags+=1<<6;
  };

  $fd.=",'Flags'=>".$flags;

  //FontBBox
  if (isset($fm['FontBBox'])) {
    $fbb=$fm['FontBBox'];
  } else {
    $fbb=array(0,$des-100,1000,$asc+100);
  };

  $fd.=",'FontBBox'=>'[".$fbb[0].' '.$fbb[1].' '.$fbb[2].' '.$fbb[3]."]'";

  //ItalicAngle
  $ia=(isset($fm['ItalicAngle']) ? $fm['ItalicAngle'] : 0);
  $fd.=",'ItalicAngle'=>".$ia;

  //StemV
  if (isset($fm['StdVW'])) {
    $stemv=$fm['StdVW'];
  } elseif(isset($fm['Weight']) and eregi('(bold|black)',$fm['Weight'])) {
    $stemv=120;
  } else {
    $stemv=70;
  };
  $fd.=",'StemV'=>".$stemv;

  //MissingWidth
  if (isset($fm['MissingWidth'])) {
    $fd.=",'MissingWidth'=>".$fm['MissingWidth'];
  };
  $fd.=')';

  return $fd;
}

function MakeWidthArray($fm) {
  //Make character width array
  $s="array(\n\t";
  $cw=$fm['Widths'];
  for ($i=0; $i<=255; $i++) {
    if (chr($i)=="'") {
      $s.="'\\''";
    } elseif (chr($i)=="\\") {
      $s.="'\\\\'";
    } elseif($i>=32 and $i<=126) {
      $s.="'".chr($i)."'";
    } else {
      $s.="chr($i)";
    };
    $s.='=>'.$fm['Widths'][$i];
    if ($i<255) {
      $s.=',';
    };

    if(($i+1)%22==0) {
      $s.="\n\t";
    };
  }
  $s.=')';
  return $s;
}

function MakeFontEncoding($map) {
  //Build differences from reference encoding
  $manager = ManagerEncoding::get();
  $ref = $manager->get_encoding_glyphs('windows-1252');

  $s='';
  $last=0;
  for($i=32;$i<=255;$i++) {
    if ($map[chr($i)]!=$ref[chr($i)]) {
      if ($i!=$last+1) {
        $s.=$i.' ';
      };
      $last=$i;
      $s.='/'.$map[chr($i)].' ';
    };
  }

  return rtrim($s);
}

function MakeFontCMap($encoding) {
  //Build differences from reference encoding
  $manager = ManagerEncoding::get();
  $ref = $manager->getEncodingVector($encoding);

  $s  = "array(\n";
  foreach ($ref as $char => $ucs) {
    $s .= sprintf("0x%02X => 0x%04X,\n", ord($char), $ucs);
  };
  $s .= ")";

  return trim($s);
}

function SaveToFile($file,$s,$mode='t')
{
  $f=fopen($file,'w'.$mode);
  if(!$f)
    die('Can\'t write to file '.$file);
  fwrite($f,$s,strlen($s));
  fclose($f);
}

function ReadShort($f)
{
  $a=unpack('n1n',fread($f,2));
  return $a['n'];
}

function ReadLong($f)
{
  $a=unpack('N1N',fread($f,4));
  return $a['N'];
}

function CheckTTF($file)
{
  //Check if font license allows embedding
  $f=fopen($file,'rb');
  if(!$f)
    die('<B>Error:</B> Can\'t open '.$file);
  //Extract number of tables
  fseek($f,4,SEEK_CUR);
  $nb=ReadShort($f);
  fseek($f,6,SEEK_CUR);
  //Seek OS/2 table
  $found=false;

  for ($i=0;$i<$nb;$i++) {
    if (fread($f,4)=='OS/2') {
      $found=true;
      break;
    }
    fseek($f,12,SEEK_CUR);
  };

  if (!$found) {
    fclose($f);
    return;
  };

  fseek($f,4,SEEK_CUR);
  $offset=ReadLong($f);
  fseek($f,$offset,SEEK_SET);

  //Extract fsType flags
  fseek($f,8,SEEK_CUR);
  $fsType=ReadShort($f);
  $rl=($fsType & 0x02)!=0;
  $pp=($fsType & 0x04)!=0;
  $e=($fsType & 0x08)!=0;
  fclose($f);
  if ($rl and !$pp and !$e) {
    echo '<B>Warning:</B> font license does not allow embedding';
  };
}

/*******************************************************************************
 * $fontfile : chemin du fichier TTF (ou chaîne vide si pas d'incorporation)    *
 * $afmfile :  chemin du fichier AFM                                            *
 * $enc :      encodage (ou chaîne vide si la police est symbolique)            *
 * $patch :    patch optionnel pour l'encodage                                  *
 * $type :     type de la police si $fontfile est vide                          *
 *******************************************************************************/
function MakeFont($fontfile, $afmfile, $destdir, $destfile, $enc) {
  // Generate a font definition file
  set_magic_quotes_runtime(0);
  ini_set('auto_detect_line_endings','1');

  $manager = ManagerEncoding::get();
  $map     = $manager->get_encoding_glyphs($enc);

  $fm = ReadAFM($afmfile, $map);

  if (is_null($fm)) {
    error_log(sprintf("Notice: Missing AFM file '%s'; attempting to parse font file '%s' directly",
                      $afmfile,
                      $fontfile));
    
    $fm = ReadTTF($fontfile, $manager->getEncodingVector($enc));

    if (is_null($fm)) {
      die(sprintf("Cannot get font metrics for '%s'", $fontfile));
    };
  }

  $diff = MakeFontEncoding($map);
  $cmap = MakeFontCMap($enc);
  $fd   = MakeFontDescriptor($fm,empty($map));

  //Find font type
  if ($fontfile) {
    $ext=strtolower(substr($fontfile,-3));
    if ($ext=='ttf') { 
      $type='TrueType';
    }  elseif($ext=='pfb') {
      $type='Type1';
    } else {
      die('<B>Error:</B> unrecognized font file extension: '.$ext);
    };
  } else {
    if ($type!='TrueType' and $type!='Type1') {
      die('<B>Error:</B> incorrect font type: '.$type);
    };
  }

  //Start generation
  $s='<?php'."\n";
  $s.='$type=\''.$type."';\n";
  $s.='$name=\''.$fm['FontName']."';\n";
  $s.='$desc='.$fd.";\n";
  if (!isset($fm['UnderlinePosition'])) {
    $fm['UnderlinePosition']=-100;
  };
  if (!isset($fm['UnderlineThickness'])) {
    $fm['UnderlineThickness']=50;
  };
  $s.='$up='.$fm['UnderlinePosition'].";\n";
  $s.='$ut='.$fm['UnderlineThickness'].";\n";
  $w=MakeWidthArray($fm);
  $s.='$cw='.$w.";\n";
  $s.='$enc=\''.$enc."';\n";
  $s.='$diff=\''.$diff."';\n";
  $s.='$cmap='.$cmap.";\n";

  $basename=substr(basename($afmfile),0,-4);

  if ($fontfile) {
    //Embedded font
    if (!file_exists($fontfile)) {
      die('<B>Error:</B> font file not found: '.$fontfile);
    };

    if ($type=='TrueType') {
      CheckTTF($fontfile);
    };

    $f=fopen($fontfile,'rb');
    if (!$f) {
      die('<B>Error:</B> Can\'t open '.$fontfile);
    };

    $file=fread($f,filesize($fontfile));
    fclose($f);
    if ($type=='Type1') {
      //Find first two sections and discard third one
      $header=(ord($file{0})==128);
      if ($header) {
        //Strip first binary header
        $file=substr($file,6);
      }
      $pos=strpos($file,'eexec');
      if(!$pos) {
        die('<B>Error:</B> font file does not seem to be valid Type1');
      };
      $size1=$pos+6;
      if($header and ord($file{$size1})==128) {
        //Strip second binary header
        $file=substr($file,0,$size1).substr($file,$size1+6);
      }
      $pos=strpos($file,'00000000');
      if (!$pos) {
        die('<B>Error:</B> font file does not seem to be valid Type1');
      };
      
      $size2=$pos-$size1;
      $file=substr($file,0,$size1+$size2);
    }

    $gzcompress_exists = function_exists('gzcompress');
    if ($gzcompress_exists) {
      $cmp = $basename.'.z';
      SaveToFile($destdir.$cmp, gzcompress($file), 'b');

      $s.='$file=\''.$cmp."';\n";
    } else {
      $cmp = $basename.'.ttf';
      SaveToFile($destdir.$cmp, $file, 'b');

      $s.='$file=\''.basename($fontfile)."';\n";
      error_log('Notice: font file could not be compressed (zlib extension not available)');
    }
    
    if ($type=='Type1') {
      $s.='$size1='.$size1.";\n";
      $s.='$size2='.$size2.";\n";
    } else {
      $s.='$originalsize='.filesize($fontfile).";\n";
    }
  } else {
    //Not embedded font
    $s.='$file='."'';\n";
  }

  $s.="?>\n";
  SaveToFile($destdir.$destfile,$s);
}
?>
