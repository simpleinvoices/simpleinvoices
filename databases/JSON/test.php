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

	echo "<br>";
	echo "<b>Table: ".$table."</b>";
	
	
	$columns ="";
	$values="";
	    foreach ($a[$k] as $v2) 
		{
			echo "<br>";
				$i = "1";
				foreach ($v2 as $k3 => $v3) 
				{
					$i == "1" ? $columns .= $k3 : $columns .= ", ".$k3;
					$i == "1" ? $values .= "'".$v3."'" : $values .= ", '".$v3."'";

					$i++;
				}
				
			
			$sql = "INSERT into ".$table." (".$columns.") VALUES (".$values.");";
			echo "SQL: ".$sql;
			$columns ="";
			$values ="";
		}
	echo "<br>";    
	
}



?>
