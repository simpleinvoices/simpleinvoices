<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
<title>Rico LiveGrid Plus-Example 1</title>

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

var ex1,buffer,lastVal=[];

Rico.onLoad( function() {
  var opts = {  
    <?php GridSettingsScript(); ?>,
    columnSpecs   : ['specQty']
  };
  var menuopts = <?php GridSettingsMenu(); ?>;
  new Rico.LiveGrid ('ex1', new Rico.GridMenu(menuopts), new Rico.Buffer.Base($('ex1').tBodies[0]), opts);
}

);


function keyfilter(txtbox,idx) {
  if (typeof lastVal[idx] != 'string') lastVal[idx]='';
  if (lastVal[idx]==txtbox.value) return;
  lastVal[idx]=txtbox.value;
  Rico.writeDebugMsg("keyfilter: "+idx+' '+txtbox.value);
  if (txtbox.value=='')
    ex1.columns[idx].setUnfiltered();
  else
    ex1.columns[idx].setFilter('LIKE',txtbox.value+'*',Rico.TableColumn.USERFILTER,function() {txtbox.value='';});
}
</script>

</head>

<body>

<?php
require "menu.php";
print "<table id='explanation' border='0' cellpadding='0' cellspacing='5' style='clear:both'><tr valign='top'><td>";
GridSettingsForm();
?>
</td><td>This example demonstrates a pre-filled grid (no AJAX data fetches). 
LiveGrid Plus just provides scrolling, column resizing, and sorting capabilities.
The first column sorts numerically, the others sort in text order.
Use the panel to the left to change grid settings.
Filtering is not supported on pre-filled grids.
</td></tr></table>

<p class="ricoBookmark"><span id="ex1_bookmark">&nbsp;</span></p>
<table id="ex1" class="ricoLiveGrid" cellspacing="0" cellpadding="0">
<colgroup>
<colgroup>
<col style='width:25%;' >
<col style='width:25%;' >
<col style='width:25%;'>
<col style='width:25%'>
</colgroup>
<thead><tr id='ex1_main'>
<th  class='ricoFrozen'>Action</th>
<th>Client</th>
<th>Biller</th>
<th>Product</th>
</tr>
  <tr class='dataInput'>
	  <th class='ricoFrozen'><input type='text' onkeyup='keyfilter(this,0)' size='5'></th>
	  <th><input type='text' onkeyup='keyfilter(this,1)' size='5'></th>
	  <th><input type='text' onkeyup='keyfilter(this,2)'></th>
	  <th><input type='text' onkeyup='keyfilter(this,3)'></th>
  </tr>


</thead><tbody>
<tr>
<td>1
</td>
<td>asd
</td>
<td>dfgf
</td>
<td>dfg
</td>
</tr>
<tr>
<td>12
</td>
<td>asd
</td>
<td>dfgf
</td>
<td>dfg
</td>
</tr>
<tr>
<td>45
</td>
<td>asd
</td>
<td>dfgf
</td>
<td>dfg
</td>
</tr>
<tr>
<td>134223
</td>
<td>asd
</td>
<td>dfgf
</td>
<td>dfg
</td>
</tr>
<tr>
<td>100
</td>
<td>asd
</td>
<td>dfgf
</td>
<td>dfg
</td>
</tr>
<tr>
<td>23
</td>
<td>asd
</td>
<td>dfgf
</td>
<td>dfg
</td>
</tr>
<tr>
<td>435
</td>
<td>asd
</td>
<td>dfgf
</td>
<td>dfg
</td>
</tr>
<tr>
<td>456456
</td>
<td>as234d
</td>
<td>123123123
</td>
<td>dfg
</td>
</tr>
</tbody></table>

<!--
<textarea id='ex1_debugmsgs' rows='5' cols='80' style='font-size:smaller;'></textarea>
-->

</body>
</html>

