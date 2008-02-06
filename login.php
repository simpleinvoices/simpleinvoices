<?php
ob_start();
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Simple Invoices Login</title>
<link rel="stylesheet" type="text/css" href="./templates/default/css/login.css">
</head>
<body class="login" >
	<div class="Container">
<?php
if ($errorMessage != '') 
{
?>
	<p align="center"><strong><font color="#990000"><?php echo htmlspecialchars($errorMessage); ?></font></strong></p>
<?php
}
?>
<div id="Dialog">
<h1>Simple Invoices</h1>



<?php
// In this test, the file is named "test.php".

define("BROWSE","browse");
include 'config/config.php';
include 'include/sql_queries.php';

require_once "Auth.php";

function loginFunction()
{
     echo "
	    <form action=\"".$_SERVER['PHP_SELF']."\" method=\"post\" \">
  	<dl>
  		<dt>Email:</dt>
  		<dd><input name=\"username\" type=\"text\" id=\"user\"/></dd>
  		<dt>Password:</dt>
  		<dd>
  		  <input name=\"password\" type=\"password\" id=\"pass\" />
		<dd> <input type=\"submit\" name=\"login\" value=\"login\" /></dd>
  	</dl>
            </form>
	";
}

$options = array(
  'dsn' => $db_server."://".$db_user."@".$db_host."/".$db_name."",
  'table' => TB_PREFIX.'users',
  'usernamecol' => 'user_email',
  'passwordcol' => 'user_password',
  'cryptType' => 'md5',
  'regenerateSessionId' => true,
  'db_fields' => '*'
  );
$a = new Auth("MDB2", $options, "loginFunction");

$a->start();

$_SESSION['_authsession']['identifier'] = $authSessionIdentifier;


//redirecto to index page if login is ok
if ($a->getAuth()) {
    header('Location: .');
    ob_end_flush();
}

if ($_GET['action'] == "logout" && $a->checkAuth()) {
    $a->logout();
    $errorMessage = "You have just logged out. <br><br>Thank you for using Simple Invoices";
    $a->start();
}
//login faield mesage
if ( (isset($_POST['login']) ) AND ($a->getAuth() == FALSE) ) 
{
	$errorMessage = "Sorry wrong username / password";
}
?>
	</div>
		<dd>Powered by <a href="http://www.simpleinvoices.org">Simple Invoices</a></dd>
    </div>
</body>
</html> 
