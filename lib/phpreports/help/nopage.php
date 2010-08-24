<html>
	<body>
		<p style='width:400px;height:200px;background-color:#F5F5F5;border-style:solid;border-width:2;border-color:#CCCCCC;padding:10px 10px 10px 10px;font-family:verdana,arial,helvetica,sans-serif;color:#505050;font-size:12px;'>";
		<span style='font-size:18px;color:#FF0000;font-weight:bold;'>NO PAGE ELEMENT DEFINED</span><br/><br/>
		This error happens because you didn't insert a PAGE element in your report layout file.<br/>
		<br/>
		The PAGE element controls how your report count lines and break pages. You need to put it
		<b>before</b> your GROUPS element.<br/>
		<br/>
		If not specified the PAGE size, the default will be 50 rows.<br/>
		So, please, open your XML layout file and insert it to fix this error. ;-)
		<br clear='all'/>
		</p>
	</body>
</html>	
