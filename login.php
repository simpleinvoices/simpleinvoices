<?php
ob_start();

echo "test:";
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
/*
JK: 
- problem: with current auth it checks if a session variable it set to x
-- the problem with this is if you have multiple installs on Simple Invoices on 1 server, by logging into 1 install of Simple Invoices on that server you will then be able to login to all the other ones without haveing to enter a password - which is bad
- by adding a SessionIdentifier thing - attempt to fix the above problem bu having each install have its own identifyer (default is based on the url to try and make it auto unique)

- if anyone knows a better way to fix this issues please do so

*/
$_SESSION[md5($authSessionIdentifier)] = "true";


//redirecto to index page if login is ok
if ( ($a->getAuth()) && (isset($_SESSION[md5($authSessionIdentifier)])) ) {
    $_SESSION[md5($authSessionIdentifier)] = md5($authSessionIdentifier);
    header('Location: .');
    ob_end_flush();
}

if ($_GET['action'] == "logout" && $a->checkAuth()) {
    $a->logout();
    unset($_SESSION[md5($authSessionIdentifier)]);
    $errorMessage = "You have just logged out. <br><br>Thank you for using Simple Invoices";
    $a->start();
}
//login faield mesage
if ( (isset($_POST['login']) ) AND ($a->getAuth() == FALSE) ) 
{
	$errorMessage = "Sorry wrong username / password";
}


print_r($_SESSION);
?>
	</div>
		<dd>Powered by <a href="http://www.simpleinvoices.org">Simple Invoices</a></dd>
    </div>
</body>
</html> 
