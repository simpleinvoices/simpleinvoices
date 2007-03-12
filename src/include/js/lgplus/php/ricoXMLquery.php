<?php
if (!isset ($_SESSION)) session_start();
header("Cache-Control: no-cache");
header("Pragma: no-cache");
header("Expires: ".gmdate("D, d M Y H:i:s",time()+(-1*60))." GMT");
header("Content-type: text/xml");
echo "<?xml version='1.0' encoding='iso-8859-1'?>\n";

require "applib.php";
require "ricoXmlResponse.php";

$id=isset($_GET["id"]) ? $_GET["id"] : "";
echo "\n<ajax-response><response type='object' id='".$id."_updater'>";
if ($id == "") {
  echo "\n<rows update_ui='false' /><error>";
  echo "\nNo ID provided!";
  echo "\n</error>";
}
elseif (empty($_SESSION[$id])) {
  echo "\n<rows update_ui='false' /><error>";
  echo "\n"."Your connection with the server was idle for too long and timed out. Please refresh this page and try again.";
  echo "\n</error>";
}
elseif (!OpenDB()) {
  echo "\n<rows update_ui='false' /><error>";
  echo "\n".htmlspecialchars($oDB->LastErrorMsg);
  echo "\n</error>";
}
else {
  $oDB->DisplayErrors=false;
  $oDB->ErrMsgFmt="MULTILINE";
  $oXmlResp= new ricoXmlResponse();
  $oXmlResp->sendDebugMsgs=true;
  $oXmlResp->Query2xml($_SESSION[$id], intval($_GET["offset"]), intval($_GET["page_size"]), ($_GET["get_total"]=="true"));
  if (!empty($oDB->LastErrorMsg)) {
    echo "\n<error>";
    echo "\n".htmlspecialchars($oDB->LastErrorMsg);
    echo "\n</error>";
  }
  $oXmlResp=NULL;
}
echo "\n</response></ajax-response>";

?>