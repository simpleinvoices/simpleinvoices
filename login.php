<?php
// we must never forget to start the session
/*
CREATE TABLE si_auth_user (
user_id VARCHAR(10) NOT NULL,
user_password CHAR(32) NOT NULL,

PRIMARY KEY (user_id)
);

INSERT INTO tbl_auth_user (user_id, user_password) VALUES ('theadmin', PASSWORD('chumbawamba'));
INSERT INTO tbl_auth_user (user_id, user_password) VALUES ('webmaster', PASSWORD('webmistress'));
INSERT INTO tbl_auth_user (user_id, user_password) VALUES ('admin', PASSWORD('admin'));
*/
session_start();

$errorMessage = '';
if (isset($_POST['txtUserId']) && isset($_POST['txtPassword'])) {
    include 'config/config.php';
	include("./lang/$language.inc.php");

    $conn = mysql_connect( $db_host, $db_user, $db_password);
     mysql_select_db( $db_name, $conn);

    $userId   = $_POST['txtUserId'];
    $password = $_POST['txtPassword'];
    
    // check if the user id and password combination exist in database
    $sql = "SELECT user_id 
            FROM si_auth_user
            WHERE user_id = '$userId' AND user_password = PASSWORD('$password')";
    
    $result = mysql_query($sql, $conn) or die('Query failed. ' . mysql_error()); 
    
    if (mysql_num_rows($result) == 1) {
        // the user id and password match, 
        // set the session
        $_SESSION['db_is_logged_in'] = true;
        
        // after login we move to the main page
	header('Location: .');
        exit;
    } else {
        $errorMessage = 'Sorry, wrong user id / password';
    }
    
} 

?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Simple Invoices Login</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
   <link rel="stylesheet" href="./include/auth/kt-login.css" type="text/css" />

</head>

<body>
<?php
if ($errorMessage != '') {
?>
<p align="center"><strong><font color="#990000"><?php echo $errorMessage; ?></font></strong></p>
<?php
}
?>
  <div id="loginbox"  class="hasDisclaimer" >

        <div id="formbox">

	    <form action="" method="post" name="frmLogin" id="frmLogin">
	        <input type="hidden" name="action" value="login" />
		<input type="hidden" name="cookieverify" value="" />
		<input type="hidden" name="redirect" value="" />
		<img src="./include/auth/ktlogo-topbar-right.png" alt="KnowledgeTree DMS" class="logoimage" height="50" width="252"/><br />
            
	        		    <p class="descriptiveText">Please enter your details below to login.</p>

		            
	        <label for="username">Username</label>
		<input  name="txtUserId" type="text" id="txtUserId"/>
		
		<label for="password">Password</label>
		<input name="txtPassword" type="password" id="txtPassword"/>
		<!--
		<label for="language">Language</label>
		<select id="language" name="language">
				    <option value="en" SELECTED="yes">English (United States)</option>

				</select>
               	-->
	        <div class="form_actions">
                    <input type="submit" value="login" />
		</div>
            </form>

	</div>
                <div id="disclaimerbox">
	    <h2>Login Credentials</h2> <br /><strong>Totally ripped from KnowledgeTree<br />thanks to stb3 for the code 
	</div>
	
	<p class="descriptiveText version">
	Simple Invoices 20060920<br/>
	copyleft; 2006 <a href="http://www.simpleinvoices.org/">Simple Invoices</a>. Licensed under the <a href="http://www.fsf.org/gpl.html">GPL</a> 
	</p>
        

    </div>





<!--
<form action="" method="post" name="frmLogin" id="frmLogin">
 <table width="400" border="1" align="center" cellpadding="2" cellspacing="2">
  <tr>
   <td width="150">User Id</td>
   <td><input name="txtUserId" type="text" id="txtUserId"></td>
  </tr>
  <tr>
   <td width="150">Password</td>
   <td><input name="txtPassword" type="password" id="txtPassword"></td>
  </tr>
  <tr>
   <td width="150">&nbsp;</td>
   <td><input name="btnLogin" type="submit" id="btnLogin" value="Login"></td>
  </tr>
 </table>
</form>
-->

</body>
</html> 
