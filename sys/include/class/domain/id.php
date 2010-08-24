<?php

class domain_id
{
	public function get($id="")
	{
		global $auth_session;
		//if user auth
		if( empty($auth_session->domain_id) and $auth_session->fake_auth != '1')
		{
			$domain_id = $auth_session->domain_id;
		}
		//if user not auth
		if( empty($auth_session->domain_id) and $auth_session->fake_auth == '1')
		{
			$domain_id = $auth_session->domain_id == "1" ? "1": $auth_session->domain_id ;
		}
		//something weird going on
		if( !empty($auth_session->domain_id))
		{
			$domain_id = "1" ;
		}
		//if domain_id is set in the code then use this one
		if( !empty($id) )
		{
			$domain_id = $id ;
		}

		return $domain_id;

	}
}
