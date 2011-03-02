<?php

/*
Class: Index

Purpose: The index class is the replacement for primary keys as the ID field in various tables - ie. si_invoices 

Details:
$node = this is the module in question - ie 'invoice', 'products' etc..
$sub_node = the sub set of the node - ie. this is the 'invoice preference' if node = 'invoice'
$sub_node_2 = 2nd sub set of the node - ir. this is the 'biller' if node = 'invoice'

*/

class index
{

    public static function next($node, $sub_node="")
    {

        global $db;
        global $auth_session;

       # $subnode = "";

        if ($sub_node !="")
        {
            $subnode = "and sub_node = ".$sub_node; 
        }
    
        $sql = "select 
                    id 
                from 
                    si_index 
                where
                    domain_id = :domain_id
                and
                    node = :node
               ".$subnode;
        
        $sth = $db->query($sql,':node',$node,':domain_id',$auth_session->domain_id) or die(htmlsafe(end($dbh->errorInfo())));
 
        $index = $sth->fetch();

        if($index['id'] == "")
        {
            $id = "1";
        
        } else {
            
            $id = $index['id'] + 1;
        }
        
        return $id;

    }

    public static function increment($node,$sub_node="")
    {
    
        $next = index::next($node,$sub_node);

        global $db;
        global $auth_session;
        
        /*
        if ($sub_node !="") 
        {
            $subnode = "and sub_node = ".$sub_node; 
        }
        */

        if ($next == 1)
        {

            $sql = "insert into si_index (id, node, sub_node, domain_id) VALUES (:id, :node, :sub_node, :domain_id);";

        } else {

            $sql ="update
                        si_index 
                    set 
                        id = :id 
                    where
                        node = :node
                    and
                        domain_id = :domain_id
                    and
                        sub_node = :sub_node";
        }

        $sth = $db->query($sql,':id',$next,':node',$node,':sub_node', $sub_node,':domain_id',$auth_session->domain_id) or die(htmlsafe(end($dbh->errorInfo())));

        return $next;

    }


    public static function rewind()
    {

        global $db;
        global $auth_session;
        
        if ($sub_node !="")
        {
            $subnode = "and sub_node = ".$sub_node; 
        }

        $sql ="update
                    si_index 
                set 
                    id = (id - 1) 
                where
                    node = :node
                and
                    domain_id = :domain_id
                ".$subnode;

        $sth = $db->query($sql,':node',$node,':domain_id',$auth_session->domain_id) or die(htmlsafe(end($dbh->errorInfo())));


        return $sth;

    }
}



/************ old code - with sub node 2 - invoice numbering by biller stuff
class index
{

	
    public static function select($node, $sub_node="", $sub_node_2="")
    {


        if ($sub_node !="")
        {
            $subnode = "and sub_node = ".$sub_node; 
        }
        if ($sub_node_2 !="" and $defaults['invoice_numbering_by_biller'] == "1")
        {
            $subnode2 = " and sub_node_2 = ".$sub_node_2; 
        }
        global $db;
        $sql = "select 
                    id 
                from 
                    si_index 
                where
                    domain_id = :domain_id
                and
                    node = :node
               ".$subnode.$subnode2;
        
        $sth = $db->query($sql,':node',$node,':domain_id',$auth_session->domain_id) or die(htmlsafe(end($dbh->errorInfo())));
 
        $index = $sth->fetch();

	if ( empty($index))
	{
        	$index['id'] = '0';
	}
	
        return $index['id'];

   }
    public static function next($node, $sub_node="", $sub_node_2="")
    {

        global $db;
        global $auth_session;

	$defaults = getSystemDefaults();
	#
	#if billnum on  & id = null then check the default incremetn id for that sub_node and use that

       # $subnode = "";

        if ($sub_node !="")
        {
            $subnode = "and sub_node = ".$sub_node; 
        }
        if ($sub_node_2 !="" and $defaults['invoice_numbering_by_biller'] == "1")
        {
            $subnode2 = " and sub_node_2 = ".$sub_node_2; 
        }
    
        $sql = "select 
                    id 
                from 
                    si_index 
                where
                    domain_id = :domain_id
                and
                    node = :node
               ".$subnode.$subnode2;
        
        $sth = $db->query($sql,':node',$node,':domain_id',$auth_session->domain_id) or die(htmlsafe(end($dbh->errorInfo())));
 
        $index = $sth->fetch();
        
        //this handles if numbering by biller turned on after x invoices already created - 
        if($index['id'] == "" AND $defaults['invoice_numbering_by_biller'] == "1")
        {

		$sql2 = "select 
			    id 
			from 
			    si_index 
			where
			    domain_id = :domain_id
			and
			    node = :node
		       ".$subnode;

                $sth2 = $db->query($sql2,':node',$node,':domain_id',$auth_session->domain_id) or die(htmlsafe(end($dbh->errorInfo())));
                $index2 = $sth2->fetch();
                $index['id'] = $index2['id'];
        }
	if($index['id'] == "")
        {
            $id = "1";
        
        } else {
            
            $id = $index['id'] + 1;
        }

        return $id;

    }

    public static function increment($node,$sub_node="",$sub_node_2="")
    {
    

        $next = index::next($node,$sub_node,$sub_node_2);
        $current = index::select($node,$sub_node,$sub_node_2);
	#echo "next:".$next."current:".$current;
	$defaults = getSystemDefaults();
        
	global $db;
        global $auth_session;
        
        
     #   if ($sub_node !="") 
     #   {
     #       $subnode = "and sub_node = ".$sub_node; 
     #   }
        
        if ($next == '1' OR ($current = '0' AND ($next != $current +1)) )
        {

		if ($defaults['invoice_numbering_by_biller'] == '0')
		{	
		    $sql = "insert 
				into si_index 
				(
					id, 
					node, 
					sub_node,
					domain_id
				) 
				VALUES 
				(
					:id, 
					:node, 
					:sub_node, 
					:domain_id
				);";
		} else {
		    $sql = "insert 
				into si_index 
				(
					id, 
					node, 
					sub_node,
					sub_node_2,
					domain_id
				) 
				VALUES 
				(
					:id, 
					:node, 
					:sub_node, 
					:sub_node_2, 
					:domain_id
				);";

		}

        } else {

		if ($defaults['invoice_numbering_by_biller'] == '0')
		{	
		    $sql ="update
				si_index 
			    set 
				id = :id 
			    where
				node = :node
			    and
				domain_id = :domain_id
			    and
				sub_node = :sub_node;";
		 } else {

			## need invoice::get()
			##if current = "" then do insert not update
		    $sql ="update 
				si_index 
			    set 
				id = :id 
			    where
				node = :node
			    and
				domain_id = :domain_id
			    and
				sub_node = :sub_node
		           and 	
				sub_node_2 = :sub_node_2;";
		}
	
	}

	if ($defaults['invoice_numbering_by_biller'] == '0')
	{	
		$sth = $db->query($sql,':id',$next,':node',$node,':sub_node', $sub_node,':domain_id',$auth_session->domain_id) or die(htmlsafe(end($dbh->errorInfo())));
	} else {
		$sth = $db->query($sql,':id',$next,':node',$node,':sub_node', $sub_node,':sub_node_2',$sub_node_2,':domain_id',$auth_session->domain_id) or die(htmlsafe(end($dbh->errorInfo())));
	}


        return $next;

    }


    public static function rewind()
    {

        global $db;
        global $auth_session;
        
        if ($sub_node !="")
        {
            $subnode = "and sub_node = ".$sub_node; 
        }
        if ($sub_node_2 !="")
        {
            $subnode2 = "and sub_node_2 = ".$sub_node_2; 
        }

        $sql ="update
                    si_index 
                set 
                    id = (id - 1) 
                where
                    node = :node
                and
                    domain_id = :domain_id
                ".$subnode.$subnode2;

        $sth = $db->query($sql,':node',$node,':domain_id',$auth_session->domain_id) or die(htmlsafe(end($dbh->errorInfo())));


        return $sth;

    }
}
*/


