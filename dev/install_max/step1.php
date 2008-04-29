<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
<title>Simple Invoices Setup. Step 1</title>
<meta http-equiv="content-type" content="text/html; charset=UTF-8">
<meta http-equiv="content-type" content="application/xhtml+xml; charset=UTF-8">
<meta http-equiv="content-style-type" content="text/css">
<meta http-equiv="expires" content="0">
<link rel="stylesheet" href="install.css" type="text/css" />
</head>
<body>
<div id="wrapper">
<img src="./images/Simple_Invoices_Logo.jpg" border="0" alt="">
<div id="header"><div align="left"><b>2 Step</b></div></div>
<div id="leftcolumn">
<div id="leftnav">
<?php
include ("left.php");
?>
</div>
<div id="leftnavbottom"></div></div>
<form action="step2.php" method="post" enctype="multipart/form-data">
<?php echo $LANG['Msg1']; ?>
<table>
<tr>
<td><?php echo $LANG['DBName']; ?></td>
<td><input type="text" size="50" name="DBName" value="<?php echo $LANG['defdbname']; ?>"></td>
</tr>
<tr>
<td><?php echo $LANG['DBUsername']; ?></td>
<td><input type="text" size="50" name="DBUsername" value="<?php echo $LANG['defdbuser']; ?>"></td>
</tr>
<tr>
<td><?php echo $LANG['DBPassword']; ?></td>
<td><input type="password" size="50" name="DBPassword" value="<?php echo $LANG['defdbpass']; ?>"></td>
</tr>
</table>
<?php echo $LANG['Msg2']; ?>
<table>
<tr>
<td><?php echo $LANG['DBHost']; ?></td>
<td><input type="text" size="50" name="DBHost" value="<?php echo $LANG['defdbhost']; ?>"></td>
</tr>
<tr>
<td><?php echo $LANG['DBPort']; ?></td>
<td><input type="text" size="50" name="DBPort" value="<?php echo $LANG['defdbport']; ?>"></td>
</tr>
<tr>
<td><?php echo $LANG['prefix']; ?></td>
<td><input type="text" size="50" name="prefix" value="<?php echo $LANG['defdbprefix']; ?>"></td>
</tr>
</table>
<p>Next step will try to connect to MySQL server,after to a database, create it if it doesnt exist and finally create tables</p>
<table>
<tr>
<td><a href="index.php"><img src="./images/go-previous.png" border="0" alt=""></a></td>
<td><input type="image" name="ok" src="./images/go-next.png" ></td>
</tr>
</table>
</form>
</div>
</body>
</html>