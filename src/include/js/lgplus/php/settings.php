<?php
$menu=GetQSitem("menu", "dblclick");
$frozen=GetQSitem("frozen", "1");
$highlt=GetQSitem("highlt", "none");
$style=GetQSitem("style", "greenHdg");
if (isset($_GET["menu"])) {
  $sort=GetQSitem("sort", "false");
  $hide=GetQSitem("hide", "false");
  $filter=GetQSitem("filter", "false");
  $resize=GetQSitem("resize", "false");
}
else {
  $sort="true";
  $hide="true";
  $filter=empty($sqltext) ? "false" : "true";
  $resize="true";
}

function GetQSitem($name, $default) {
  return isset($_GET[$name]) ? $_GET[$name] : $default;
}

function setStyle() {
  if ($GLOBALS['style'] != "") {
    echo "Rico.include('".$GLOBALS['style'].".css');";
  }
}

function GridSettingsMenu() {
  echo "{}";
}

function GridSettingsScript() {
  echo "menuEvent     : '".$GLOBALS['menu']."',\n";
  echo "frozenColumns : ".$GLOBALS['frozen'].",\n";
  echo "canSortDefault: ".$GLOBALS['sort'].",\n";
  echo "canHideDefault: ".$GLOBALS['hide'].",\n";
  echo "allowColResize: ".$GLOBALS['resize'].",\n";
  echo "canFilterDefault: ".$GLOBALS['filter'].",\n";
  echo "highlightElem: '".$GLOBALS['highlt']."'";
}

function GridSettingsTE(&$oTE) {
  $oTE->options["menuEvent"]=$GLOBALS['menu'];
  $oTE->options["canSortDefault"]=($GLOBALS['sort'] == "true");
  $oTE->options["canHideDefault"]=($GLOBALS['hide'] == "true");
  $oTE->options["allowColResize"]=($GLOBALS['resize'] == "true");
  $oTE->options["canFilterDefault"]=($GLOBALS['filter'] == "true");
  $oTE->options["frozenColumns"]=$GLOBALS['frozen'];
  $oTE->options["highlightElem"]=$GLOBALS['highlt'];
}

function GridSettingsForm() {
  echo "<form id='settings'><table border='0' cellspacing='5' cellpadding='0'>";
  echo "\n<tr><td colspan='2'><input type='submit' value='Change Settings' style='font-size:small'></td></tr>";
  echo "\n<tr valign=top><td>";
  echo "\n<table border='0' cellspacing='0' cellpadding='0'>";
  echo "\n<tr><td>Style:</td><td><select name='style' style='margin:0'>";
  SettingOpt("greenHdg", "Green Heading", $GLOBALS['style']);
  SettingOpt("tanChisel", "Tan chisel", $GLOBALS['style']);
  SettingOpt("warmfall", "Warm Fall", $GLOBALS['style']);
  SettingOpt("iegradient", "IE gradient", $GLOBALS['style']);
  SettingOpt("coffee-with-milk", "Coffee with milk", $GLOBALS['style']);
  SettingOpt("grayedout", "Grayed out", $GLOBALS['style']);
  echo "</select></td></tr>";
  echo "\n<tr><td>Menu&nbsp;event:</td><td><select name='menu' style='margin:0'>";
  SettingOpt("click", "Click", $GLOBALS['menu']);
  SettingOpt("dblclick", "Double-click", $GLOBALS['menu']);
  SettingOpt("contextmenu", "Right-click", $GLOBALS['menu']);
  SettingOpt("none", "None", $GLOBALS['menu']);
  echo "</select></td></tr>";
  echo "\n<tr><td>Highlight:</td><td><select name='highlt' style='margin:0'>";
  SettingOpt("cursorCell", "Cursor Cell", $GLOBALS['highlt']);
  SettingOpt("cursorRow", "Cursor Row", $GLOBALS['highlt']);
  SettingOpt("menuCell", "Menu Cell", $GLOBALS['highlt']);
  SettingOpt("menuRow", "Menu Row", $GLOBALS['highlt']);
  SettingOpt("selection", "Selection", $GLOBALS['highlt']);
  SettingOpt("none", "None", $GLOBALS['highlt']);
  echo "</select></td></tr>";
  echo "\n<tr><td>Frozen columns:</td><td><select name='frozen' style='margin:0'>";
  for ($i=0; $i<=3; $i++) {
    if (intval($GLOBALS['frozen']) == $i) {
      $sel=" selected";
    }
    else {
      $sel="";
    }
    echo "<option value='".$i."'".$sel.">".$i."</option>";
  }
  echo "</select></td></tr>";
  echo "</table>";
  echo "</td><td>";
  echo "<table border='0' cellspacing='0' cellpadding='0'>";
  SettingChkBx("sort", $GLOBALS['sort'], "Sorting?", false);
  SettingChkBx("filter", $GLOBALS['filter'], "Filtering?", empty($GLOBALS['sqltext']));
  SettingChkBx("hide", $GLOBALS['hide'], "Hide/Show?", false);
  SettingChkBx("resize", $GLOBALS['resize'], "Resizing?", false);
  echo "</td></tr></table>";
  echo "\n</table></form>";
}

function SettingChkBx($name, $value, $desc, $disable) {
  if ($value === "true") {
    $chk=" checked";
  }
  else {
    $chk="";
  }
  echo "<tr><td><input type='checkbox' value='true' name='".$name."'".$chk;
  if ($disable) {
    echo " disabled";
  }
  echo "></td><td>".$desc."</td></tr>";
}

function SettingOpt($value, $desc, $default) {
  $sel=($value == $default) ? " selected" : "";
  echo "\n<option value='".$value."'".$sel.">".$desc."</option>";
}
?>
