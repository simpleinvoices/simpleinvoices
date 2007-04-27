<?php
header("Cache-Control: no-cache");
header("Pragma: no-cache");
header("Expires: ".gmdate("D, d M Y H:i:s",time()+(-1*60))." GMT");
header("Content-type: text/xml");
echo "<?xml version='1.0' encoding='iso-8859-1'?>\n";

require "applib.php";
require "ricoXmlResponse.php";

$id=isset($_GET["id"]) ? $_GET["id"] : "";
$parent=isset($_GET["Parent"]) ? $_GET["Parent"] : "";
echo "\n<ajax-response><response type='object' id='".$id."_updater'>";
if ($id == "") {
  echo "\n<rows update_ui='false' /><error>";
  echo "\nNo ID provided!";
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
  $oXmlResp=new ricoXmlResponse();
  echo "\n<rows update_ui='true' offset='0'>";
  if ($parent) {
    $oXmlResp->Query2xmlRaw("SELECT '$parent',CustomerID,CompanyName,'L',1 FROM customers where CompanyName like '$parent%'",0,99);
  } else {
    $oXmlResp->WriteTreeRow("","root","Customer names starting with...","C",0);
    $oXmlResp->Query2xmlRaw("SELECT distinct 'root',left(CompanyName,1),left(CompanyName,1),'C',0 FROM customers",0,99);
  }
  print "\n"."</rows>";
  if (!empty($oDB->LastErrorMsg)) {
    echo "\n<error>";
    echo "\n".htmlspecialchars($oDB->LastErrorMsg);
    echo "\n</error>";
  }
  $oXmlResp=NULL;
}
echo "\n</response></ajax-response>";

?>