<?php
if (!isset ($_SESSION)) session_start();
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
<title>Rico LiveGrid Plus-Example 2</title>

<?php
require "applib.php";

session_set_cookie_params(60*60);
//$sqltext="select OrderID,CustomerID,LastName,ShipCity,ShipCountry,OrderDate,ShippedDate from Orders, Employees where Orders.EmployeeID=Employees.EmployeeID";
$sqltext="select OrderID,CustomerID,ShipName,ShipCity,ShipCountry,OrderDate,ShippedDate from nworders";
if (isset($_GET["id"])) {
  OpenDB(); // the addQuotes function requires a db connection when using MySQL
  $id=trim($_GET["id"]);
  if (strlen($id) == 5) $sqltext.=" where CustomerID=".$GLOBALS['oDB']->addQuotes($id);
  CloseApp();
}
$_SESSION['ex2']=$sqltext;

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
var orderGrid,buffer;

Rico.onLoad( function() {
  var opts = {  
    <?php GridSettingsScript(); ?>,
    columnSpecs   : [,,,,,{type:'date'},{type:'date'}]
  };
  var menuopts = <?php GridSettingsMenu(); ?>;
  buffer=new Rico.Buffer.AjaxSQL('ricoXMLquery.php', {TimeOut:<?php print array_shift(session_get_cookie_params())/60 ?>});
  orderGrid=new Rico.LiveGrid ('ex2', new Rico.GridMenu(menuopts), buffer, opts);
});

</script>

</head>

<body>

<?php
require "menu.php";
print "<table id='explanation' border='0' cellpadding='0' cellspacing='5' style='clear:both'><tr valign='top'><td>";
GridSettingsForm();

?>
</td><td>This example uses AJAX to fetch order data as required. 
It also demonstrates how the number of rows can be set automatically based
on the size of the window.
</td></tr></table>

<p class="ricoBookmark"><span id='ex2_timer' class='ricoSessionTimer'></span>&nbsp;&nbsp;<span id="ex2_bookmark"></span></p>
<table id="ex2" class="ricoLiveGrid" cellspacing="0" cellpadding="0">
<colgroup>
<col style='width:40px;' >
<col style='width:60px;' >
<col style='width:150px;'>
<col style='width:80px;' >
<col style='width:90px;' >
<col style='width:100px;'>
<col style='width:100px;'>
</colgroup>
  <tr>
	  <th>Order#</th>
	  <th>Customer#</th>
	  <th>Ship Name</th>
	  <th>Ship City</th>
	  <th>Ship Country</th>
	  <th>Order Date</th>
	  <th>Ship Date</th>
  </tr>
</table>
<!--
<textarea id='ex2_debugmsgs' rows='5' cols='80'></textarea>
-->
</body>
</html>

