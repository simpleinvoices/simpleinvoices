<?php

/*
 * Class: Index
 *
 * Purpose: The index class is the replacement for primary keys as the ID field in various tables - ie. si_invoices
 *
 * Details:
 * $node = this is the module in question - ie 'invoice', 'products' etc..
 * $sub_node = the sub set of the node - ie. this is the 'invoice preference' if node = 'invoice'
 * $sub_node_2 = 2nd sub set of the node - ir. this is the 'biller' if node = 'invoice'
 */
class index {
    public static function next($node, $sub_node = 0, $domain_id = '', $sub_node_2 = 0) {
        $domain_id = domain_id::get ( $domain_id );

        $sql = "SELECT id FROM " . TB_PREFIX . "index
                WHERE node = :node
                  AND   sub_node   = :sub_node
                  AND   sub_node_2 = :sub_node_2
                  AND   domain_id  = :domain_id";

        $sth = dbQuery ( $sql, ':node', $node, ':sub_node', $sub_node, ':sub_node_2', $sub_node_2, ':domain_id', $domain_id );

        $index = $sth->fetch ();

        if ($index ['id'] == "")
            $id = 1;
        else
            $id = $index ['id'] + 1;

        return $id;
    }

    public static function increment($node, $sub_node = 0, $domain_id = '', $sub_node_2 = 0) {
        $domain_id = domain_id::get ( $domain_id );
        $next = index::next ( $node, $sub_node, $domain_id, $sub_node_2 );

        if ($next == 1) {
            $sql = "INSERT INTO " . TB_PREFIX . "index (id, node, sub_node, sub_node_2, domain_id)
                    VALUES (:id, :node, :sub_node, :sub_node_2, :domain_id)";
        } else {
            $sql = "UPDATE " . TB_PREFIX . "index
                    SET id = :id
                    WHERE node       = :node
                      AND sub_node   = :sub_node
                      AND sub_node_2 = :sub_node_2
                      AND domain_id  = :domain_id";
        }
        $sth = dbQuery ( $sql, ':id'        , $next,
                               ':node'      , $node,
                               ':sub_node'  , $sub_node,
                               ':sub_node_2', $sub_node_2,
                               ':domain_id' , $domain_id );

        return $next;
    }

    public static function rewind($node, $sub_node = 0, $domain_id = '', $sub_node_2 = 0) {
        $domain_id = domain_id::get ( $domain_id );

        $sql = "UPDATE " . TB_PREFIX . "index
                SET id = (id - 1)
                WHERE node     = :node
                AND sub_node   = :sub_node
                AND sub_node_2 = :sub_node_2
                AND domain_id  = :domain_id";
        $sth = dbQuery ( $sql, ':node'      , $node,
                               ':sub_node'  , $sub_node,
                               ':sub_node_2', $sub_node_2,
                               ':domain_id' , $domain_id );

        return $sth;
    }
}
