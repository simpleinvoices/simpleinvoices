<?php
/*
* Script: login.php
* 	Login page
*
* License:
*	 GPL v3 or above
*/

$menu = false;
// we must never forget to start the session
//so config.php works ok without using index.php define browse
//define("BROWSE","browse");//already defined in /hermes/bosnaweb21a/b1834/ipg.yumatechnicalcom/simple/modules/auth/login.php on line 13

Zend_Session::start();
/*
echo  substr($_SERVER['SCRIPT_FILENAME'], -9, 5);
require_once 'include/init.php';
*/
// Create an in-memory SQLite database connection
////require_once 'Zend/Db/Adapter/Pdo/Mysql.php';
//$dbAdapter = new Zend_Db_Adapter_Pdo_Mysql(array('dbname' => ':memory:'));
/*
$dbAdapter = new Zend_Db_Adapter_Pdo_Mysql(array(
    'host'     => $config->database->params->host,
    'username' => $config->database->params->username,
    'password' => $config->database->params->password,
    'dbname'   => $config->database->params->dbname
));
*/
/*
$dbAdapter = Zend_Db::factory($config->database->adapter, array(
    'host'     => $config->database->params->host,
    'username' => $config->database->params->username,
    'password' => $config->database->params->password,
    'dbname'   => $config->database->params->dbname)
);
*/

$errorMessage = '';

if (!empty($_POST['user']) && !empty($_POST['pass'])) 
{

////	require_once 'Zend/Auth/Adapter/DbTable.php';

	// Configure the instance with constructor parameters...
	//$authAdapter = new Zend_Auth_Adapter_DbTable($dbAdapter, 'users', 'username', 'password');

	// ...or configure the instance with setter methods
	$authAdapter = new Zend_Auth_Adapter_DbTable($zendDb);

	$PatchesDone = getNumberOfDoneSQLPatches();

	//sql patch 161 changes user table name - need to accomodate
	$user_table = ($PatchesDone < "161") ? "users" : "user";
	$user_email = ($PatchesDone < "184") ? "user_email" : "email";
	$user_password = ($PatchesDone < "184") ? "user_password" : "password";

	$authAdapter->setTableName(TB_PREFIX.$user_table)
				->setIdentityColumn($user_email)
				->setCredentialColumn($user_password)
				->setCredentialTreatment('MD5(?)');

	$userEmail   = $_POST['user'];
	$password = $_POST['pass'];

	// Set the input credential values (e.g., from a login form)
	$authAdapter->setIdentity($userEmail)
	            ->setCredential($password);

	// Perform the authentication query, saving the result
	$result = $authAdapter->authenticate();

	if ($result->isValid()) {
		
		Zend_Session::start();

		/*
		* grab user data  from the database
		*/

		//patch 147 adds user_role table - need to accomodate pre and post patch 147
		if ( $PatchesDone < "147" ) {
			$result = $zendDb->fetchRow("
				SELECT 
					u.user_id AS id, u.user_email, u.user_name
				FROM 
					".TB_PREFIX."users u
				WHERE 
					user_email = ?", $userEmail
			);
			$result['role_name']="administrator";

		} elseif ( $PatchesDone < "184" ) {
			$result = $zendDb->fetchRow("
				SELECT 
					u.user_id AS id, u.user_email, u.user_name, r.name AS role_name, u.user_domain_id
				FROM 
					".TB_PREFIX."user u 
					LEFT JOIN ".TB_PREFIX."user_role r ON (u.user_role_id = r.id)
				WHERE 
					u.user_email = ?", $userEmail
			);

		} elseif ( $PatchesDone < "292" ) {
			$result = $zendDb->fetchRow("
				SELECT 
					u.id, u.email, r.name AS role_name, u.domain_id, 0 AS user_id
				FROM 
					".TB_PREFIX."user u 
					LEFT JOIN ".TB_PREFIX."user_role r ON (u.role_id = r.id)
				WHERE 
					u.email = ? AND u.enabled = '".ENABLED."'", $userEmail
			);

		// Customer / Biller User ID available on and after Patch 292
		} else {
			$result = $zendDb->fetchRow("
				SELECT 
					u.id, u.email, r.name AS role_name, u.domain_id, u.user_id
				FROM 
					".TB_PREFIX."user u 
					LEFT JOIN ".TB_PREFIX."user_role r ON (u.role_id = r.id)
				WHERE 
					u.email = ? AND u.enabled = '".ENABLED."'", $userEmail
			);
		}		
		/*
		* chuck the user details sans password into the Zend_auth session
		*/
		$authNamespace = new Zend_Session_Namespace('Zend_Auth');
		$authNamespace->setExpirationSeconds(60 * 60);
		foreach ($result as $key => $value)
		{
			$authNamespace->$key = $value;
		}

		if ($authNamespace->role_name == 'customer' && $authNamespace->user_id > 0) {
			header('Location: index.php?module=customers&view=details&action=view&id='.$authNamespace->user_id);
		} else {
			if (!isset ($_POST['action']) && !isset ($_POST['user']))
				header('Location: index.php?redir='.urlencode($_SERVER['HTTP_REFERER']));
			else
				header('Location: .');
		}
	} else {
		$errorMessage = 'Sorry, wrong user / password';
	}
}

if (isset ($_POST['action']) && $_POST['action'] == 'login' && (empty($_POST['user']) OR empty($_POST['pass'])))
{
        $errorMessage = 'Username and password required';
}

// No translations for login since user's lang not known as yet
$smarty->assign("errorMessage",$errorMessage);

