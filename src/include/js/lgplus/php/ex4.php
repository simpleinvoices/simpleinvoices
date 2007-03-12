<?php
if (!isset ($_SESSION)) session_start();
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
<title>Rico LiveGrid Plus-Example 4</title>

<?php
require "chklang.php";

$_SESSION['customergrid']="select CustomerID,CompanyName,ContactName,Address,City,Region,PostalCode,Country,Phone,Fax from nwcustomers order by CustomerID";
$_SESSION['ordergrid']="select CustomerID,OrderID,ShipName,ShipCity,ShipCountry,OrderDate,ShippedDate from nworders order by OrderID";
$_SESSION['detailgrid']="select OrderID,p.ProductName,QuantityPerUnit,od.UnitPrice,Quantity,od.UnitPrice*Quantity as 'Total',Discount,od.UnitPrice*Quantity*(1.0-Discount) as 'Net Price' from nworderdetails od left join nwproducts p on od.ProductID=p.ProductID order by od.ProductID";
?>

<script src="../js/rico.js" type="text/javascript"></script>
<script type="text/javascript">
Rico.loadModule('LiveGridAjax');
Rico.loadModule('LiveGridMenu');
Rico.include('greenHdg.css');
Rico.include('demo.css');
<?php
setLang();
?>

var customerGrid, orderGrid, detailGrid;

Rico.onLoad( function() {
  if (!Rico) {
    setTimeout(bodyOnLoad,50);
    return;
  }
  var opts = {  prefetchBuffer: true,
                frozenColumns : 2,
                dblclick      : customerDrillDown,
                menuEvent     : 'contextmenu',
                visibleRows   : 4
             };
  customerGrid=new Rico.LiveGrid ('customergrid', new Rico.GridMenu(), new Rico.Buffer.AjaxSQL('ricoXMLquery.php'), opts);

  var opts = {  prefetchBuffer: false,
                columnSpecs   : [{canSort:false,visible:false},,,,,{type:'date'},{type:'date'}],
                canFilterDefault: false,
                dblclick      : orderDrillDown,
                menuEvent     : 'contextmenu',
                visibleRows   : 4
             };
  orderGrid=new Rico.LiveGrid ('ordergrid', new Rico.GridMenu(), new Rico.Buffer.AjaxSQL('ricoXMLquery.php'), opts);

  var opts = {  prefetchBuffer: false,
                columnSpecs   : [{canSort:false,visible:false},,,'specDollar','specQty','specDollar','specPercent','specDollar'],
                canFilterDefault: false,
                menuEvent     : 'contextmenu',
                visibleRows   : 4
             };
  detailGrid=new Rico.LiveGrid ('detailgrid', new Rico.GridMenu(), new Rico.Buffer.AjaxSQL('ricoXMLquery.php'), opts);
});

var custid,orderid;

function customerDrillDown(e) {
  var cell=Event.element(e);
  Event.stop(e);
  var a=cell.id.split(/_/);
  var l=a.length;
  var r=parseInt(a[l-2]);
  if (r < customerGrid.buffer.totalRows) {
    custid=customerGrid.columns[0].getValue(r);
    $("custid").innerHTML=custid;
    $("orderid").innerHTML="";
    orderGrid.columns[0].setSystemFilter("EQ",custid);
    detailGrid.resetContents();
  }
  return false;
}

function orderDrillDown(e) {
  var cell=Event.element(e);
  Event.stop(e);
  var a=cell.id.split(/_/);
  var l=a.length;
  var r=parseInt(a[l-2]);
  if (r < orderGrid.buffer.totalRows) {
    orderid=orderGrid.columns[1].getValue(r);
    $("orderid").innerHTML=orderid;
    detailGrid.columns[0].setSystemFilter("EQ",orderid);
  }
  return false;
}

function detailDataMenu(objCell,onBlankRow) {
  return !onBlankRow;
}

</script>

<style type="text/css">
div.container {
float:left;
margin-left:2%;
width:75%;
overflow:visible;
}

div.ricoLG_cell {
font-size: 8pt;
height: 12px;
}
</style>

</head>

<body>

<?php
require "menu.php";
?>

<div style='float:left;font-size:9pt;width:18%;color:blue;font-family:Verdana, Arial, Helvetica, sans-serif;'>
Double-click on a row to see all orders for that customer.
<p>Drag the edge of a column heading to resize a column.
<p>To filter: right-click (ctrl-click in Opera or Safari) on the value that you would like to use as the basis for filtering, then select the desired filtering method from the pop-up menu.
<p>Right-click anywhere in a column to see sort, hide, and show options.
</div>

<div class="container">
<p class="ricoBookmark"><strong>Customers</strong>
<span id="customergrid_bookmark" style="font-size:10pt;padding-left:4em;"></span>
</p>
<table id="customergrid" class="ricoLiveGrid" cellspacing="0" cellpadding="0">
<colgroup>
<col style='width:60px;' >
<col style='width:150px;' >
<col style='width:115px;'>
<col style='width:130px;' >
<col style='width:90px;' >
<col style='width:60px;' >
<col style='width:90px;' >
<col style='width:100px;'>
<col style='width:115px;'>
<col style='width:115px;'>
</colgroup>
  <tr>
	  <th>Customer#</th>
	  <th>Company</th>
	  <th>Contact</th>
	  <th>Address</th>
	  <th>City</th>
	  <th>Region</th>
	  <th>Postal Code</th>
	  <th>Country</th>
	  <th>Phone</th>
	  <th>Fax</th>
  </tr>
</table>

<p class="ricoBookmark"><strong>Orders for <span id="custid"></span></strong>
<span id="ordergrid_bookmark" style="font-size:10pt;padding-left:4em;">&nbsp;</span>
</p>
<table id="ordergrid" class="ricoLiveGrid" cellspacing="0" cellpadding="0">
<colgroup>
<col style='width:5px;'  >
<col style='width:60px;' >
<col style='width:150px;'>
<col style='width:80px;' >
<col style='width:90px;' >
<col style='width:100px;'>
<col style='width:100px;'>
</colgroup>
  <tr>
	  <th>Customer#</th>
	  <th>Order#</th>
	  <th>Ship Name</th>
	  <th>Ship City</th>
	  <th>Ship Country</th>
	  <th>Order Date</th>
	  <th>Ship Date</th>
  </tr>
</table>

<p class="ricoBookmark"><strong>Order #<span id="orderid"></span></strong>
<span id="detailgrid_bookmark" style="font-size:10pt;padding-left:4em;">&nbsp;</span>
</p>
<table id="detailgrid" class="ricoLiveGrid" cellspacing="0" cellpadding="0">
<colgroup>
<col style='width:5px;'  >
<col style='width:150px;'>
<col style='width:125px;'>
<col style='width:80px;' >
<col style='width:50px;' >
<col style='width:80px;' >
<col style='width:80px;' >
<col style='width:90px;' >
</colgroup>
  <tr>
	  <th>Order #</th>
	  <th>Description</th>
	  <th>Unit Quantity</th>
	  <th>Unit Price</th>
	  <th>Qty</th>
	  <th>Total</th>
	  <th>Discount</th>
	  <th>Net Price</th>
  </tr>
</table>

</div>

<!--
<table border="0" style="clear:both;"><tr>
<td><textarea id='customergrid_debugmsgs' rows='5' cols='30'></textarea>
<td><textarea id='ordergrid_debugmsgs' rows='5' cols='30'></textarea>
<td><textarea id='detailgrid_debugmsgs' rows='5' cols='30'></textarea>
</tr></table>
-->

</body>
</html>

