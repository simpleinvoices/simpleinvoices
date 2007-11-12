<?php

/*
* Script: login.php
* 	Login page
*
* Authors:
*	 Justin Kelly, Nicolas Ruflin
*
* Last edited:
* 	 2007-07-18
*
* License:
*	 GPL v2 or above
*/

// we must never forget to start the session
//so config.php works ok without using index.php define browse
define("BROWSE","browse");
include 'config/config.php';
include 'include/sql_queries.php';

session_start();

$errorMessage = '';
if (isset($_POST['user']) && isset($_POST['pass'])) {

    $conn = mysql_connect( $db_host, $db_user, $db_password);
     mysql_select_db( $db_name, $conn);

    $userEmail   = $_POST['user'];
    $password = $_POST['pass'];
    
    // check if the user id and password combination exist in database
    $sql = "SELECT user_id 
            FROM ".TB_PREFIX."users
            WHERE user_email = '$userEmail' AND user_password = md5('$password')";
    
    $result = mysql_query($sql, $conn) or die('Query failed. ' . mysql_error()); 
    
    if (mysql_num_rows($result) == 1) {
        // the user id and password match, 
        // set the session
        $_SESSION['db_is_logged_in'] = true;
        
        // after login we move to the main page
	header('Location: .');
        exit;
    } else {
        $errorMessage = 'Sorry, wrong user / password';
    }
    
} 


/*

if (isset($_POST['user']) && isset($_POST['pass'])) {

    $conn = mysql_connect( $db_host, $db_user, $db_password);
     mysql_select_db( $db_name, $conn);

    $userEmail   = $_POST['user'];
    if ($_POST['pass'] == $_POST['md5'] ){
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
    $storedPassword=$credentials['user_password'];



    if ($ChallengeLife>0) {
        $DeleteOldChallenges = 'DELETE FROM `si_auth_challenges` WHERE `challenges_timestamp` < DATE_SUB(now(),INTERVAL '.$ChallengeLife.' Minute)';
        mysqlQuery($DeleteOldChallenges, $conn);
        $sql = "SELECT *
            FROM si_auth_challenges
            WHERE challenges_key = '$ChallengeKeySubmitted' ";
        $result = mysqlQuery($sql, $conn) or die('Query failed. ' . mysql_error()); 
#        echo "Found or not the key in DB";
        if (mysql_num_rows($result) >= 1) {
            //Challenge was valid
#            echo $ChallengeKeySubmitted;
            $DeleteUSEDChallenge = 'DELETE FROM `si_auth_challenges` WHERE `challenges_key` = '.$ChallengeKeySubmitted.' limit 1';
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
*/

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

if ($errorMessage != '') {
?>
<p align="center"><strong><font color="#990000"><?php echo $errorMessage; ?></font></strong></p>
<?php
}
?>
<div id="Dialog">

<h1>Simple Invoices</h1>


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
  		<dd><input name="user" type="text" id="user" value="" /></dd>

  		<dt>Password:</dt>
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
        
		<dd>Powered by <a href="http://www.simpleinvoices.org">Simple Invoices</a></dd>

    </div>

</body>
</html> 
