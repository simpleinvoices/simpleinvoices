<html>
	<body>
		<p style='width:400px;height:200px;background-color:#F5F5F5;border-style:solid;border-width:2;border-color:#CCCCCC;padding:10px 10px 10px 10px;font-family:verdana,arial,helvetica,sans-serif;color:#505050;font-size:12px;'>";
		<span style='font-size:18px;color:#FF0000;font-weight:bold;'>No XSLT processor found</span><br/><br/>
		Oh boy, this is bad. Stop the press! You <b>REALLY</b> need a XSLT processor do make all this stuff here works.
		Let me explain why.<br/>
		<br/>
		<b>All</b> the idea of <b>PHPReports</b> is based on transform XML data on something else.<br/>
		<br/>
		On a first moment, it transforms your XML layout file (that one you put your FIELDS etc) in PHP code that uses the 
		PHP classes provided on the package to create another XML file with all your report data inside of it, and transform 
		it again using an <b>output plugin</b> to the format you choose.<br/>
		<br/>
		I can see that you're using PHP <?php $iVersion = intval(substr(phpversion(),0,1)); print $iVersion; ?>.<br/>
		With this version is recommended to use 
		<?php
			if($iVersion<5)
				print "the <a href='http://www.gingerall.com/charlie/ga/xml/p_sab.xml'>Sablotron</a> processor, you'll ".
						"need to download, install it and compile PHP again.<br/><br/>I must warn you that Sablotron have some problems ".
						"when running on Windows enviroments, so if you think in use <b>PHPReports</b> on this kind of environment ".
						"it's better migrate to PHP5 and use the <a href='http://www.php.net/manual/en/ref.xsl.php'>PHP XSL extension</a> there.";
			else
				print "the <a href='http://www.php.net/manual/en/ref.xsl.php'>PHP XSL extension</a>, and it's very easy to configure.";
			
			$sOS = strtoupper(trim(substr(php_uname(),0,strpos(php_uname()," "))));	
			if($sOS=="LINUX")
				print "<br/><br/>I can see that you're running GNU/Linux, so you'll have no problems with this. ;-)";
		?>
		<br clear='all'/>
		</p>
	</body>
</html>	
