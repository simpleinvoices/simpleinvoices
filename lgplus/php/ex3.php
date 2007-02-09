<?
if (!isset ($_SESSION)) session_start();
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
<title>Rico LiveGrid Plus-Example 3</title>

<? 
$sqltext="select OrderID,CustomerID,ShipName,ShipCity,ShipCountry,OrderDate,ShippedDate from nworders";
$_SESSION['ex3']=$sqltext;
require "chklang.php";
require "settings.php";
?>

<script src="../js/rico.js" type="text/javascript"></script>
<script type='text/javascript'>
Rico.loadModule('LiveGridAjax');
Rico.loadModule('LiveGridMenu');
Rico.include('demo.css');
<?
setStyle();
setLang();
?>

var ex3,buffer,lastVal=[];

Rico.onLoad( function() {
  var opts = {  
    frozenColumns : 1,
    canFilterDefault: false,
    columnSpecs   : [,,,,,{type:'date'},{type:'date'}],
    headingRow    : 1
  };
  var menuopts = <? GridSettingsMenu(); ?>;
  buffer=new Rico.Buffer.AjaxSQL('ricoXMLquery.php', {TimeOut:<? print array_shift(session_get_cookie_params())/60 ?>});
  ex3=new Rico.LiveGrid ('ex3', new Rico.GridMenu(menuopts), buffer, opts);
});

function keyfilter(txtbox,idx) {
  if (typeof lastVal[idx] != 'string') lastVal[idx]='';
  if (lastVal[idx]==txtbox.value) return;
  lastVal[idx]=txtbox.value;
  Rico.writeDebugMsg("keyfilter: "+idx+' '+txtbox.value);
  if (txtbox.value=='')
    ex3.columns[idx].setUnfiltered();
  else
    ex3.columns[idx].setFilter('LIKE',txtbox.value+'*',Rico.TableColumn.USERFILTER,function() {txtbox.value='';});
}
</script>

<style type="text/css">
input { font-weight:normal;font-size:8pt;}
th div.ricoLG_cell { height:1.5em; }  /* the text boxes require a little more height than normal */
</style>

</head>

<body>

<?
require "menu.php";
print "<table id='explanation' border='0' cellpadding='0' cellspacing='5' style='clear:both'><tr valign='top'><td>";
GridSettingsForm();
?>
</td><td>This grid demonstrates how filters can be applied as the user types.
Frozen columns would normally be set to 2 for this grid, but feel free to try other values.
</td></tr></table>

<p class="ricoBookmark"><span id="ex3_bookmark"></span></p>
<table id="ex3" class="ricoLiveGrid" cellspacing="0" cellpadding="0">
<colgroup>
<col style='width:10%;' >
<col style='width:10%;' >
<col style='width:20%;'>
<col style='width:20%;' >
<col style='width:20%' >
<col style='width:10%'>
<col style='width:10%'>
</colgroup>
<thead>
  <tr>
	  <th class='ricoFrozen'>ID</th>
	  <th>ID</th>
	  <th colspan='3'>Shipment</th>
	  <th colspan='2'>Date</th>
  </tr>
  <tr id='ex3_main'>
	  <th class='ricoFrozen'>Order</th>
	  <th>Customer</th>
	  <th>Name</th>
	  <th>City</th>
	  <th>Country</th>
	  <th>Order</th>
	  <th>Ship</th>
  </tr>
  <tr class='dataInput'>
	  <th class='ricoFrozen'><input type='text' onkeyup='keyfilter(this,0)' size='5'></th>
	  <th><input type='text' onkeyup='keyfilter(this,1)' size='5'></th>
	  <th><input type='text' onkeyup='keyfilter(this,2)'></th>
	  <th><input type='text' onkeyup='keyfilter(this,3)'></th>
	  <th><input type='text' onkeyup='keyfilter(this,4)'></th>
	  <th>&nbsp;</th>
	  <th>&nbsp;</th>
  </tr>
</thead>
</table>

<!--
<textarea id='ex3_debugmsgs' rows='5' cols='80' style='font-size:smaller;'></textarea>
-->

</body>
</html>

