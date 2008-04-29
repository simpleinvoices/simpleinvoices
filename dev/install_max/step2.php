<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
<title>Simple Invoices Setup. Step 2</title>
<meta http-equiv="content-type" content="text/html; charset=UTF-8">
<meta http-equiv="content-type" content="application/xhtml+xml; charset=UTF-8">
<meta http-equiv="content-style-type" content="text/css">
<meta http-equiv="expires" content="0">
<link rel="stylesheet" href="install.css" type="text/css" />
</head>
<body>
<div id="wrapper">
<img src="./images/Simple_Invoices_Logo.jpg" border="0" alt="">
<div id="header"><div align="left"><b>3 Step</b></div></div>
		 <div id="leftcolumn">
			<div id="leftnav">
<?php
include("left.php");
?>
 

				</div>
					
                        <div id="leftnavbottom"></div></div>
<?php
$DBName=$_SESSION['DBName']=$_POST['DBName'];
$DBUsername=$_SESSION['DBUsername']=$_POST['DBUsername'];
$DBPassword=$_SESSION['DBPassword']=$_POST['DBPassword'];
$DBHost=$_SESSION['DBHost']=$_POST['DBHost'];
$DBPort=$_SESSION['DBPort']=$_POST['DBPort'];
$prefix=$_SESSION['prefix']=$_POST['prefix'];
$_SESSION['language'];


echo "<table><tr><td>";
echo $LANG['Connect'];
echo "</td><td>";
$res=testconn($DBHost, $DBUsername, $DBPassword);
if($res==1){
		echo "<font id=\"green\">";
		echo $LANG['ConnectDB_true'];
		echo "</font>";
}else {
		echo "<font id=\"red\">";
		echo $LANG['unableConnectDb'];
		echo "</font>";
		}

echo "</td></tr><tr><td>";
echo $LANG['DBexists'];
echo "</td><td>";
$res=dbexists($DBHost, $DBUsername, $DBPassword,$DBName);
if($res==1){
      echo "<font id=\"red\">";   	 	
		echo $LANG['ok_DBexists'];	
		echo "</font>";
		echo "</td></tr></table>";
		echo "<table><tr><td><a href='step1.php'><img src='./images/go-previous.png' border='0' alt=''></a></td></tr></table>";
		}else {
		echo $LANG['no_DBexists'];
		echo "</td></tr>";
		echo "<tr><td>";
		echo $LANG['DBcreate'];
		echo "</td><td>";
		$res=dbcreate($DBHost, $DBUsername, $DBPassword,$DBName);
		if($res==1){
      	echo "<font id=\"green\">";   	 	
			echo $LANG['ok_DBcreate'];	
			echo "</font>";
			echo "</td></tr>";
			echo "<tr><td>";
			echo $LANG['TABLES'];
			echo "</td><td>";
			$res=parse_mysql_dump($DBHost, $DBUsername, $DBPassword,$DBName, "../SimpleInvoices.sql");
			if($res==1){
      		echo "<font id=\"green\">";   	 	
				echo $LANG['ok_TABLES'];	
				echo "</font></td></tr></table>";
				echo "<p>";
				echo $LANG['Msg3'];
				echo "</p>";
				echo "<table><tr><td><a href='step1.php'><img src='./images/go-previous.png' border='0' alt=''></a></td>";
				echo "<td><input type='image' name='ok' src='./images/go-next.png' ></td></tr></table>";
				}else {
					echo "<font id=\"red\">";
					echo $LANG['no_TABLES'];
					echo "</font></td></tr></table>";
					echo "<a href='./trouble/trouble.php'>";
					echo </a>
		}
			}else {	
				echo "<font id=\"red\">";
				echo $LANG['no_DBcreate'];
				echo "</font>";
				echo "</td></tr></table>";
			}
		}
?>

</div>
</body>
</html>