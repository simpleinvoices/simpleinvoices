<html>
	<body>
		<p style='width:400px;height:200px;background-color:#F5F5F5;border-style:solid;border-width:2;border-color:#CCCCCC;padding:10px 10px 10px 10px;font-family:verdana,arial,helvetica,sans-serif;color:#505050;font-size:12px;'>";
		<span style='font-size:18px;color:#FF0000;font-weight:bold;'>Missing field</span><br/><br/>
		Seems that you're trying to retrieve the value of (or a function based on) a field called <b><?php print $_GET["field"]; ?></b>, but it is not
		on the fields your query returned to me.<br/>
		<br/>
		Note that when the query returns <i>(programmer stuff here now)</i>, it returns is an associative array, a array which the
		elements can be reached using their <b>names</b> not their <b>numbers</b> (yes, you can do that, but calling 0 and not MYFIELD 
		will be confuse).<br/>
		<br/>
		Check your SQL query fields and the fields you put on your XML layout file, on the <b>FIELDS</b> element (inside the <b>ROWs</b>
		there, on <b>COLs</b> of <b>TYPE="FIELD"</b>). Check if they are equal on your query and on your XML file.<br/>
		<br/>
		One thing that can make this happens when the fields are the same on the SQL query and the XML file is the <b>case sensitive</b>
		thing.<br/>
		Note that <b>myfield</b> is different from <b>MYFIELD</b> on some databases, and it's a good idea you put the fields on
		your query and XML file the way your database works, and with same case on both places.
		<br clear='all'/>
		</p>
	</body>
</html>	
