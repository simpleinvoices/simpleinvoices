<?php

class domain_id
{
	public function get($id="")
	{
		global $auth_session;

		// default value
		$domain_id = "1";
		
		if( !empty($id) ) {

			//if domain_id is set in the code then use this one
			$domain_id = $id;
			
		} else {

			// no preset value available

			if ( $auth_session->fake_auth == '1') {
				// no auth needed

				if (!empty($auth_session->domain_id)) {

					// take session value since available
					$domain_id = $auth_session->domain_id;

				} else {

					// no session value available, retain default value, weird - not possible
					// $domain_id = "1";

				}
			} else {

				// user auth enabled
				$domain_id = $auth_session->domain_id;

			}
		}

		return $domain_id;
	}
}
