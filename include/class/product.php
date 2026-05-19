<?php

class product
{
	public $domain_id;
    
	public function __construct()
	{
		$this->domain_id = domain_id::get($this->domain_id);
	}

    public function count()
    {

         $sql = "SELECT count(id) as count FROM ".TB_PREFIX."products WHERE domain_id = :domain_id ORDER BY id";
         $sth  = dbQuery($sql,':domain_id',$this->domain_id);
 
         return $sth->fetch();

    }

    public function get_all()
    {

         $sql = "SELECT * FROM ".TB_PREFIX."products WHERE domain_id = :domain_id AND visible = 1 ORDER BY description, id";
         $sth  = dbQuery($sql,':domain_id',$this->domain_id);
 
         return $sth->fetchAll();

    }

    public function get($id)
    {

         $sql = "SELECT * FROM ".TB_PREFIX."products WHERE domain_id = :domain_id AND id = :id";
         $sth  = dbQuery($sql,':domain_id',$this->domain_id, ':id',$id);
 
         return $sth->fetch();

    }

    public function select_all($type='', $dir, $sort, $rp, $page )
    {
        global $config;
        global $LANG;

        $valid_search_fields = array('id', 'description', 'unit_price');

        if (intval($rp) != $rp) {
            $rp = 25;
        }
        /*SQL Limit - start*/
        $start = (($page-1) * $rp);
        $limit = "LIMIT $rp OFFSET $start";

        if ($type == "count") {
            unset($limit);
        }
        /*SQL Limit - end*/

        if (!preg_match('/^(asc|desc)$/iD', $dir)) {
            $dir = 'DESC';
        }

        $where = "";
        $query = $_POST['query'] ?? null;
        $qtype = $_POST['qtype'] ?? null;
        if ( ! (empty($qtype) || empty($query)) ) {
            if ( in_array($qtype, $valid_search_fields) ) {
                $where = " AND p.$qtype LIKE :query ";
            } else {
                $qtype = null;
                $query = null;
            }
        }

        /*Check that the sort field is OK*/
        $validFields = array('id', 'description', 'unit_price', 'enabled');

        if (!in_array($sort, $validFields)) {
            $sort = "p.id";
        }

        // Use LEFT JOINs with pre-aggregated subqueries so that:
        //  - each named param (:domain_id, :domain_id2, :domain_id3) is unique (required by
        //    PostgreSQL and SQLite PDO drivers which do not support duplicate named params)
        //  - qty_in/qty_out are real column references, not aliases (alias references in the
        //    same SELECT list are not portable across MySQL, PostgreSQL, or SQLite)
        $sql = "SELECT
                    p.id,
                    p.description,
                    p.unit_price,
                    COALESCE(sold.qty_out, 0) AS qty_out,
                    COALESCE(stk.qty_in, 0) AS qty_in,
                    COALESCE(p.reorder_level, 0) AS reorder_level,
                    (COALESCE(stk.qty_in, 0) - COALESCE(sold.qty_out, 0)) AS quantity,
                    CASE WHEN p.enabled = 0 THEN '".$LANG['disabled']."' ELSE '".$LANG['enabled']."' END AS enabled
                FROM
                    ".TB_PREFIX."products p
                LEFT JOIN (
                    SELECT ii.product_id, SUM(ii.quantity) AS qty_out
                    FROM ".TB_PREFIX."invoice_items ii
                    JOIN ".TB_PREFIX."invoices inv ON ii.invoice_id = inv.id
                    JOIN ".TB_PREFIX."preferences pref ON inv.preference_id = pref.pref_id
                    WHERE ii.domain_id = :domain_id AND pref.status = 1
                    GROUP BY ii.product_id
                ) sold ON sold.product_id = p.id
                LEFT JOIN (
                    SELECT product_id, SUM(quantity) AS qty_in
                    FROM ".TB_PREFIX."inventory
                    WHERE domain_id = :domain_id2
                    GROUP BY product_id
                ) stk ON stk.product_id = p.id
                WHERE
                    p.visible = 1
                    AND p.domain_id = :domain_id3
                    $where
                ORDER BY
                    $sort $dir
                $limit";

        if (empty($query)) {
            $result = dbQuery($sql, ':domain_id', $this->domain_id, ':domain_id2', $this->domain_id, ':domain_id3', $this->domain_id);
        } else {
            $result = dbQuery($sql, ':domain_id', $this->domain_id, ':domain_id2', $this->domain_id, ':domain_id3', $this->domain_id, ':query', "%$query%");
        }

        return $result;
    }

}
