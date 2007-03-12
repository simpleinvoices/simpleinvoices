<?php

class ricoXmlResponse {

  // public properties
  var $orderByRef;
  var $sendDebugMsgs;
  var $readAllRows;    // always return the total number of rows? (if true, the user will always see the total number of rows, but there is a small performance hit)
  
  // private properties
  var $objDB;
  var $eof;
  var $oParse;
  var $sqltext;

  function ricoXmlResponse() {
    if (is_object($GLOBALS['oDB'])) {
      $this->objDB=$GLOBALS['oDB'];   // use oDB global as database connection, if it exists
    }
    $this->orderByRef=false;
    $this->sendDebugMsgs=false;
    $this->readAllRows=true;
  }

  // ASSUMES SELECT LIST DOES NOT CONTAIN ANY "AS" (OR COLNAME=) CLAUSES
  // All Oracle and SQL Server 2005 queries *must* have an ORDER BY clause

  function Query2xml($sqlselect, $offset, $numrows, $gettotal) {
    $this->oParse= new sqlParse();
    $this->oParse->ParseSelect($sqlselect);
    $this->ApplyQStringParms();
    echo "\n<rows update_ui='true' offset='".$offset."'>";
    if ($numrows >= 0) {
      $Dialect=$this->objDB->Dialect;
    }
    else {
      $numrows=999;
    }
    switch ($Dialect) {

      case "TSQL":
        $this->objDB->SingleRecordQuery("select @@VERSION", $version);
        if (_instr(0,$version[0],"SQL Server 2005",0) > 0) {
          $this->sqltext=$this->UnparseWithRowNumber($offset, $numrows + 1, true);
          $totcnt=$this->Query2xmlRaw_Limit($this->sqltext, $offset, $numrows, 1);
        }
        else {
          $this->sqltext=$this->oParse->UnparseSelect();
          $totcnt=$this->Query2xmlRaw($this->sqltext, $offset, $numrows);
        }
        break;

      case "Oracle":
        $this->sqltext=$this->UnparseWithRowNumber($offset, $numrows + 1, false);
        $totcnt=$this->Query2xmlRaw_Limit($this->sqltext, $offset, $numrows, 1);
        break;

      case "MySQL":
        $this->sqltext=$this->oParse->UnparseSelect()." LIMIT ".($numrows + 1)." OFFSET ".$offset;
        $totcnt=$this->Query2xmlRaw_Limit($this->sqltext, $offset, $numrows, 0);
        break;

      default:
        $this->sqltext=$this->oParse->UnparseSelect();
        $totcnt=$this->Query2xmlRaw($this->sqltext, $offset, $numrows);
        break;
    }
    echo "\n</rows>";
    if (!$this->eof && $gettotal) {
      $totcnt=$this->getTotalRowCount();
    }
    if ($this->eof) {
      echo "\n<rowcount>".$totcnt."</rowcount>";
    }
    if ($this->sendDebugMsgs) {
      echo "\n<debug>".htmlspecialchars($sqlselect)."</debug>";
      echo "\n<debug>".htmlspecialchars($this->sqltext)."</debug>";
    }
    $this->oParse=NULL;
    return $totcnt;
  }


  // this still needs some work based on SQL dialect (tested ok with SQL Server 2005 & MySQL)
  function getTotalRowCount() {
    $countSql="SELECT ".implode(",",$this->oParse->arSelList)." FROM ".$this->oParse->FromClause;
    if (!empty($this->oParse->WhereClause)) {
      $countSql.=" WHERE ".$this->oParse->WhereClause;
    }
    if (is_array($this->oParse->arGroupBy)) {
      if (count($this->oParse->arGroupBy) >  0) {
        $countSql.=" GROUP BY ".implode(",",$this->oParse->arGroupBy);
      }
    }
    if (!empty($this->oParse->HavingClause)) {
      $countSql.=" HAVING ".$this->oParse->HavingClause;
    }
    $countSql="SELECT COUNT(*) FROM (".$countSql.") AS _ricoMain";
    if ($this->sendDebugMsgs) {
      echo "\n<debug>".htmlspecialchars($countSql)."</debug>";
    }
    if ($this->objDB->SingleRecordQuery($countSql, $cnt)) {
      $this->eof=true;
      return $cnt[0];
    }
  }

  function UnparseWithRowNumber($offset, $numrows, $includeAS) {
    if (is_array($this->oParse->arOrderBy)) {
      if (count($this->oParse->arOrderBy) >  0) {
        $strOrderBy=implode(",",$this->oParse->arOrderBy);
      }
    }
    if (empty($strOrderBy)) {
      // order by clause should be included in main sql select statement
      // However, if it isn't, then use primary key as sort - assuming FromClause is a simple table name
      $strOrderBy=$this->objDB->PrimaryKey[$this->oParse->FromClause];
    }
    $unparseText="SELECT ROW_NUMBER() OVER (ORDER BY ".$strOrderBy.") AS _rownum,";
    $unparseText.=implode(",",$this->oParse->arSelList)." FROM ".$this->oParse->FromClause;
    if (!empty($this->oParse->WhereClause)) {
      $unparseText.=" WHERE ".$this->oParse->WhereClause;
    }
    if (is_array($this->oParse->arGroupBy)) {
      if (count($this->oParse->arGroupBy) >  0) {
        $unparseText.=" GROUP BY ".implode(",",$this->oParse->arGroupBy);
      }
    }
    if (!empty($this->oParse->HavingClause)) {
      $unparseText.=" HAVING ".$this->oParse->HavingClause;
    }
    $unparseText="SELECT * FROM (".$unparseText.")";
    if ($includeAS) {
      $unparseText.=" AS _ricoMain";
    }
    $unparseText.=" WHERE _rownum > ".$offset." AND _rownum <= ".($offset + $numrows);
    return $unparseText;
  }

  function Query2xmlRaw($rawsqltext, $offset, $numrows) {
    $rsMain=$this->objDB->RunQuery($rawsqltext);
    if (!$rsMain) return;
  
    $colcnt = $this->objDB->db->NumFields($rsMain);
    $totcnt = $this->objDB->db->NumRows($rsMain);
    if ($offset < $totcnt || $totcnt==-1)
    {
      $rowcnt=0;
      $this->objDB->db->Seek($rsMain,$offset);
      while(($this->objDB->db->FetchRow($rsMain,$row)) && $rowcnt < $numrows)
      {
        $rowcnt++;
        print "\n<tr>";
        for ($i=0; $i < $colcnt; $i++)
          print $this->XmlStringCell($row[$i]);
        print "</tr>";
      }
      if ($totcnt < 0) {
        $totcnt=$offset+$rowcnt;
        while($this->objDB->db->FetchRow($rsMain,$row))
          $totcnt++;
      }
    }
    else
    {
      $totcnt=$offset;
    }
    $this->objDB->rsClose($rsMain);
    return $totcnt;
  }

  function Query2xmlRaw_Limit($rawsqltext, $offset, $numrows, $firstcol) {
    $rsMain=$this->objDB->RunQuery($rawsqltext);
    $totcnt=$offset;
    $this->eof=true;
    if (!$rsMain) return;
    $colcnt = $this->objDB->db->NumFields($rsMain);
    $rowcnt=0;
    while(($this->objDB->db->FetchRow($rsMain,$row)) && $rowcnt < $numrows)
    {
      $rowcnt++;
      print "\n<tr>";
      for ($i=$firstcol; $i < $colcnt; $i++)
        print $this->XmlStringCell($row[$i]);
      print "</tr>";
    }
    $totcnt+=$rowcnt;
    $this->eof=($rowcnt < $numrows);
    $this->objDB->rsClose($rsMain);
    return $totcnt;
  }

  function SetDbConn(&$dbcls) {
    $this->objDB=&$dbcls;
  }

  function ApplyQStringParms() {
    foreach($_GET as $qs => $value) {
      switch (substr($qs,0,1)) {

        case "s":
          $i=intval(substr($qs,1));
          $value=stripslashes($value);
          if ($this->orderByRef)
            $this->oParse->AddSort(($i + 1)." ".$value);
          else
            $this->oParse->AddSort($this->oParse->arSelList[$i]." ".$value);
          break;

        case "f":
          //print_r($value);
          foreach($value as $i => $filter) {
            $newfilter=$this->oParse->arSelList[$i];
            switch ($filter['op']) {
              case "EQ":  $newfilter.="=".$this->objDB->addQuotes($filter[0]); break;
              case "LE":  $newfilter.="<=".$this->objDB->addQuotes($filter[0]); break;
              case "GE":  $newfilter.=">=".$this->objDB->addQuotes($filter[0]); break;
              case "NULL": $newfilter.=" is null"; break;
              case "NOTNULL": $newfilter.=" is not null"; break;
              case "LIKE": $newfilter.=" LIKE ".$this->objDB->addQuotes(str_replace("*",$this->objDB->Wildcard,$filter[0])); break;
              case "NE":
                $flen=intval($filter['len']);
                $newfilter.=" NOT IN (";
                for ($j=0; $j<$flen; $j++) {
                  if ($j > 0) $newfilter.=",";
                  $newfilter.=$this->objDB->addQuotes($filter[$j]);
                }
                $newfilter.=")";
                break;
            }
            if (preg_match("/\bmin\(|\bmax\(|\bsum\(|\bcount\(/i",$this->oParse->arSelList[$i]))
              $this->oParse->AddHavingCondition($newfilter);
            else
              $this->oParse->AddWhereCondition($newfilter);
          }
          break;
      }
    }
  }

  function XmlStringCell($value) {
    if (!isset($value)) {
      $result="";
    }
    else {
      $result=htmlspecialchars($value);
    }
    return "<td>".$result."</td>";
  }

  // for the root node, parentID should "" (empty string)
  // containerORleaf: L/zero (leaf), C/non-zero (container)
  // selectable:      0->not selectable, 1->selectable
  function WriteTreeRow($parentID, $ID, $description, $containerORleaf, $selectable) {
    echo "\n<tr>";
    echo $this->XmlStringCell($parentID);
    echo $this->XmlStringCell($ID);
    echo $this->XmlStringCell($description);
    echo $this->XmlStringCell($containerORleaf);
    echo $this->XmlStringCell($selectable);
    echo "</tr>";
  }

}

?>

