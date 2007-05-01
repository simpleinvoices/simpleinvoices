<?php
if (!isset ($_SESSION)) session_start();
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
<title>Rico LiveGrid Plus-Example 3</title>

<?php
$sqltext="SELECT id,name,email,(CASE WHEN enabled = 1 THEN 'Enabled' WHEN enabled = 0 THEN 'Disabled'	ELSE '??' END) as enabled FROM {$tb_prefix}biller";

$_SESSION['ex3']=$sqltext;
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

var ex3,buffer,lastVal=[];

Rico.onLoad( function() {
  var opts = {  
    frozenColumns : 1,
    canFilterDefault: false,
// columnSpecs   : [,{type:'control',control:new Rico.TableColumn.link('ex2.php?id={0}','_blank')},,]
// columnSpecs   : [,,,]

    headingRow    : 1
  };
  var menuopts = <?php GridSettingsMenu(); ?>;
  buffer=new Rico.Buffer.AjaxSQL('ricoXMLquery.php', {TimeOut:<?php print array_shift(session_get_cookie_params())/60 ?>});
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

<?php
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
</colgroup>
<thead>
  <tr id='ex3_main'>
	  <th class='ricoFrozen'>id</th>
	  <th>name</th>
	  <th>email</th>
	  <th>enabled</th>
  </tr>
  <tr class='dataInput'>
	  <th class='ricoFrozen'><input type='text' onkeyup='keyfilter(this,0)' size='5'></th>
	  <th><input type='text' onkeyup='keyfilter(this,1)' size='5'></th>
	  <th><input type='text' onkeyup='keyfilter(this,2)'></th>
	  <th><input type='text' onkeyup='keyfilter(this,3)'></th>
  </tr>
</thead>
</table>

<!--
<textarea id='ex3_debugmsgs' rows='5' cols='80' style='font-size:smaller;'></textarea>
-->

</body>
</html>

