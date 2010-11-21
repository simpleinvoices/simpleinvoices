<?php

/*************************************************************************
 * File: db_peardb.php
 * Authors: Matthew Palmer <mpalmer@baileyroberts.com.au>
 *
 * A PEAR DB abstraction layer backend for PHPReports.  Acts a little bit
 * differently to other PHPReports backends; I don't necessarily think
 * that's a problem at my end.
 *
 * Easiest way to use this is to pass a DSN into the report via the server
 * parameter.  The rest of the DB parameters are ignored.
 *
 * Copyright (C) 2004 Bailey Roberts Group Pty Ltd.
 * 
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation (version 2 of the License)
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA 02111-1307, USA.
 ****************************************************************************/
           
require_once 'DB.php';

class PHPR_PEARDB_Result
{
	var $index = 0;
	
	var $data = array();
	
	function PHPR_PEARDB_Result($res)
	{
		while ($row = $res->fetchRow(DB_FETCHMODE_ASSOC))
		{
			$this->data[] = $row;
		}

		$this->index = 0;
	}

	function fetchNext()
	{
		if ($this->index >= count($this->data))
		{
			return NULL;
		}
		return $this->data[$this->index++];
	}
	
	function fieldNames()
	{
		return array_keys($this->data[0]);
	}

	function numCols()
	{
		return count($this->data[0]);
	}
}

// This database type is a little different to the average.  You can
// either specify a DSN to connect to via the 'server' parameter, or
// otherwise specify a global variable name in the 'database'
// parameter which contains the PEAR database object you want to
// use.  'server' trumps 'database' if both are supplied.
class PHPReportsDBI {
	function db_connect($cdata)
	{
		if ($cdata[2])
		{
			$DB = DB::Connect($cdata[2]);
		}
		else
		{
			global ${$cdata[3]};
			
			$DB = ${$cdata[3]};
		}

		if (DB::isError($DB))
		{
			die($DB->getMessage()."\n".$DB->getUserInfo()."\n");
		}
		
		return $DB;		
	}

	function db_select_db($sDatabase)
	{
		// You wanna DB?  Pick it in the DSN
		return;
	}

	function db_query(&$DB,$SQL)
	{
		$res = $DB->query($SQL);
		
		$rv = new PHPR_PEARDB_Result($res);
		$res->free();
		return $rv;
	}

	function db_colnum($res)
	{
		return $res->numCols();
	}

	function db_columnName($res,$i)
	{
		$names = $res->fieldNames();
		return $names[$i-1];
	}
		
	function db_columnType($res,$i)
	{
		// The type of a field should be irrelevant
		return 'INT';
	}

	// An auto-increment fetch
	function db_fetch(&$res)
	{
		$row = $res->fetchNext();
		return $row;
	}

	function db_free($res)
	{
		return 0;
	}

	function db_disconnect($DB)
	{
		$DB->disconnect();
	}
}	
