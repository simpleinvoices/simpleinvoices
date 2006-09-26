<?php

/*
	This file is part of ActiveLink PHP NET Package (www.active-link.com).
	Copyright (c) 2002-2004 by Zurab Davitiani

	You can contact the author of this software via E-mail at
	hattrick@mailcan.com

	ActiveLink PHP NET Package is free software; you can redistribute it and/or modify
	it under the terms of the GNU Lesser General Public License as published by
	the Free Software Foundation; either version 2.1 of the License, or
	(at your option) any later version.

	ActiveLink PHP NET Package is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU Lesser General Public License for more details.

	You should have received a copy of the GNU Lesser General Public License
	along with ActiveLink PHP NET Package; if not, write to the Free Software
	Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*/

/*
 *	requires Socket class
 */
import("org.active-link.net.Socket");

/**
  *	HTTPServer class provides functionality to receive HTTP requests and serve responses
  *	@class		HTTPServer
  *	@package	org.active-link.net
  *	@author		Zurab Davitiani
  *	@version	0.4.0
  *	@extends	Socket
  *	@requires	Socket
  *	@see		Socket
  */

class HTTPServer extends Socket {

	// protected properties
    var $defaultServer;

	function HTTPServer () {
		$this->defaultServer = "ActiveLink NET Object/0.1";
	}

}
