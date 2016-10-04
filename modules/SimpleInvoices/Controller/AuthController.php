<?php
/**
 * Simple Invoices (http://www.simpleinvoices.org/)
 *
 * @link      http://github.com/simpleinvoices/simpleinvoices for the source repository
 * @copyright Copyright (c) 2005-2016 Simple Invoices Community (http://www.simpleinvoices.org)
 * @license   https://www.gnu.org/licenses/gpl-3.0.html GPL v3
 */

namespace SimpleInvoices\Controller;

class AuthController
{
    protected $menu;
    
    protected $smarty;

    /**
     * TODO: Don't use globals!
     */
    public function __construct()
    {
        global $smarty;
        global $menu;

        $this->smarty = $smarty;
        $this->menu   = $menu;
    }
 
    public function loginAction()
    {
        global $zendDb;
        
        $this->menu = false;
     
        \Zend_Session::start();
        
        $errorMessage = '';
        
        if (!empty($_POST['user']) && !empty($_POST['pass']))
        {
            $authAdapter = new \Zend_Auth_Adapter_DbTable($zendDb);
        
            $PatchesDone = getNumberOfDoneSQLPatches();
        
            //sql patch 161 changes user table name - need to accomodate
            $user_table    = ($PatchesDone < "161") ? "users" : "user";
            $user_email    = ($PatchesDone < "184") ? "user_email" : "email";
            $user_password = ($PatchesDone < "184") ? "user_password" : "password";
        
            $authAdapter->setTableName(TB_PREFIX.$user_table)
                        ->setIdentityColumn($user_email)
                        ->setCredentialColumn($user_password)
                        ->setCredentialTreatment('MD5(?)');
        
            $userEmail = $_POST['user'];
            $password  = $_POST['pass'];
        
            // Set the input credential values (e.g., from a login form)
            $authAdapter->setIdentity($userEmail)
                        ->setCredential($password);
        
            // Perform the authentication query, saving the result
            $result = $authAdapter->authenticate();
        
            if ($result->isValid()) {
                \Zend_Session::start();
        
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
                $authNamespace = new \Zend_Session_Namespace('Zend_Auth');
                $authNamespace->setExpirationSeconds(60 * 60);
                foreach ($result as $key => $value)
                {
                    $authNamespace->$key = $value;
                }
        
                if ($authNamespace->role_name == 'customer' && $authNamespace->user_id > 0) {
                    header('Location: index.php?module=customers&view=details&action=view&id='.$authNamespace->user_id);
                } else {
                    header('Location: .');
                }
            } else {
                $errorMessage = 'Sorry, wrong user / password';
            }
        }
        
        if($_POST['action'] == 'login' && (empty($_POST['user']) OR empty($_POST['pass'])))
        {
            $errorMessage = 'Username and password required';
        }
        
        // No translations for login since user's lang not known as yet
        $this->smarty->assign("errorMessage",$errorMessage);
    }
    
    public function logoutAction()
    {
        $this->menu = false;
        
        // we must never forget to start the session
        // so config.php works ok without using index.php
        \Zend_Session::start();
        \Zend_Session::destroy(true);
        
        header('Location: .');
    }
}