<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<title>Simple Invoices - Login</title>
<link rel="stylesheet" type="text/css" href="./templates/default/css/login.css" />
</head>
<body class="login" >
<div class="Container">
{if $errorMessage }
<p align="center"><strong><font color="#990000">{$errorMessage}</font></strong></p>
{/if}
	<div id="Dialog">
		<h1>Simple Invoices</h1>
		<form action="" method="post" id="frmLogin">
	 	<fieldset>
	        <input type="hidden" name="action" value="login" />
		<dl>
  		<dt>{$LANG.email}:</dt>
  		<dd><input name="user" type="text" title="user" value="" /></dd>
  		<dt>{$LANG.password}:</dt>
  		<dd><input name="pass" type="password" title="password" value="" /></dd>
		<dd><input type="submit" value="login" /></dd>
	  	</dl>
		</fieldset>
		</form>
	</div>
        Powered by <a href="http://www.simpleinvoices.org">Simple Invoices</a>
</div>
</body>
</html>
