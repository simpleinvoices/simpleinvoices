<?php
//**********************************
// Rico: GENERIC TABLE/VIEW EDITOR
//  By Matt Brown
//**********************************

class TableEditTable {
  var $TblName;
  var $arFields;
  var $arData;
}

class TableEditClass {
  var $action;
  var $TableFilter;
  var $options;
  var $AutoInit;
  var $CurrentField;
  var $SvrOnly;
  var $Panels=array();
  var $objDB;
  var $CurrentPanel;
  var $TEScriptName;
  var $xhtmlcloser;
  var $ErrorFlag;
  var $ErrorMsg;
  var $gridID;
  var $MainTbl;
  var $Tables=array();
  var $TableCnt;
  var $Fields=array();
  var $FieldCnt;

  function AddEntryField($ColumnName, $Heading, $EntryTypeCode, $DefaultValue) {
    if (_instr(0,"/S/N/R/H/HF/D/DT/I/B/T/TA/SL/RL/CL/","/".$EntryTypeCode."/",0) < 1) {
      $this->TableEditError("invalid EntryTypeCode in TableEditClass");
      return;
    }
    if (!$this->IncrCurrentField()) {
      return;
    }
    $this->CurrentField["ColName"]=$ColumnName;
    $this->CurrentField["Hdg"]=$Heading;
    $this->CurrentField["EntryType"]=$EntryTypeCode;
    $this->CurrentField["ColData"]=$DefaultValue;
    switch ($EntryTypeCode) {

      case "I":
        $this->CurrentField["OnChange"]="TableEditCheckInt";
        break;

      case "D":
        $this->CurrentField["type"]="date";
        break;

      case "DT":
        $this->CurrentField["type"]="datetime";
        break;

      case "TA":
        $this->CurrentField["TxtAreaRows"]=4;
        $this->CurrentField["TxtAreaCols"]=80;
        break;

      case "R":
      case "RL":
        $this->CurrentField["RadioBreak"]="<br".$this->xhtmlcloser;
        break;
    }
  }
  // returns true if successful

  function AddCalculatedField($ColumnFormula, $Heading) {
    $_retval=false;
    if (!$this->IncrCurrentField()) {
      return $_retval;
    }
    if (substr($ColumnFormula,0,1) != "(") {
      $ColumnFormula="(".$ColumnFormula.")";
    }
    $this->CurrentField["ColName"]="Calc_".$this->FieldCnt;
    $this->CurrentField["Formula"]=$ColumnFormula;
    $this->CurrentField["Hdg"]=$Heading;
    return true;
  }

  function AddFilterField($ColumnName, $FilterValue) {
    $this->AddEntryField($ColumnName, "", "H", $FilterValue);
    $this->CurrentField["FilterFlag"]=true;
  }

  function AddPanel($PanelHeading) {
    $this->CurrentPanel++;
    $this->Panels[$this->CurrentPanel]=$PanelHeading;
  }

  function DefineAltTable($AltTabName, $FieldList, $FieldData, $Delim) {
    $this->TableCnt++;
    $this->Tables[$this->TableCnt]= new TableEditTable();
    $_withval=$this->Tables[$this->TableCnt];
    $_withval->TblName=$AltTabName;
    $_withval->arFields=explode($Delim,$FieldList);
    $_withval->arData=explode($Delim,$FieldData);
    if (count($_withval->arFields)-1 != count($_withval->arData)-1) {
      $this->TableEditError("# of fields does not match # of data entries supplied for table ".$AltTabName);
      return $_retval;
    }
    return $this->TableCnt;
  }
  // returns true if FieldCnt successfully incremented

  function IncrCurrentField() {
    $this->FieldCnt++;
    $this->Fields[$this->FieldCnt]= array();
    $this->CurrentField= &$this->Fields[$this->FieldCnt];
    $this->CurrentField["panelIdx"]=($this->CurrentPanel >= 0) ? $this->CurrentPanel : 0;
    $this->CurrentField["AddQuotes"]=true;
    $this->CurrentField["ReadOnly"]=false;
    $this->CurrentField["TableIdx"]=$this->MainTbl;
    return true;
  }

  function SetTableName($s) {
    $this->TableCnt++;
    $this->MainTbl=$this->TableCnt;
    $this->Tables[$this->TableCnt]= new TableEditTable();
    $this->Tables[$this->MainTbl]->TblName=$s;
    $this->gridID=strtolower(str_replace(" ","_",str_replace(".","_",$s)));
    $actionparm=$this->gridID."__action";
    $this->action=isset($_REQUEST[$actionparm]) ? trim($_REQUEST[$actionparm]) : "";
    $this->action=($this->action == "") ? "table" : strtolower($this->action);
  }

  function SortAsc() {
    $this->options["sortCol"]=$this->FieldCnt;
    $this->options["sortDir"]="ASC";
  }

  function SortDesc() {
    $this->options["sortCol"]=$this->FieldCnt;
    $this->options["sortDir"]="DESC";
  }

  function ConfirmDeleteColumn() {
    $this->options["ConfirmDeleteCol"]=$this->FieldCnt;
  }

  function genXHTML() {
    $this->xhtmlcloser=" />";
  }

  function SetDbConn(&$dbcls) {
    $this->objDB=&$dbcls;
  }
  //*************************************************************************************
  // Get a passed in parameter - typically used for filtering
  //*************************************************************************************

  function TableEditParm($ParmName) {
    $CookieName=$this->TEScriptName.".".$ParmName;
    $cnt=(int)isset($_GET[$ParmName]);
    if ($cnt == 0) {
      $TempVal=$_COOKIE[$CookieName];
      if ($this->options["DebugFlag"]) {
        echo "<p class='debug'>Retrieved value '".$TempVal."' from cookie '".$CookieName."'";
      }
    }
    else {
      for ($i=1; $i<=$cnt; $i++) {
        if ($i > 1) {
          $TempVal.="~";
        }
        $TempVal.=trim($_GET[$ParmName][$i-1]);
      }
      if ($this->options["DebugFlag"]) {
        echo "<p class='debug'>Retrieved value '".$TempVal."' from QueryString '".$ParmName."'";
      }
    }
    setcookie($CookieName,$TempVal);
    if ($this->options["DebugFlag"]) {
      echo "<p class='debug'>Setting cookie '".$CookieName."' to '".$TempVal."'";
    }
    return $TempVal;
  }
  //*************************************************************************************
  // Take appropriate action
  //*************************************************************************************

  function DisplayPage() {
    if ($this->FieldCnt < 0) {
      return;
    }
    if (!$this->ErrorFlag) {
      $this->GetColumnInfo();
    }
    if (!$this->ErrorFlag) {
      switch ($this->action) {

        case "del":
          if ($this->options["canDelete"]) {
            $this->TableDeleteRecord();
          }
          break;

        case "ins":
          if ($this->options["canAdd"]) {
            $this->TableInsertRecord();
          }
          break;

        case "upd":
          if ($this->options["canEdit"]) {
            $this->TableUpdateRecord();
          }
          break;

        default:
          $this->TableDisplay();
          break;
      }
    }
    if ($this->ErrorFlag) {
      echo "\n<p style='color:red;'><span style='text-decoration:underline;'>ERROR ENCOUNTERED</span><br".$this->xhtmlcloser.$this->ErrorMsg;
    }
  }
  //*************************************************************************************
  // Class Constructor
  //*************************************************************************************

  function TableEditClass() {
    $this->TEScriptName=trim($_SERVER["SCRIPT_NAME"]);
    if (is_object($GLOBALS['oDB'])) {
      $this->objDB=&$GLOBALS['oDB'];
      // use oDB global as database connection, if it exists
    }
    $this->options=array();
    $this->options["TableSelectNew"]="___new___";
    $this->options["TableSelectNone"]="";
    $this->options["canAdd"]=true;
    $this->options["canEdit"]=true;
    $this->options["canView"]=true;
    $this->options["canDelete"]=true;
    $this->options["ConfirmDelete"]=true;
    $this->options["ConfirmDeleteCol"]=-1;
    $this->options["DebugFlag"]=isset($_GET["debug"]);
    $this->options["RecordName"]="record";
    $this->options["prefetchBuffer"]=true;
    $this->options["PanelNamesOnTabHdr"]=true;
    $this->options["highlightElem"]="menuRow";

    $this->SvrOnly=array();
    $this->SvrOnly["SelectSql"]=1;
    $this->SvrOnly["Formula"]=1;
    $this->SvrOnly["TableIdx"]=1;
    $this->SvrOnly["AddQuotes"]=1;
    $this->SvrOnly["FilterFlag"]=1;
    $this->SvrOnly["XMLprovider"]=1;

    $this->xhtmlcloser=">";
    $this->FieldCnt=-1;
    $this->CurrentPanel=-1;
    $this->TableCnt=-1;
    $this->AutoInit=true;
    $this->ErrorFlag=false;
    $this->ErrorMsg="";
    $PopUpFlag=false;
  }
  //*************************************************************************************
  // Class Destructor
  //*************************************************************************************

  function Class_Terminate() {
    // Setup Terminate event.
    for ($i=0; $i<=$this->FieldCnt; $i++) {
      $this->Fields[$i]=NULL;
    }
    $this->options=NULL;
    $this->SvrOnly=NULL;
  }
  // if AltTable has a multi-column key, then add those additional constraints
  // required columns will be prefixed with a "*"

  function AltTableKeyWhereClause($AltTabIdx) {
    for ($i=0; $i<=count($this->Tables[$AltTabIdx]->arFields)-1; $i++) {
      if (substr($this->Tables[$AltTabIdx]->arFields[$i],0,1) == "*") {
        $w.=" and ".substr($this->Tables[$AltTabIdx]->arFields[$i],1)."=".$this->Tables[$AltTabIdx]->arData[$i];
      }
    }
    return $w;
  }

  function GetDataForKey() {
    $a=array();
    $sqltext='';
    for ($i=0; $i<=$this->FieldCnt; $i++) {
      if ($this->Fields[$i]["TableIdx"] == $this->MainTbl) {
        $sqltext.=",".$this->Fields[$i]["ColName"];
      }
      else {
        $sqltext.=",(select ".$this->Fields[$i]["ColName"]." from ".$this->Tables[$this->Fields[$i]["TableIdx"]]->TblName." t".$i;
        $sqltext.=$this->TableKeyWhereClause();
        $sqltext.=$this->AltTableKeyWhereClause($this->Fields[$i]["TableIdx"]).")";
      }
    }
    $sqltext="SELECT ".substr($sqltext,1)." FROM ".$this->Tables[$this->MainTbl]->TblName." t ".$this->TableKeyWhereClause();
    if ($this->options["DebugFlag"]) {
      echo "<p class='debug'>".$sqltext;
    }
    if ($this->objDB->SingleRecordQuery($sqltext, $a)) {
      for ($i=0; $i<=$this->FieldCnt; $i++) {
        if (array_key_exists("EntryType",$this->Fields[$i])) {
          if ($this->Fields[$i]["EntryType"] != "H") {
            $this->Fields[$i]["ColData"]=$a[$i];
            // don't overwrite hidden field data
          }
        }
      }
    }
    else {
      $this->TableEditError("cannot retrieve data using query:<br".$this->xhtmlcloser.$sqltext);
    }
  }
  // name used external to this script

  function ExtFieldId($i) {
    return $this->gridID."_".$i;
  }

  function IsCalculatedField($i) {
    return array_key_exists("Formula",$this->Fields[$i]);
  }

  //*************************************************************************************
  // Retrieves column info from database for main table and any alternate tables
  //*************************************************************************************
  function GetColumnInfo() {
    $Columns=array();
    $dicColIdx=array();
    for ($FieldNum=0; $FieldNum<=$this->FieldCnt; $FieldNum++) {
      $dicColIdx[$this->Fields[$FieldNum]["TableIdx"].".".$this->Fields[$FieldNum]["ColName"]]= $FieldNum;
      if ($this->options["canEdit"] == false && $this->options["canAdd"] == false) {
        $this->Fields[$FieldNum]["ReadOnly"]=true;
      }
    }
    //print_r($dicColIdx);
    for ($i=0; $i<=$this->TableCnt; $i++) {
      $cnt=$this->objDB->GetColumnInfo($this->Tables[$i]->TblName, $Columns);
      if ($cnt < 1) {
        $this->TableEditError("unable to retrieve column info for ".$this->Tables[$i]->TblName."<br>".$this->objDB->LastErrorMsg);
        return;
      }
      //print_r($Columns);
      for ($c=0; $c<=$cnt-1; $c++) {
        $colname=$i.".".$Columns[$c]->ColName;
        if (array_key_exists($colname,$dicColIdx)) {
          $FieldNum=$dicColIdx[$colname];
          $this->Fields[$FieldNum]["ColInfo"]=$Columns[$c];
        }
        elseif ($Columns[$c]->IsPKey) {
          $this->TableEditError("primary key field is not defined (".$this->Tables[$i]->TblName.".".$Columns[$c]->ColName.")");
        }
      }
    }
    $dicColIdx=NULL;
  }

  function TableUpdateDatabase($sqltext, $actiontxt) {
    if ($this->ErrorFlag) {
      return;
    }
    $cnt=$this->objDB->RunActionQueryReturnMsg($sqltext, $errmsg);
    if ($this->options["DebugFlag"])
      echo "<p class='debug'>".$sqltext."<br".$this->xhtmlcloser."Records affected: ".$cnt;
    if (!empty($errmsg))
      $this->TableEditError("unable to update database!<br".$this->xhtmlcloser.$errmsg);
    else if ($cnt == 1)
      echo "<p>".$this->options["RecordName"]." ".$actiontxt." successfully</p>";
    else
      $this->TableEditError("no data changed - update skipped");
  }

  function FormatValue($v, $idx) {
    $fld=$this->Fields[$idx];
    $addquotes=$fld["AddQuotes"];
    if (substr($fld["EntryType"],0,1) == "D") {
      if ($v == "") {
        $addquotes=false;
        $v="NULL";
      }
    }
    elseif ($fld["EntryType"] == "I") {
      $addquotes=false;
      if ($v == "" || !is_numeric($v)) {
        $v="NULL";
      }
    }
    elseif ($fld["EntryType"] == "N" && $v == $this->options["TableSelectNew"]) {
      $v=trim($_POST["textnew__".$this->ExtFieldId($idx)]);
    }
    elseif (_instr(0,"SNR",substr($fld["EntryType"],0,1),0) > 0 && $v == $this->options["TableSelectNone"]) {
      $addquotes=false;
      $v="NULL";
    }
    if ($addquotes) $v=$this->objDB->addQuotes($v);
    return $v;
  }

  function FormatFormValue($idx,$fieldname) {
    if (array_key_exists("EntryType",$this->Fields[$idx])) {
      if ($this->Fields[$idx]["EntryType"] == "H" || (array_key_exists("FormView",$this->Fields[$idx]) && $this->Fields[$idx]["FormView"] == "exclude")) {
        $v=$this->Fields[$idx]["ColData"];
      }
      else {
        if (isset($_POST[$fieldname]))
          $v=trim($_POST[$fieldname]);
        else if ($this->options["DebugFlag"])
          $v=$_GET[$fieldname];
      }
      $_retval=$this->FormatValue($v, $idx);
    }
    return $_retval;
  }
  //*************************************************************************************
  // Deletes the specified record
  //*************************************************************************************

  function TableDeleteRecord() {
    $this->TableUpdateDatabase("DELETE FROM ".$this->Tables[$this->MainTbl]->TblName.$this->TableKeyWhereClause(), "deleted");
  }

  function UpdateRecord($sqltext) {
    $this->objDB->RunActionQueryReturnMsg($sqltext, $errmsg);
    if (!empty($errmsg)) {
      $errmsg="unable to update database!<br".$this->xhtmlcloser.$errmsg;
      if ($this->options["DebugFlag"]) {
        $errmsg.="<p>SQL: ".$sqltext;
      }
      $this->TableEditError($errmsg);
    }
    elseif ($this->options["DebugFlag"]) {
      echo "<BR class='debug'>".$sqltext;
    }
  }

  function UpdateAltTableRecords($i) {
    if ($this->ErrorFlag) {
      return;
    }
    // delete existing record
    $sqltext="delete from ".$this->Tables[$i]->TblName;
    $sqltext.=$this->TableKeyWhereClause();
    $sqltext.=$this->AltTableKeyWhereClause($i);
    $this->UpdateRecord($sqltext);
    // insert new record
    $colnames="";
    $coldata="";
    for ($j=0; $j<=$this->FieldCnt; $j++) {
      if ($this->Fields[$j]["TableIdx"] == $i || $this->Fields[$j]["ColInfo"]->IsPKey) {
        $colnames.=",".$this->Fields[$j]["ColName"];
        $coldata.=",".$this->FormatValue(trim($_POST[$this->ExtFieldId($j)]), $j);
      }
    }
    for ($j=0; $j<=count($this->Tables[$i]->arFields)-1; $j++) {
      $c=$this->Tables[$i]->arFields[$j];
      if (substr($c,0,1) == "*") {
        $c=substr($c,1);
      }
      $colnames.=",".$c;
      $coldata.=",".$this->Tables[$i]->arData[$i][$j];
    }
    $sqltext="insert into ".$this->Tables[$i]->TblName." (".substr($colnames,1).") values (".substr($coldata,1).")";
    $this->UpdateRecord($sqltext);
  }
  //*************************************************************************************
  // Updates an existing record in the db
  //*************************************************************************************

  function TableUpdateRecord() {
    for ($i=0; $i<=$this->TableCnt; $i++) {
      if ($i != $this->MainTbl) {
        $this->UpdateAltTableRecords($i);
      }
    }
    for ($i=0,$sqltext=''; $i<=$this->FieldCnt; $i++) {
      if (!$this->IsCalculatedField($i)) {
        if ($this->Fields[$i]["TableIdx"] == $this->MainTbl && $this->Fields[$i]["ColInfo"]->Writeable) {
          $sqltext.=",".$this->Fields[$i]["ColName"]."=".$this->FormatFormValue($i,$this->ExtFieldId($i));
        }
      }
    }
    $sqltext="UPDATE ".$this->Tables[$this->MainTbl]->TblName." SET ".substr($sqltext,1);
    $sqltext.=$this->TableKeyWhereClause();
    $this->TableUpdateDatabase($sqltext, "updated");
  }
  //*************************************************************************************
  // Inserts a new record into the db
  //*************************************************************************************

  function TableInsertRecord() {
    $keyCnt=0;
    $sqlcol="";
    $sqlval="";
    for ($i=0; $i<=$this->FieldCnt; $i++) {
      if (!$this->IsCalculatedField($i) && $this->Fields[$i]["TableIdx"] == $this->MainTbl) {
        if ($this->Fields[$i]["ColInfo"]->IsPKey) {
          $keyCnt++;
          $keyIdx=$i;
        }
        if ($this->Fields[$i]["ColInfo"]->Writeable) {
          $sqlcol.=",".$this->Fields[$i]["ColName"];
          $sqlval.=",".$this->FormatFormValue($i,$this->ExtFieldId($i));
        }
      }
    }
    $sqltext="insert into ".$this->Tables[$this->MainTbl]->TblName." (".substr($sqlcol,1).") values (".substr($sqlval,1).")";
    $this->TableUpdateDatabase($sqltext, "added");
    if ($this->TableCnt > 0 && $keyCnt == 1 && !$this->Fields[$keyIdx]["ColInfo"]->Writeable) {
      if (!$this->objDB->SingleRecordQuery("SELECT SCOPE_IDENTITY()", $this->Fields[$keyIdx]["ColData"])) {
        $this->TableEditError("unable to retrieve new identity value");
        return;
      }
    }
    for ($i=0; $i<=$this->TableCnt; $i++) {
      if ($i != $this->MainTbl) {
        $this->UpdateAltTableRecords($i);
      }
    }
  }
  // form where clause based on table's primary key

  function TableKeyWhereClause() {
    for ($i=0; $i<=$this->FieldCnt; $i++) {
      if ($this->Fields[$i]["TableIdx"] == $this->MainTbl && !$this->IsCalculatedField($i)) {
        if ($this->Fields[$i]["ColInfo"]->IsPKey) {
          $this->objDB->AddCondition($w, $this->Fields[$i]["ColName"]."=".$this->FormatFormValue($i,"_k".$i));
        }
      }
    }
    if (empty($w)) {
      $this->TableEditError("no key value");
    }
    else {
      $_retval=" WHERE ".$w;
    }
    return $_retval;
  }

  function TableEditError($msg) {
    $this->ErrorFlag=true;
    $this->ErrorMsg=$msg;
  }
  //*************************************************************************************
  // Displays a table
  //*************************************************************************************

  function TableDisplay() {
    // -------------------------------------
    // form sql query
    // -------------------------------------
    $oParseMain= new sqlParse();
    $oParseLookup= new sqlParse();
    $oParseMain->Init($this->FieldCnt);
    $oParseMain->FromClause=$this->Tables[$this->MainTbl]->TblName." t";
    $oParseMain->AddWhereCondition($this->TableFilter);
    for ($i=0; $i<=$this->FieldCnt; $i++) {
      if (array_key_exists("FilterFlag",$this->Fields[$i])) {
        // add any column filters to where clause
        $oParseMain->AddWhereCondition($this->Fields[$i]["ColName"]."='".$this->Fields[$i]["ColData"]."'");
      }
      if (array_key_exists("EntryType",$this->Fields[$i])) {
        if (_instr(0,"CSNR",substr($this->Fields[$i]["EntryType"],0,1),0) > 0) {
          if (array_key_exists("SelectSql",$this->Fields[$i])) {
            $_SESSION[$this->ExtFieldId($i)]=$this->Fields[$i]["SelectSql"];
          }
          else {
            $_SESSION[$this->ExtFieldId($i)]="select distinct ".$this->Fields[$i]["ColName"]." from ".$this->Tables[$this->Fields[$i]["TableIdx"]]->TblName." where ".$this->Fields[$i]["ColName"]." is not null";
          }
        }
      }
      if ($this->IsCalculatedField($i)) {
        $oParseMain->arSelList[$i]=$this->Fields[$i]["Formula"] . " as _col".$i;
      }
      elseif ($this->Fields[$i]["TableIdx"] == $this->MainTbl) {
        if (substr($this->Fields[$i]["EntryType"],1) == "L" && array_key_exists("SelectSql",$this->Fields[$i])) {
          $oParseLookup->ParseSelect($this->Fields[$i]["SelectSql"]);
          if (count($oParseLookup->arSelList) == 2) {
            $oParseMain->AddJoin("left join ".$oParseLookup->FromClause." t".($i)." on t.".$this->Fields[$i]["ColName"]."=t".($i).".".$oParseLookup->arSelList[0]);
            $oParseMain->arSelList[$i]=$this->objDB->concat(array($oParseLookup->arSelList[1], "'<span class=\"ricoLookup\">'", $this->objDB->Convert2Char("t.".$this->Fields[$i]["ColName"]), "'</span>'"), false) . " as _col".$i;
            //"t." & Fields(i)("ColName")
          }
          else {
            $this->TableEditError("Invalid lookup query (".$this->Fields[$i]["SelectSql"].")");
            return;
          }
        }
        else {
          $oParseMain->arSelList[$i]="t.".$this->Fields[$i]["ColName"];
        }
      }
      else {
        $oParseMain->arSelList[$i]="select ".$this->Fields[$i]["ColName"]." from ".$this->Tables[$this->Fields[$i]["TableIdx"]]->TblName." a".$i;
        $oParseMain->arSelList[$i]=$oParseMain->arSelList[$i].$this->TableKeyWhereClause("t.");
        $oParseMain->arSelList[$i]="(".$oParseMain->arSelList[$i].$this->AltTableKeyWhereClause($this->Fields[$i]["TableIdx"]).")";
      }
    }
    $_SESSION[$this->gridID]=$oParseMain->UnparseSelect();
    $_SESSION[$this->gridID.".db"]=$this->objDB->DefaultDB();
    echo "\n<p class='ricoBookmark'>";
    echo "\n<span id='".$this->gridID."_timer' class='ricoSessionTimer'></span>";
    echo "&nbsp;&nbsp;<span id='".$this->gridID."_bookmark' class='ricoBookmark'></span>";
    echo "&nbsp;&nbsp;<span id='".$this->gridID."_savemsg' class='ricoSaveMsg'></span>";
    echo "\n</p>";
    echo "\n<div id='".$this->gridID."'></div>";
    echo "\n<script type='text/javascript'>";
    echo "\nvar ".$this->gridID."_GridOpts = {";
    foreach ($this->options as $o => $value) {
      if (!is_object($value) && !array_key_exists($o,$this->SvrOnly)) {
        echo "\n  ".$o.": ".$this->FormatOption($value).",";
      }
    }
    if ($this->CurrentPanel >= 0) {
      echo "\n  panels: [";
      for ($i=0; $i<=$this->CurrentPanel; $i++) {
        if ($i > 0) {
          echo ",";
        }
        echo "'".$this->Panels[$i]."'";
      }
      echo "],";
    }
    echo "\n  columnSpecs : [";
    for ($i=0; $i<=$this->FieldCnt; $i++) {
      if ($i > 0) {
        echo ",";
      }
      echo "\n    {";
      echo " FieldName:'".$this->ExtFieldId($i)."'";
      foreach ($this->Fields[$i] as $o => $value) {
        if (!is_object($value) && !array_key_exists($o,$this->SvrOnly)) {
          echo ",\n      ".$o.": ".$this->FormatOption($value);
        }
      }
      if (array_key_exists("ColInfo",$this->Fields[$i])) {
        echo ",\n      isNullable:".$this->FormatOption($this->Fields[$i]["ColInfo"]->Nullable);
        echo ",\n      Writeable:".$this->FormatOption($this->Fields[$i]["ColInfo"]->Writeable);
        echo ",\n      isKey:".$this->FormatOption($this->Fields[$i]["ColInfo"]->IsPKey);
        if ($this->Fields[$i]["ColInfo"]->ColLength) {
          echo ",\n      Length:".$this->Fields[$i]["ColInfo"]->ColLength;
        }
      }
      echo " }";
    }
    echo "\n  ]";
    echo "\n};";
    if ($this->AutoInit) {
      echo "\nRico.onLoad(function() {";
      //echo "\n  try {";
      echo "\n  if(typeof RicoUtil=='undefined') throw('LiveGridForms requires the RicoUtil Library');";
      echo "\n  if(typeof RicoTranslate=='undefined') throw('LiveGridForms requires the RicoTranslate Library');";
      echo "\n  if(typeof Rico.SimpleGrid=='undefined') throw('LiveGridForms requires the Rico.SimpleGrid Library');";
      echo "\n  if(typeof Rico.LiveGrid=='undefined') throw('LiveGridForms requires the Rico.LiveGrid Library');";
      echo "\n  if(typeof Rico.GridMenu=='undefined') throw('LiveGridForms requires the Rico.GridMenu Library');";
      echo "\n  if(typeof Rico.Buffer=='undefined') throw('LiveGridForms requires the Rico.Buffer Library');";
      echo "\n  if(typeof Rico.Buffer.AjaxSQL=='undefined') throw('LiveGridForms requires the Rico.Buffer.AjaxSQL Library');";
      echo "\n  if(typeof ".$this->gridID."_FormInit=='function') ".$this->gridID."_FormInit();";
      echo "\n  ".$this->InitScript();
      //echo "\n  } catch(e) { alert(e.message); };";
      echo "\n});";
    }
    echo "\n</script>";
  }

  function FormatOption($s) {
    switch (gettype($s)) {

      case 'string':
        $_retval="\"".$s."\"";
        break;

      case 'boolean':
        $_retval=$s ? 'true' : 'false';
        break;

      default:
        $_retval=$s;
        break;
    }
    return $_retval;
  }

  function InitScript() {
    return $this->gridID."_editobj"."=new Rico.TableEdit(new Rico.LiveGrid ('".$this->gridID."', new Rico.GridMenu(), new Rico.Buffer.AjaxSQL('".$this->options["XMLprovider"]."', {TimeOut:".(array_shift(session_get_cookie_params())/60)."}),".$this->gridID."_GridOpts));";
  }
}

// Helper Functions

function _instr($start,$str1,$str2,$mode) {
if ($mode) { $str1=strtolower($str1); $str2=strtolower($str2); }
$retval=strpos($str1,$str2,$start);
return ($retval===false) ? 0 : $retval+1;
}
?>
