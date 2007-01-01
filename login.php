<?php
// we must never forget to start the session
/*
CREATE TABLE si_auth_user (
user_id VARCHAR(10) NOT NULL,
user_password CHAR(32) NOT NULL,

PRIMARY KEY (user_id)
);

INSERT INTO si_auth_user (user_id, user_password) VALUES ('guest', md5('guest'));
INSERT INTO si_auth_user (user_id, user_password) VALUES ('demo', md5('demo'));
INSERT INTO si_auth_user (user_id, user_password) VALUES ('admin', md5('admin'));
*/

include 'config/config.php';
include "lang/$language.inc.php";

session_start();

$errorMessage = '';
if (isset($_POST['user']) && isset($_POST['pass'])) {

    $conn = mysql_connect( $db_host, $db_user, $db_password);
     mysql_select_db( $db_name, $conn);

    $userId   = $_POST['user'];
    $password = $_POST['pass'];
    
    // check if the user id and password combination exist in database
    $sql = "SELECT user_id 
            FROM si_auth_user
            WHERE user_id = '$userId' AND user_password = md5('$password')";
    
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
		<img src="./logo/simple_invoices_logo.png" alt="KnowledgeTree DMS" class="logoimage"<br />
            
	        		    <p class="descriptiveText">Please enter your details below to login.</p>

		            
	        <label for="username">Username</label>
		<input  name="user" type="text" id="user"/>
		
		<label for="password">Password</label>
		<input name="pass" type="password" id="pass"/>
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
	    <h2>Login Credentials</h2> <br /><strong>Annoucement area<br /></strong><br>This is an important annoucement
	</div>
	
	<p class="descriptiveText version">
	<?php echo $title; echo " "; echo $version; ?><br/>
	copyleft; 2006 <a href="http://www.simpleinvoices.org/">Simple Invoices</a>. Licensed under the <a href="http://www.gnu.org/licenses/gpl.html">GPL</a> 
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
