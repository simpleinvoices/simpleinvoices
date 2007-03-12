<?php
if (!isset ($_SESSION)) session_start();
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
<title>Rico LiveGrid Plus-Example 6</title>

<?php
$sqltext="select CustomerID,ShipName,year(ShippedDate),count(*) from nworders group by CustomerID,ShipName,year(ShippedDate)";
$_SESSION['ex8']=$sqltext;

require "applib.php";
require "chklang.php";
require "settings.php";
?>

<script src="../js/rico.js" type="text/javascript"></script>
<script type='text/javascript'>
Rico.loadModule('LiveGridAjax');
Rico.loadModule('LiveGridMenu');
Rico.include('demo.css');
<?php
setStyle();
setLang();
?>

var ex8;

function setFilter() {
  for (var i=0; i<yrboxes.length; i++) {
    if (yrboxes[i].checked==true) {
      var yr=yrboxes[i].value;
      ex8.columns[2].setSystemFilter('EQ',yr);
      return;
    }
  }
}

Rico.onLoad( function() {
  yrboxes=document.getElementsByName('year');
  var opts = {  
    <?php GridSettingsScript(); ?>,
    prefetchBuffer: false,
    columnSpecs   : [,{type:'control',control:new Rico.TableColumn.link('ex2.php?id={0}','_blank'),width:250},,'specQty']
  };
  var menuopts = <?php GridSettingsMenu(); ?>;
  var buffer=new Rico.Buffer.AjaxSQL('ricoXMLquery.php', {TimeOut:<?php print array_shift(session_get_cookie_params())/60 ?>});
  ex8=new Rico.LiveGrid ('ex8', new Rico.GridMenu(menuopts), buffer, opts);
  setFilter();
});
</script>

<style type="text/css">
td div.ricoLG_cell { height:1.5em; }  /* the check boxes require a little more height than normal */
td.ex8_col0,
td.ex8_col2,
td.ex8_col3 { text-align:center; }
</style>

</head>

<body>

<?php
require "menu.php";
print "<table id='explanation' border='0' cellpadding='0' cellspacing='5' style='clear:both'><tr valign='top'><td>";
GridSettingsForm();
?>
</td><td>This grid uses grouping and a where clause in the select statement. 
It also places a checkbox on every row.
The checkboxes are only included to demonstrate a capability,
they don't actually do anything useful in this example.
Finally, it shows how to apply a filter to the initial data set - even though that filter may change later.
</td></tr></table>

<p>Count orders for: 
<input type='radio' name='year' onclick='setFilter()' value='1996' checked>&nbsp;1996
<input type='radio' name='year' onclick='setFilter()' value='1997'>&nbsp;1997
</p>

<p class="ricoBookmark"><span id="ex8_bookmark"></span></p>
<table id="ex8" class="ricoLiveGrid" cellspacing="0" cellpadding="0">
<colgroup>
<col style='width:40px;' >
<col style='width:60px;' >
<col style='width:40px;' >
<col style='width:40px;' >
</colgroup>
  <tr>
	  <th>Select</th>
	  <th>Customer#</th>
	  <th>Year</th>
	  <th>Order Count</th>
  </tr>
</table>
<!--
<textarea id='ex8_debugmsgs' rows='5' cols='80'>
-->
</body>
</html>

