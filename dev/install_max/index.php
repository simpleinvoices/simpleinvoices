<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
<title>Simple Invoices Setup.</title>
<meta http-equiv="content-type" content="text/html; charset=UTF-8">
<meta http-equiv="content-type" content="application/xhtml+xml; charset=UTF-8">
<meta http-equiv="content-style-type" content="text/css">
<meta http-equiv="expires" content="0">
<link rel="stylesheet" href="install.css" type="text/css" />
</head>
<body>
<div id="wrapper">
<img src="./images/Simple_Invoices_Logo.jpg" border="0" alt="">
<div id="header"><div align="left"><b>1 Step</b></div></div>
<div id="leftcolumn">
<div id="leftnav">
<?php
include ("left.php");
?>
</div>
<div id="leftnavbottom"></div></div>
<p><h2><?php echo $LANG['welcome'] ."<br />"; ?></h2></p>
<p><?php echo $LANG['intro']; ?></p>
<p><?php echo $LANG['download']; ?> <a href="http://simpleinvoices.sf.net" target="_blank"><img src="./images/html_go.png" border="0" alt=""> http://simpleinvoices.sf.net</a></p>
<p><?php echo $LANG['download2']; ?></p>
<p><?php echo $LANG['requirements']; ?></p>
<p><?php echo $LANG['next']; ?></p>
<table>
<tr>
<td></td>
<td><a href="step1.php"><img src="./images/go-next.png" border="0" alt=""></a></td>
</tr>
</table>
</div>
</body>
</html>