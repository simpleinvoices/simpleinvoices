<?php

class domain_id
{
    /**
     * Get the current domain id for the application.
     * 
     * @param string $id
     * @return string
     */
	public static function get($id = "")
	{
		$auth_session = new Zend_Session_Namespace('Zend_Auth');
		
		// default when session value absent - fake auth, whether auth needed or not
		$domain_id = "1";
		
		if( empty($id) && isset($auth_session->domain_id) ) {
			if (!empty($auth_session->domain_id)) {
				// take session value since available 
				// whether fake_auth or not
				$domain_id = $auth_session->domain_id;
			}
		}

		return $domain_id;
	}
}
