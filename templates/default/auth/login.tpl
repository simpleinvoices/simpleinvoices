<html>
<head>
<link rel="stylesheet" type="text/css" href="./templates/default/css/login.css" />
</head>
<body class="login" >
<div class="Container">

{if $errorMessage != ''}
<p align="center"><strong><font color="#990000">{$errorMessage}</font></strong></p>
{/if}
	<div id="Dialog">

	<h1>Simple Invoices</h1>
		<form action="" method="post" name="frmLogin" id="frmLogin">
	        <input type="hidden" name="action" value="login" />
		<dl>
  		<dt>{$LANG.email}:</dt>
  		<dd><input name="user" type="text" id="user" value="" /></dd>

  		<dt>{$LANG.password}:</dt>
  		<dd>
  		  <input name="pass" type="password" id="pass" value="" />
			<!--
  		  <span>(<a href="login.php">I forgot my password/username</a>)</span>
			-->
 		</dd>
<!--TODO add language select drop down here -->
<!--
      		<dd><input type="checkbox" name="remember_me" /> Remember me on this computer</dd>
-->
		<dd> <input type="submit" value="login" /></dd>


<!--  		<dd><input type="submit" value="Sign in" /></dd> -->
<!--
	        <label for="username">Username</label>
		<input  name="user" type="text" id="user" />
		<br />
		<label for="password">Password</label>
		<input name="pass" type="password" id="pass"/>
-->
		<!--
		<label for="language">Language</label>
		<select id="language" name="language">
			<option value="en" selected="yes">English (United States)</option>
		</select>
               	-->
<!--
	        <div class="form_actions">
                   <dd> <input type="submit" value="login" /></dd>
		</div>
-->
	  	</dl>
		</form>

	</div>
        <dd>Powered by <a href="http://www.simpleinvoices.org">Simple Invoices</a></dd>
</div>
</body>
</html>
