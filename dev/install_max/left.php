<?php
//need to add session verification
session_start();

/*Checks web browser default lang, i think can be hiden, ive tried "if(!empty($langNav))" verification but it doest work well,
need to be done*/

$langNav = substr(getenv("HTTP_ACCEPT_LANGUAGE"),0,2);
$filename = 'lang/lang_'.$langNav.'.php';
if (file_exists($filename)) {
	include('lang/lang_'.$langNav.'.php');
	$_SESSION['language'] = $langNav;
	}else{
   $langNav = "en";
   include('lang/lang_'.$langNav.'.php');
	$_SESSION['language']= $langNav;
	}


//include functions, messy file :)
include("functions.php");

//here goes left container code
echo "<table><tr><td>";
echo "<img src=\"../images/common/help.png\" align=\"right\" border=\"0\"></td>";
echo "<td><h1>";
echo $LANG['info'];
echo "</h1></td><td></td></tr>";
echo "<tr><td>";
echo $LANG['softver'];
echo "</td><td>";
echo cur();
echo "</td></tr><tr><td>";
echo $LANG['phpver'];
echo "</td><td>";
$res=phpvers();
if($res[0]==1){
		echo "<font id=\"green\">";
		echo $LANG['php5_true1'];
		echo $res[1];
		echo $LANG['php5_true2'];
		echo "</font>";
}else {
		echo "<font id=\"red\">";
		echo $LANG['php5_false'];
		echo $res[1];
		echo "</font>";
		}
echo "</td></tr><tr><td>";
echo $LANG['mysqlmod'];
echo "</td><td>";
$res=mysql_is_available();
if($res==1){
	echo "<font id=\"green\">";
	echo $LANG['mysql_true'];
	echo "</font>";
	}else{
		echo "<font id=\"red\">";
		echo 	$LANG['mysql_false'];	
		echo "</font>";
		}
echo "</td></tr><tr><td>";
echo $LANG['memorylim'];
echo "</td><td>";
$res=memorysheck();
if($res[0]==1){
	echo "<font id=\"green\">";
	echo $LANG['memory_valid_1'];
	echo $res[1];
	echo $LANG['memory_valid_2'];	
	echo "</font>";
}else {
		echo "<font id=\"red\">";
		echo $LANG['memory_caution_1'];		
		echo $res[1];
		echo $LANG['memory_caution_2'];		
		echo "</font>";
		}

echo "</td></tr><tr><td>";
echo $LANG['gdsup'];
echo "</td><td>";
if ($gdv = gdVersion()) {
     if ($gdv >=2) {
         echo "<font id=\"green\">";
         echo $LANG["GD_true"];
         echo "</font>";
     } else {
         echo "<font id=\"red\">";
         echo $LANG['GD_2false'];
         echo "</font>";
     } 
}
echo "</td>";
echo "</tr><tr><td>";
echo $LANG['xslsup'];
echo "</td><td>";
$res=xsl();
if($res==1){
	echo "<font id=\"green\">";
	echo $LANG['xslt_true'];
	echo "</font>";
	}else {
		echo "<font id=\"red\">";
		echo 	$LANG['xslt_false'];
		echo "</font>";
		}
echo "</td></tr><tr><td>";
echo $LANG['cachedir'];
echo "</td><td>";
$res=testdir("../cache/");
if($res==1){
	echo "<font id=\"green\">";
	echo $LANG['cachedir_true'];
	echo "</font>";
	}else {
		echo "<font id=\"red\">";
		echo 	$LANG['cachedir_false'];
		echo "</font>";
		}
echo "</td></tr><tr><td>";
echo $LANG['config'];
echo "</td><td>";
$res=testdir("../config/config.php");
if($res==1){
	echo "<font id=\"green\">";
	echo $LANG['config_true'];
	echo "</font>";
	}else {
		echo "<font id=\"red\">";
		echo 	$LANG['config_false'];
		echo "</font>";
		}
echo "</td></tr><tr><td>";
echo $LANG['backup'];
echo "</td><td>";
$res=testdir("../database_backups/");
if($res==1){
	echo "<font id=\"green\">";
	echo $LANG['ok_backup'];
	echo "</font>";
	}else {
		echo "<font id=\"red\">";
		echo 	$LANG['no_backup'];
		echo "</font>";
		}
echo "</table>";
?>