<?php

$myFile = "EssentialData.json";
$json = file_get_contents($myFile, true);

$a = json_decode($json,true);
/*
foreach ($a as $k => $v) 
{
	echo "<b>".$k. "</b>";
	echo "<br>";
	    foreach ($a[$k] as $v2) 
		{
			echo "<br>";
				foreach ($v2 as $k3 => $v3) 
				{
					echo $k3 ." => ".$v3;
					echo "<br>";
				}
		}
	echo "<br>";    
	
}
*/
foreach ($a as $k => $v) 
{
	$table = $k;
	echo "Table: ".$table;
	
	echo "<br>";
	$columns ="";
	    foreach ($a[$k] as $v2) 
		{
			echo "<br>";
				
				foreach ($v2 as $k3 => $v3) 
				{
					$columns .= ", ".$k3;	
					echo $k3 ." => ".$v3;
					echo "<br>";
				}
				
			echo "Columns: ".$columms;
			$sql = "INSERT into ".$table." (".$columns.") VALUES (".$values.");";
		}
	echo "<br>";    
	
}



?>
