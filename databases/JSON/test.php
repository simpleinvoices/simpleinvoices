<?php

$myFile = "EssentialData.json";
$json = file_get_contents($myFile, true);

$a = json_decode($json,true);

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
				
					//TODO: IF NULL don't ''
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

$import = new import();
$import->file = "./database/JSON/EssentialData.json";
$import->import();

class import {

	pubic function getFile()
	{
		$json = file_get_contents($this->file, true);	
	}
	
	function decode()
	{
		$a = json_decode($json,true);	
	}

	function process()
	{
		
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
						
							//TODO: IF NULL don't ''
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
	}
	
	
	function import()
	{
		getFile();
		decode();
		process();
	}
	
	
}


?>
