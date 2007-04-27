<?php
// -------------------------------------------------------------
// Check languages accepted by browser
// and see if there is a match
// -------------------------------------------------------------


function setLang() {
  $jsDir="../js/";
  $lang=strtolower($_SERVER["HTTP_ACCEPT_LANGUAGE"]);
  $arLang=explode(",",$lang);
  for ($i=0; $i<count($arLang); $i++)
  {
    $lang2=strtolower(substr(trim($arLang[$i]),0,2));
    if ($lang2=='en') break;
    $fname=$jsDir."livegrid_".$lang2.".js";
    if (file_exists($fname))
    {
      echo "Rico.include('".$fname."');";
      break;
    } 
  }
}
?>
