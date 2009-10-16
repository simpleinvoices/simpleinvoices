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

    public static function next($node, $sub_node="", $sub_node_2="")
    {

        global $db;
        global $auth_session;

       # $subnode = "";

        if ($sub_node !="")
        {
            $subnode = "and sub_node = ".$sub_node; 
        }
        if ($sub_node_2 !="")
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
        
        $sth = $db->query($sql,':node',$node,':domain_id',$auth_session->domain_id) or die(htmlspecialchars(end($dbh->errorInfo())));
 
        $index = $sth->fetch();

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

            $sql = "insert into si_index (id, node, sub_node, sub_node_2, domain_id) VALUES (:id, :node, :sub_node, :sub_node_2, :domain_id);";

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
                        sub_node = :sub_node
                    and
                        sub_node_2 = :sub_node_2";
        }

        $sth = $db->query($sql,':id',$next,':node',$node,':sub_node', $sub_node,':sub_node_2',$sub_node_2,':domain_id',$auth_session->domain_id) or die(htmlspecialchars(end($dbh->errorInfo())));

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

        $sth = $db->query($sql,':node',$node,':domain_id',$auth_session->domain_id) or die(htmlspecialchars(end($dbh->errorInfo())));


        return $sth;

    }
}

