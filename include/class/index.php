<?php

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

        $sth = $db->query($sql,':id',$next,':node',$node,':sub_node', $sub_node,':domain_id',$auth_session->domain_id) or die(htmlspecialchars(end($dbh->errorInfo())));

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

        $sth = $db->query($sql,':node',$node,':domain_id',$auth_session->domain_id) or die(htmlspecialchars(end($dbh->errorInfo())));


        return $sth;

    }
}

