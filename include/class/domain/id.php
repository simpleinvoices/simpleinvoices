<?php

class domain_id
{
	public function get($id="")
	{
		global $auth_session;

		// default when session value absent - fake auth, whether auth needed or not
		$domain_id = "1";
		
		if( !empty($id) ) {

			//if domain_id is set in the code then use this one
			$domain_id = $id;
			
		} else {

			// no preset value available

			if (!empty($auth_session->domain_id)) {

				// take session value since available 
				// whether fake_auth or not
				$domain_id = $auth_session->domain_id;

			}
		}

		return $domain_id;
	}
}
