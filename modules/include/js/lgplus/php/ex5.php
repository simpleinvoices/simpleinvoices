<?php
header("Cache-Control: no-cache");
header("Pragma: no-cache");
header("Expires: ".gmdate("D, d M Y H:i:s",time()+(-1*60))." GMT");
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>

<title>Sales Assignments</title>

<?php
require "applib.php";
require "chklang.php";
?>

<script src="../js/rico.js" type="text/javascript"></script>
<script type='text/javascript'>
Rico.loadModule('LiveGrid');
Rico.loadModule('CustomMenu');
Rico.include('greenHdg.css');
Rico.include('demo.css');
<?php
setLang();
?>

var menu,grid;

Rico.onLoad( function() {
  var gridopts = {  
    highlightElem    : 'selection',
    highlightSection : 2,
    canDragSelect    : true,
    frozenColumns    : 2,
    menuSection      : 2,
    visibleRows      : 'data',
    menuEvent        : 'contextmenu'
  };
  menu=new Rico.Menu('7em');
  menu.registerGrid = function(liveGrid) {
    this.liveGrid = liveGrid;
    this.createDiv();
    createSubMenus();
  };
  grid=new Rico.LiveGrid ('emptab', menu, new Rico.Buffer.Base($('emptab').tBodies[0]), gridopts);
});

function setEmployee(EmpId, Name) {
  menu.cancelmenu();
  grid.FillSelection(Name);
  grid.ShowSelection();
  grid.ClearSelection();
}
</script>

<style type="text/css">
div.container {
float:left;
margin-left:2%;
width:75%;
overflow:hidden; /* this is very important! */
}
</style>
</head>

<body>

<?php
require "menu.php";
?>

<div style='float:left;font-size:9pt;width:18%;color:blue;font-family:Verdana, Arial, Helvetica, sans-serif;'>
<p>In this scenario, you are a sales manager and you must assign your sales staff by customer and product category.
<p>Drag over cells to select them (doesn't work in FF if the cell is empty). You can also select using shift-click and ctrl-click. 
Ctrl-click cannot be used to select in Safari or Opera because this is the combination to invoke the context menu on those browsers.
<p>Once some cells are selected, right-click (ctrl-click in Opera or Safari) to select an employee from the pop-up menu.
<p>The selection will be filled with the selected employee. Notice that the
employee names and selections scroll with the grid.
</div>

<div class="container">

<?php
if (OpenDB()) {
  AppHeader("Sales Assignments By Customer &amp; Product Category");
  echo "<p class='ricoBookmark'><span id='emptab_bookmark' style='font-size:10pt;'>&nbsp;</span></p>";
  DisplayTable();
}
CloseApp();

function DisplayTable() {
  global $oDB;
  echo "<table id='emptab'>";
  echo "<thead><tr><th>ID</th><th>Company</th>";
  $rsLookup=$oDB->RunQuery("Select CategoryID, CategoryName From categories Order By CategoryName");
  while($oDB->db->FetchRow($rsLookup,$row)) {
    echo "<th style='width:100px;'>".$row[1]."</th>";
  }
  $oDB->rsClose($rsLookup);
  echo "</tr></thead><tbody>";
  $rsLookup=$oDB->RunQuery("Select CustomerID, CompanyName From customers Order By CompanyName");
  while($oDB->db->FetchRow($rsLookup,$row)) {
    echo "<tr><td>".$row[0]."</td><td>".$row[1]."</td></tr>";
  }
  $oDB->rsClose($rsLookup);
  echo "</tbody></table>";
  echo "\n<script type='text/javascript'>";
  echo "\nfunction createSubMenus() {";
  $rsLookup=$oDB->RunQuery("Select Country, EmployeeID, LastName From employees Order By Country, LastName");
  $LastCountry=null;
  while($oDB->db->FetchRow($rsLookup,$row)) {
    if ($LastCountry != $row[0]) {
      $LastCountry=$row[0];
      echo "\n  var submenu = new Rico.Menu();";
      echo "\n  submenu.createDiv();";
      echo "\n  menu.addSubMenuItem('".$row[0]."',submenu);";
    }
    echo "\n  submenu.addMenuItem('".$row[2]."',function() {setEmployee('".$row[1]."','".$row[2]."');});";
  }
  $oDB->rsClose($rsLookup);
  echo "\n}";
  echo "\n</script>";
}
?>

</div>
<!--
<textarea id='emptab_debugmsgs' rows='5' cols='80' style='font-size:smaller;'></textarea>
-->

</body>
</html>
