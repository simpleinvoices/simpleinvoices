<?php
// we must never forget to start the session
/*
CREATE TABLE si_users (
user_id int(11) NOT NULL auto_increment,
user_email VARCHAR(100) NOT NULL,
user_name VARCHAR(100) NOT NULL,
user_group VARCHAR(10) NOT NULL,
user_domain VARCHAR(10) NOT NULL,
user_password CHAR(32) NOT NULL,

PRIMARY KEY (user_id)
);

INSERT INTO si_users (user_id, user_email, user_name, user_group, user_domain, user_password) VALUES ('','guest@simpleinvoices.org','guest','1','1', md5('guest'));
INSERT INTO si_users (user_id, user_email, user_name, user_group, user_domain, user_password) VALUES ('','demo@simpleinvoices.org','demo','1','1', md5('demo'));
INSERT INTO si_users (user_id, user_email, user_name, user_group, user_domain, user_password) VALUES ('','admin@simpleinvoices.org','admin','1','1', md5('admin'));

CREATE TABLE `si_auth_challenges` (
`challenges_key` INT( 11 ) NOT NULL ,
`challenges_timestamp` TIMESTAMP ON UPDATE CURRENT_TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
);

*/
//so config.php works ok without using index.php define browse
define("BROWSE","browse");
include 'config/config.php';
include 'include/sql_queries.php';
include "lang/$language.inc.php";
include "include/md5/hmac_md5.php";
session_start();

$errorMessage = '';
if (isset($_POST['user']) && isset($_POST['pass'])) {

    $conn = mysql_connect( $db_host, $db_user, $db_password);
     mysql_select_db( $db_name, $conn);

    $userEmail   = $_POST['user'];
    if ($_POST['pass'] == $_POST['md5']){
        $password = $_POST['pass'];
    } 
    else {
        if($MD5Auth==True){
            $NoJSonClient=True;
        }        
        $password = md5($_POST['pass']);
    }
    if ($ChallengeLife>0){
    $ChallengeKeySubmitted = $_POST['ChallengeKey'];
    }

    // Grab Password from database
    $sql = "SELECT *
            FROM si_users
            WHERE user_email = '$userEmail' ";
    
    $result = mysqlQuery($sql, $conn) or die('Query failed. ' . mysql_error()); 
    $credentials = mysql_fetch_array($result);
    $storedPassword=$credentials[user_password];



    if ($ChallengeLife>0) {
        $DeleteOldChallenges = 'DELETE FROM `si_auth_challenges` WHERE `challenges_timestamp` < DATE_SUB(now(),INTERVAL $ChallengeLife Minute)';
        mysqlQuery($DeleteOldChallenges, $conn);
        $sql = "SELECT *
            FROM si_auth_challenges
            WHERE challenges_key = '$ChallengeKeySubmitted' ";
        $result = mysqlQuery($sql, $conn) or die('Query failed. ' . mysql_error()); 
#        echo "Found or not the key in DB";
        if (mysql_num_rows($result) >= 1) {
            //Challenge was valid
#            echo $ChallengeKeySubmitted;
            $DeleteUSEDChallenge = 'DELETE FROM `si_auth_challenges` WHERE `challenges_key` = `$ChallengeKeySubmitted`limit 1';
            mysqlQuery($DeleteUSEDChallenge, $conn);
#            echo "Deleted Used Key $ChallengeKeySubmitted";
            if($password==hmac_md5($ChallengeKeySubmitted, "$storedPassword")){ 
                $_SESSION['db_is_logged_in'] = true;
                // after login we move to the main page
	        header('Location: .');
                exit;
            } else {
#                echo $ChallengeKeySubmitted;
                $DB=hmac_md5($ChallengeKeySubmitted, "$storedPassword");
                $errorMessage = "Sorry, wrong user / password";
            }
        } else {
            $errorMessage = 'Sorry, the login timed out.  Please try again';
        }
    }elseif ($password==$storedPassword){
            $_SESSION['db_is_logged_in'] = true;
            // after login we move to the main page
            header('Location: .');
            exit;
    }else {
    $errorMessage = 'Sorry, wrong user / password';
    }
} 

if($ChallengeLife>0) {
    $Challenge_Key=Rand(0,99999999999);
#    $Challenge_Key=1;
    $conn = mysql_connect( $db_host, $db_user, $db_password);
     mysql_select_db( $db_name, $conn);
     mysqlQuery("INSERT INTO si_auth_challenges (challenges_key) VALUES ($Challenge_Key)",$conn);
}

?>
<html>
<head>

<?php if($MD5Auth==True){?>

    <script src="./include/md5/md5-2.2alpha.js"></script> -->
    <script language="JavaScript"><!--

    function login(f) {

    <?php if ($ChallengeLife>0){?>
       f['md5'].value = hex_md5(f['pass'].value);
       f['pass'].value = hex_hmac_md5(f['ChallengeKey'].value, f['md5'].value);
       f['md5'].value = hex_hmac_md5(f['ChallengeKey'].value, f['md5'].value);
    <?php } else {?>   
       f['md5'].value = hex_md5(f['pass'].value);
       f['pass'].value = hex_md5(f['pass'].value);
    <?php }?>

       return true;

}

//--></script>

<?php }?>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Simple Invoices Login</title>
<link rel="stylesheet" type="text/css" href="./templates/default/css/login.css">

</head>

<body class="login" >
	<div class="Container">
<?php if($MD5Auth==True){?>
        <noscript>
        <p align="center"><strong><font color="#990000">JavaScript must be enabled for MD5 login.</font></strong></p>
        </noscript>

<?php
}
if ($errorMessage != '') {
?>
<p align="center"><strong><font color="#990000"><?php echo $errorMessage; ?></font></strong></p>
<?php
}
?>
<div id="Dialog">
<!--
        <div align=center id="disclaimerbox">
	    <img src="./images/common/important.png"></img>
	    <strong>Annoucement<br /></strong><br>This is an important annoucement
		<hr></hr>
	</div>
-->
<h1>Simple Invoices</h1>
<!--
  <div id="loginbox"  class="hasDisclaimer" >

        <div id="formbox">
-->

	    <form action="" method="post" name="frmLogin" id="frmLogin" <?php if($MD5Auth==True){?>onSubmit="return login(this);"<?php }?>>
	        <input type="hidden" name="action" value="login" />
		<input type="hidden" name="cookieverify" value="" />
		<input type="hidden" name="redirect" value="" />

                <?php if($MD5Auth==True){?>
                <input type="hidden" name="md5" value="">
                <?php }?>		            
                <?php if($ChallengeLife>0){?>
                <input type="hidden" name="ChallengeKey" value="<?php echo $Challenge_Key;?>">
                <?php }?>		            

  	<dl>
  		<dt>Email:</dt>
  		<dd><input name="user" type="text" id="user" /></dd>

  		<dt>Password:</dt>
  		<dd>
  		  <input name="pass" type="password" id="pass" />
			<!--
  		  <span>(<a href="login.php">I forgot my password/username</a>)</span>
			-->
  		</dd>

      		<dd><input type="checkbox" name="remember_me" /> Remember me on this computer</dd>
		<dd> <input type="submit" value="login" /></dd>
<!--
		<dd>Powered by Simple Invoices</dd>
-->
<!--  		<dd><input type="submit" value="Sign in" /></dd> -->
<!--
	        <label for="username">Username</label>
		<input  name="user" type="text" id="user"/>
			<BR>
		<label for="password">Password</label>
		<input name="pass" type="password" id="pass"/>
-->
		<!--
		<label for="language">Language</label>
		<select id="language" name="language">
				    <option value="en" SELECTED="yes">English (United States)</option>

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
