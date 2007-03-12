<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
<title>Rico LiveGrid Plus-Example 7</title>

<?php
require "chklang.php";
require "settings.php";
?>

<script src="../js/rico.js" type="text/javascript"></script>
<script type='text/javascript'>
Rico.loadModule('LiveGrid');
Rico.loadModule('LiveGridMenu');
Rico.include('demo.css');
<?php
setStyle();
setLang();
?>

var grid,buffer;

//    columnSpecs   : [{canHide:false,type:'control',control:new Rico.TableColumn.lookup({1:'A',0:'Z'}),ClassName:'aligncenter'},'specQty'],
Rico.onLoad( function() {
  var opts = {  
    columnSpecs   : [{canHide:false,type:'control',control:new Rico.TableColumn.checkbox('1','0'),ClassName:'aligncenter'},'specQty'],
    <?php GridSettingsScript(); ?>,
    offset        : 20  // first row to display
  };
  var menuopts = <?php GridSettingsMenu(); ?>;
  buffer=new Rico.Buffer.Base($('ex7').tBodies[0]);
  grid=new Rico.LiveGrid ('ex7', new Rico.GridMenu(menuopts), buffer, opts);
});
</script>

<style type="text/css">
div.ricoLG_cell { 
height:1.5em;
white-space: nowrap;
}  /* the check boxes require a little more height than normal */
td.ex7_col_0 { text-align:center; }
</style>

</head>

<body>

<?php
require "menu.php";
print "<table id='explanation' border='0' cellpadding='0' cellspacing='5' style='clear:both'><tr valign='top'><td>";
GridSettingsForm();
?>
</td><td>This example demonstrates a pre-filled grid (same as example 1),
except that checkboxes have been placed in the first column. 
Click on a checkbox - notice that the row is identified in the alert message
and that the box stays checked as the grid scrolls.
It also demonstrates how the grid can be initialized to start at a specified row
(this example skips the first 20 rows). Finally, it also shows how sorting and hide/show
can be disabled for individual columns (the first column in this example).
</td></tr></table>

<p class="ricoBookmark"><span id="ex7_bookmark">&nbsp;</span></p>
<table id="ex7" class="ricoLiveGrid" cellspacing="0" cellpadding="0">
<colgroup>
<?php
$numcol=12;
for ($c=1; $c<=$numcol; $c++) {
  echo "<col style='width:80px;' />";
}
?>
</colgroup>
<thead><tr>
<?php
for ($c=1; $c<=$numcol; $c++) {
  echo "<th>Column $c</th>";
}
?>
</tr></thead><tbody>
<?php
for ($r=1; $r<=100; $r++) {
  echo "<tr>";
  echo "<td>";
  echo ($r % 10 == 0 ? "1" : "0");
  echo "</td>";
  echo "<td>$r</td>";
  for ($c=3; $c<=$numcol; $c++) {
    echo "<td>Cell $r:$c</td>";
  }
  echo "</tr>";
}
?>
</tbody></table>

<!--
<textarea id='ex7_debugmsgs' rows='5' cols='80' style='font-size:smaller;'></textarea>
-->
</body>
</html>

