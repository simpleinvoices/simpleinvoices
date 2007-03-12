<?php
require "dbClass2.php";
$appName="Northwind";
$appDB="simple_invoices";

function CreateDbClass() {
  $GLOBALS['oDB']= new dbClass();
  //$GLOBALS['oDB']->Dialect="TSQL";
}

function OpenDB() {
  $_retval=false;
  CreateDbClass();
  return $GLOBALS['oDB']->MySqlLogon($GLOBALS['appDB'], "php", "php");
  //return $GLOBALS['oDB']->OdbcLogon("northwind","Northwind","userid","password");
}

function OpenApp($title) {
  $_retval=false;
  if (!OpenDB()) {
    return $_retval;
  }
  if (!empty($title)) {
    AppHeader($GLOBALS['appName']."-".$title);
  }
  $GLOBALS['accessRights']="rw";
  // CHECK APPLICATION SECURITY HERE  (in this example, "r" gives read-only access and "rw" gives read/write access)
  if (empty($GLOBALS['accessRights']) || !isset($GLOBALS['accessRights']) || substr($GLOBALS['accessRights'],0,1) != "r") {
    echo "<p class='error'>You do not have permission to access this application";
  }
  else {
    $_retval=true;
  }
  return $_retval;
}

function OpenTE($title, $tabname) {
  $_retval=false;
  if (!OpenApp($title)) {
    return $_retval;
  }
  $GLOBALS['oTE']= new TableEditClass();
  $GLOBALS['oTE']->SetTableName($tabname);
  $GLOBALS['oTE']->options["XMLprovider"]="ricoXMLquery.php";
  $CanModify=($GLOBALS['accessRights'] == "rw");
  $GLOBALS['oTE']->options["canAdd"]=$CanModify;
  $GLOBALS['oTE']->options["canEdit"]=$CanModify;
  $GLOBALS['oTE']->options["canDelete"]=$CanModify;
  session_set_cookie_params(60*60);
  $GLOBALS['sqltext']='.';
  return true;
}

function CloseApp() {
  $GLOBALS['oDB']=NULL;
  $GLOBALS['oTE']=NULL;
}

function AppHeader($hdg) {
  echo "<h2 class='appHeader'>".str_replace("<dialect>",$GLOBALS['oDB']->Dialect,$hdg)."</h2>";
}
?>