<?php
//Developed by -==[Mihir Shah]==- during my Project work
//for the output
header("Content-type: text/xml");


$sql = "SELECT * FROM ".TB_PREFIX."invoices ORDER BY id desc";
$result = mysqlQuery($sql) or die(mysql_error());

echo sql2xml($result);

?> 
