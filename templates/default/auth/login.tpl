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
<p align="center"><strong><font color="#990000">{$errorMessage|outhtml}</font></strong><br /><br /></p>
{/if}
	<div id="Dialog">
		<center>
            <h1>{$LANG.simple_invoices}</h1>
		<form action="" method="post" id="frmLogin" name="frmLogin">
	        <input type="hidden" name="action" value="login" />
		<dl>
        <table>
  		<tr>
            <td>
                {$LANG.email}:
            </td>
            <td>
  		        <input name="user" size="25" type="text" title="user" value="" />
            </td>
        </tr>       
  		<tr>
            <td>
                {$LANG.password}:
            </td>
            <td>
  		        <input name="pass" size="25" type="password" title="password" value="" />
            </td>
        </tr>       
  		<tr>
            <td>
            </td>
            <td>
                <input type="submit" value="login" />
            </td>
        </tr>       
        </table>
		</form>
        </center>
        <br/>
	</div>
    <br/>
        <a href="http://www.simpleinvoices.org">{$LANG.simple_invoices_powered_by}</a>
</div>

<script language="JavaScript">
	<!--
	document.frmLogin.user.focus();
	//-->
</script>

</body>
</html>
