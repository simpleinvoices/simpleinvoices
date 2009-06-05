<?php
/*
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
*/


class import {

	public $file;
	
	public function getFile()
	{
		$json = file_get_contents($this->file, true);
		return $json;
	}
	
	public function decode($json)
	{
		$a = json_decode($json,true);	
		return $a;
	}

	public function process($a)
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
	
	
	public function import()
	{
		$json = $this->getFile();
		$decode = $this->decode( $this->getFile() );
		$this->process($decode);
	}
	
	
}




$import = new import();
$import->file = "EssentialData.json";
$import->import();


?>
