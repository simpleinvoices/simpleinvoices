<?php
if (!isset ($_SESSION)) session_start();
header("Cache-Control: no-cache");
header("Pragma: no-cache");
header("Expires: ".gmdate("D, d M Y H:i:s",time()+(-1*60))." GMT");
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
<title>LiveGrid Plus-Edit Example</title>
<script src="../js/rico.js" type="text/javascript"></script>

<?php
$sqltext=".";  // force filtering to "on" in settings box
require "applib.php";
require "ricoLiveGridForms.php";
require "chklang.php";
require "settings.php";
?>

<script type='text/javascript'>
Rico.include('demo.css');
Rico.loadModule('LiveGridForms');
Rico.loadModule('Calendar');
Rico.loadModule('Tree');
<?php
setStyle();
setLang();
?>

// Results of Rico.loadModule may not be immediately available!
// In which case, "new Rico.CalendarControl" would fail if executed immediately.
// Therefore, wrap it in a function.
// ricoLiveGridForms will call orders_FormInit right before grid & form initialization.

function orders_FormInit() {
  var cal=new Rico.CalendarControl("Cal");
  RicoEditControls.register(cal, Rico.imgDir+'calarrow.png');
  cal.addHoliday(25,12,0,'Christmas','#F55','white');
  cal.addHoliday(4,7,0,'Independence Day-US','#88F','white');
  cal.addHoliday(1,1,0,'New Years','#2F2','white');
  
  var CustTree=new Rico.TreeControl("CustomerTree","CustTree.php");
  RicoEditControls.register(CustTree, Rico.imgDir+'dotbutton.gif');
}

function debug() {
  with (orders_editobj.grid) {
    var msg=tableId;
    msg+='\ntabs wi='+tabs[0].offsetWidth+' '+tabs[1].offsetWidth;
    msg+='\nfrozenTabs wi='+frozenTabs.style.width+' '+frozenTabs.offsetWidth;
    msg+='\nrow0 wi='+hdrCells[0][0].hdrColDiv.style.width+' '+hdrCells[0][0].cell.offsetWidth;
    for (var c=0; c<columns.length; c++)
      msg+='\ncol '+c+' '+columns[c].colWidth+' '+columns[c].hdrColDiv.offsetWidth;
  }
  alert(msg);
}
</script>
<style type="text/css">
div.ricoLG_outerDiv thead .ricoLG_cell, div.ricoLG_outerDiv thead td, div.ricoLG_outerDiv thead th {
	height:2.5em;
}
</style>
</head>
<body>

<?php
require "menu.php";

//************************************************************************************************************
//  LiveGrid Plus-Edit Example
//************************************************************************************************************
//  Matt Brown
//************************************************************************************************************
if (OpenTE("", "orders")) {
  if ($oTE->action == "table") {
    DisplayTable();
  }
  else {
    DefineFields();
  }
} else {
  echo 'open failed';
}
CloseApp();

function DisplayTable() {
  echo "<table id='explanation' border='0' cellpadding='0' cellspacing='5' style='clear:both'><tr valign='top'><td>";
  GridSettingsForm();
  echo "</td><td>This example demonstrates how database records can be updated via AJAX. ";
  echo "Try selecting add, edit, or delete from the pop-up menu. ";
  echo "If you select add, then click the '...' button next to customer, you will see the Rico tree control.";
  echo "The actual database updates have been disabled for security reasons and result in an error.";
  echo "</td></tr></table>";
  $GLOBALS['oTE']->options["borderWidth"]=0;
  GridSettingsTE($GLOBALS['oTE']);
  //$GLOBALS['oTE']->options["DebugFlag"]=true;
  //$GLOBALS['oDB']->debug=true;
  DefineFields();
  //echo "<p><textarea id='orders_debugmsgs' rows='5' cols='80' style='font-size:smaller;'></textarea>";
}

function DefineFields() {
  global $oTE,$oDB;
  $oTE->options["RecordName"]="order";
  $oTE->AddPanel("Basic Info");
  $oTE->AddEntryField("OrderID", "Order ID", "B", "<auto>");
  $oTE->ConfirmDeleteColumn();
  $oTE->CurrentField["width"]=50;
  $oTE->AddEntryField("CustomerID", "Customer", "CL", "");
  $oTE->CurrentField["SelectSql"]="select CustomerID,CompanyName from customers order by CompanyName";
  $oTE->CurrentField["SelectCtl"]="CustomerTree";
  $oTE->CurrentField["ReadOnly"]=true;
  $oTE->CurrentField["width"]=160;
  $oTE->AddEntryField("EmployeeID", "Sales Person", "SL", "");
  $oTE->CurrentField["SelectSql"]="select EmployeeID,".$oDB->concat(array("LastName", "', '", "FirstName"), false)." from employees order by LastName,FirstName";
  $oTE->CurrentField["width"]=140;
  $oTE->AddEntryField("OrderDate", "Order Date", "D", strftime('%Y-%m-%d'));
  $oTE->CurrentField["SelectCtl"]="Cal";
  $oTE->CurrentField["width"]=90;
  $oTE->AddEntryField("RequiredDate", "Required Date", "D", strftime('%Y-%m-%d'));
  $oTE->CurrentField["SelectCtl"]="Cal";
  $oTE->CurrentField["width"]=90;
  $oTE->AddCalculatedField("select sum(UnitPrice*Quantity*(1.0-Discount)) from nworderdetails d where d.OrderID=t.OrderID","Net Price");
  $oTE->CurrentField["format"]="DOLLAR";
  $oTE->CurrentField["width"]=80;
  $oTE->AddPanel("Ship To");
  $oTE->AddEntryField("ShipName", "Name", "B", "");
  $oTE->CurrentField["width"]=140;
  $oTE->AddEntryField("ShipAddress", "Address", "B", "");
  $oTE->CurrentField["width"]=140;
  $oTE->AddEntryField("ShipCity", "City", "B", "");
  $oTE->CurrentField["width"]=120;
  $oTE->AddEntryField("ShipRegion", "Region", "T", "");
  $oTE->CurrentField["width"]=60;
  $oTE->AddEntryField("ShipPostalCode", "Postal Code", "T", "");
  $oTE->AddEntryField("ShipCountry", "Country", "N", "");
  //oTE.AutoInit=false
  $oTE->DisplayPage();
}
?>


</body>
</html>
