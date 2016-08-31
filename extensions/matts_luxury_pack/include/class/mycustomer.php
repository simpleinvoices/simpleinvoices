<?php
/*
* Script: ./extensions/matts_luxury_pack/include/class/mycustomer.php
* 	Customers list XML page
*
* Authors:
*	 yumatechnical@gmail.com
*
* Last edited:
* 	 2016-08-29
*
* License:
*	 GPL v2 or above
*
* Website:
* 	http://www.simpleinvoices.org
 */

/*
class mycustomer extends Customer
{
	public $domain_id;
	
	public function __construct()
	{
		parent::__construct();
	}

	public static function insert()
	{
		$sql = "INSERT INTO ".TB_PREFIX."customers (
					domain_id, attention, name, street_address, street_address2,
					city, state, zip_code, country, phone, mobile_phone,
					fax, email, notes, custom_field1, custom_field2,
					custom_field3, custom_field4, enabled
				) VALUES (
					:domain_id ,:attention, :name, :street_address, :street_address2,
					:city, :state, :zip_code, :country, :phone, :mobile_phone,
					:fax, :email, :notes, :custom_field1, :custom_field2,
					:custom_field3, :custom_field4, :enabled
				)";

		return dbQuery($sql,
			':attention', $this->attention,
			':name', $this->name,
			':street_address', $this->street_address,
			':street_address2', $this->street_address2,
			':city', $this->city,
			':state', $this->state,
			':zip_code', $this->zip_code,
			':country', $this->country,
			':phone', $this->phone,
			':mobile_phone', $this->mobile_phone,
			':fax', $this->fax,
			':email', $this->email,
			':notes', $this->notes,
			':custom_field1', $this->custom_field1,
			':custom_field2', $this->custom_field2,
			':custom_field3', $this->custom_field3,
			':custom_field4', $this->custom_field4,
			':enabled', $this->enabled,
			':domain_id',$this->domain_id
		);
	}
*/
/*
	public function sql($type='', $start, $dir, $sort, $rp, $page )
	{
	        global $config;
	        global $LANG;
	        global $auth_session;

        	$valid_search_fields = array('c.id', 'c.name');

	        //SC: Safety checking values that will be directly subbed in
	        if (intval($page) != $page) {
        	        $start = 0;
		}

        if (intval($rp) != $rp) {
                $rp = 25;
        }

        /// *SQL Limit - start* /
        $start = (($page-1) * $rp);
        $limit = "LIMIT $start, $rp";

        if($type =="count")
        {
                unset($limit);
                $limit;
        }
        /// *SQL Limit - end* /


        if (!preg_match('/^(asc|desc)$/iD', $dir)) {
                $dir = 'DESC';
        }

        $where = "";
        $query = isset($_POST['query']) ? $_POST['query'] : null;
        $qtype = isset($_POST['qtype']) ? $_POST['qtype'] : null;
        if ( ! (empty($qtype) || empty($query)) ) {
                if ( in_array($qtype, $valid_search_fields) ) {
                        $where = " AND $qtype LIKE :query ";
                } else {
                        $qtype = null;
                        $query = null;
                }
        }

        /// *Check that the sort field is OK* /
        $validFields = array('CID', 'name', 'customer_total', 'paid', 'owing', 'enabled');

        if (in_array($sort, $validFields)) {
                $sort = $sort;
        } else {
                $sort = "CID";
        }

                //$sql = "SELECT * FROM ".TB_PREFIX."customers ORDER BY $sort $dir LIMIT $start, $limit";
                $sql = "SELECT
                                        c.id as CID
                                        , c.name as name
                                        , c.street_address as street_address
                                        , c.attention as attention
                                        , (SELECT (CASE  WHEN c.enabled = 0 THEN '".$LANG['disabled']."' ELSE '".$LANG['enabled']."' END )) AS enabled
                                        , SUM(COALESCE(IF(pr.status = 1, ii.total, 0),  0)) AS customer_total
                                        , COALESCE(ap.amount,0) AS paid
                                        , (SUM(COALESCE(IF(pr.status = 1, ii.total, 0),  0)) - COALESCE(ap.amount,0)) AS owing
                                FROM
                                        ".TB_PREFIX."customers c
                                        LEFT JOIN ".TB_PREFIX."invoices iv ON (c.id = iv.customer_id AND iv.domain_id = c.domain_id)
                                        LEFT JOIN ".TB_PREFIX."preferences pr ON (pr.pref_id = iv.preference_id AND pr.domain_id = iv.domain_id)
                                        LEFT JOIN ".TB_PREFIX."invoice_items ii ON (iv.id = ii.invoice_id AND iv.domain_id = ii.domain_id)
                                        LEFT JOIN (SELECT iv3.customer_id, p.domain_id, SUM(COALESCE(p.ac_amount, 0)) AS amount
                                                        FROM ".TB_PREFIX."payment p INNER JOIN si_invoices iv3
                                                ON (iv3.id = p.ac_inv_id AND iv3.domain_id = p.domain_id)
                                                        GROUP BY iv3.customer_id, p.domain_id
                                                ) ap ON (ap.customer_id = c.id AND ap.domain_id = c.domain_id)
                                WHERE c.domain_id = :domain_id
                                          $where
                                GROUP BY CID
                                ORDER BY
                                        $sort $dir
                                $limit";

                if (empty($query)) {
                        $result = dbQuery($sql, ':domain_id', $auth_session->domain_id);
                } else {
                        $result = dbQuery($sql, ':domain_id', $auth_session->domain_id, ':query', "%$query%");
                }
                return $result;
	}*//*
}*/

/**********************************************************/

