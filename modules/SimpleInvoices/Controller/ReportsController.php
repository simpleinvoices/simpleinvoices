<?php
namespace SimpleInvoices\Controller;

/**
 * @author Juan Pedro Gonzalez Gutierrez
 */
class ReportsController
{
    protected $smarty;
    
    public function __construct()
    {
        global $smarty;
        
        $this->smarty = $smarty;
    }
    
    public function databaseLogAction()
    {
        global $auth_session;
    
        // TODO: Here we couldn't get smarty to go last!
        //       Refactor code!
        $this->smarty->assign('pageActive', 'report');
        $this->smarty->assign('active_tab', '#home');
        
        $this->smarty->display("../templates/default/menu.tpl");
        $this->smarty->display("../templates/default/main.tpl");
        
        $startdate	= (isset($_POST['startdate'])) ? $_POST['startdate'] : date("Y-m-d",strtotime("last Year"));
        $startdate  = htmlsafe($startdate);
        $enddate	= (isset($_POST['enddate']))   ? $_POST['enddate']   : date("Y-m-d",strtotime("now"));
        $enddate    = htmlsafe($enddate);
        
        $sql = "SELECT l.*, u.email
	                FROM
	                    ".TB_PREFIX."log l INNER JOIN
	                    ".TB_PREFIX."user u ON (u.id = l.userid AND u.domain_id = l.domain_id)
	                WHERE l.domain_id = :domain_id
	                    AND l.timestamp BETWEEN :start AND :end
	                ORDER BY l.timestamp";
        
        $sth  = dbQuery($sql, ':start', $startdate, ':end', $enddate, ':domain_id', $auth_session->domain_id);
        $sqls = null;
        $sqls = $sth->fetchAll();
        
        echo <<<EOD
		<div style="text-align:left;">
		<br /><br />
		<form action="index.php?module=reports&amp;view=database_log" method="post">
		<input type="text" class="date-picker" name="startdate" id="date1" value='$startdate' /><br /><br />
		<input type="text" class="date-picker" name="enddate" id="date2" value='$enddate' /><br /><br />
		<input type="submit" value="Show">
		</form>
EOD;
        
        echo "<br /><b>Invoice created</b><br />";
        
        foreach($sqls as $sql) {
            $pattern = "/.*INSERT\s+INTO\s+".TB_PREFIX."invoices\s+/im";
        
            if(preg_match($pattern,$sql['sqlquerie'])) {
                $user = htmlsafe($sql['email']).' (id '.htmlsafe($sql['userid']).')';
                echo "User $user created invoice $sql[last_id] on $sql[timestamp].<br />";
            }
        }
        
        echo "<br /><b>Invoice modified</b><br />";
        foreach($sqls as $sql) {
            $pattern = "/.*UPDATE\s+".TB_PREFIX."invoices\s+SET/im";
            if(preg_match($pattern,$sql['sqlquerie'],$match)) {
                $user = htmlsafe($sql['email']).' (id '.htmlsafe($sql['userid']).')';
                echo "User $user modified invoice $match[1] on $sql[timestamp].<br />";
            }
        }
        
        echo "<br /><b>Payment Process</b><br />";
        
        foreach($sqls as $sql) {
            $pattern = "/.*INSERT\s+INTO\s+".TB_PREFIX."payment\s+/im";
        
            if(preg_match($pattern,$sql['sqlquerie'],$match)) {
                $user = htmlsafe($sql['email']).' (id '.htmlsafe($sql['userid']).')';
                echo "User $user processed invoice $match[1] on $sql[timestamp] with amount $match[2].<br />";
            }
        }
        
        echo "</div>";
        exit();
    }
    
    public function indexAction()
    {
        $this->smarty->assign('pageActive', 'report');
        $this->smarty->assign('active_tab', '#home');
    }
    
    public function reportBillerByCustomerAction()
    {
        global $auth_session;
        
        $sql = "
            SELECT
                b.name  AS Biller,
	            c.name AS Customer,
	            SUM(ii.total) AS SUM_TOTAL
            FROM ".TB_PREFIX."biller b
                INNER JOIN ".TB_PREFIX."invoices iv ON (b.id = iv.biller_id AND b.domain_id = iv.domain_id)
                INNER JOIN ".TB_PREFIX."invoice_items ii ON (ii.invoice_id = iv.id AND ii.domain_id = iv.domain_id)
                INNER JOIN ".TB_PREFIX."preferences pr ON (pr.pref_id = iv.preference_id AND pr.domain_id = iv.domain_id)
	            INNER JOIN ".TB_PREFIX."customers c ON (c.id = iv.customer_id AND c.domain_id = iv.domain_id)
            WHERE
	            pr.status ='1'
	            AND b.domain_id = :domain_id
            GROUP BY
	            b.name, c.name
        ";
        
        $customer_result = dbQuery($sql, ':domain_id', $auth_session->domain_id);
        
        $billers = array();
        $total_sales = 0;
        
        while($customer = $customer_result->fetch()) {
            $c = array();
            $c['name'] = $customer['Customer'];
            $c['sum_total'] = $customer['SUM_TOTAL'];
        
            $billers[$customer['Biller']]['name'] = $customer['Biller'];
        
            if (!array_key_exists('customers', $billers[$customer['Biller']])) {
                $billers[$customer['Biller']]['customers'] = array();
            }
        
            array_push($billers[$customer['Biller']]['customers'], $c);
        
            $billers[$customer['Biller']]['total_sales'] += $customer['SUM_TOTAL'];
        
            $total_sales += $customer['SUM_TOTAL'];
        }
        
        $this->smarty->assign('data', $billers);
        $this->smarty->assign('total_sales', $total_sales);
        $this->smarty->assign('pageActive', 'report');
        $this->smarty->assign('active_tab', '#home');
    }
    
    public function reportBillerTotalAction()
    {
        global $auth_session;
        
        $sql = "
            SELECT
                b.name
              , SUM(ii.total) AS sum_total
            FROM ".TB_PREFIX."biller b
                INNER JOIN ".TB_PREFIX."invoices iv ON (b.id = iv.biller_id AND b.domain_id = iv.domain_id)
                INNER JOIN ".TB_PREFIX."invoice_items ii ON (ii.invoice_id = iv.id AND ii.domain_id = iv.domain_id)
                INNER JOIN ".TB_PREFIX."preferences pr ON (pr.pref_id = iv.preference_id AND pr.domain_id = iv.domain_id)
            WHERE
            	    pr.status ='1'
            	AND b.domain_id = :domain_id
            GROUP BY
            	b.name
        ";
        
        $biller_sales = dbQuery($sql, ':domain_id', $auth_session->domain_id);
        
        $total_sales = 0;
        $billers = array();
        
        while($biller = $biller_sales->fetch()) {
            $total_sales += $biller['sum_total'];
            array_push($billers, $biller);
        }
        
        $this->smarty->assign('data', $billers);
        $this->smarty->assign('total_sales', $total_sales);
        $this->smarty->assign('pageActive', 'report');
        $this->smarty->assign('active_tab', '#home');
    }
    
    public function reportDebtorsAgingTotalAction()
    {
        global $auth_session;
        
        if ($db_server == 'pgsql') {
            $sql = "
                SELECT
                    sum(coalesce(ii.total, 0)) AS inv_total,
                    sum(coalesce(ap.ac_amount, 0)) AS inv_paid,
                    
                    sum(coalesce(ii.total, 0)) -
                    sum(coalesce(ap.ac_amount, 0)) AS inv_owing,
                    
                    (CASE   WHEN age(iv.date) <= '14 days'::interval THEN '0-14'
                            WHEN age(iv.date) <= '30 days'::interval THEN '15-30'
                            WHEN age(iv.date) <= '60 days'::interval THEN '31-60'
                            WHEN age(iv.date) <= '90 days'::interval THEN '61-90'
                            ELSE '90+'
                    END) AS aging
                FROM
                    ".TB_PREFIX."invoices iv LEFT JOIN
                    (
                        SELECT 
                            i.invoice_id, 
                            i.domain_id, 
                            SUM(i.total) AS total
                        FROM 
                            ".TB_PREFIX."invoice_items i 
                        GROUP BY 
                            i.invoice_id, 
                            i.domain_id
                    ) ii ON (iv.id = ii.invoice_id AND iv.domain_id = ii.domain_id) LEFT JOIN
                    (
                        SELECT 
                            p.ac_inv_id, 
                            p.domain_id, 
                            SUM(p.ac_amount) AS ac_amount
                        FROM ".TB_PREFIX."payment p GROUP BY p.ac_inv_id, p.domain_id
                    ) ap ON (iv.id = ap.ac_inv_id AND iv.domain_id = ap.domain_id)
                WHERE iv.domain_id = :domain_id
                GROUP BY
                    aging
                ORDER BY
	               aging DESC;
            ";
        } else {
            $sql = "SELECT
                SUM(COALESCE(ii.total, 0)) AS inv_total
              , COALESCE(ap.inv_paid, 0) AS inv_paid
              , SUM(COALESCE(ii.total, 0)) - COALESCE(ap.inv_paid, 0) AS inv_owing
              , (CASE WHEN DATEDIFF(NOW(),DATE) <= '14 days' THEN '0-14'
                      WHEN DATEDIFF(NOW(),DATE) <= '30 days' THEN '15-30'
                      WHEN DATEDIFF(NOW(),DATE) <= '60 days' THEN '31-60'
                      WHEN DATEDIFF(NOW(),DATE) <= '90 days' THEN '61-90'
                      ELSE '90+'
                  END) AS aging
        FROM
              ".TB_PREFIX."invoice_items ii
              LEFT JOIN ".TB_PREFIX."invoices iv ON (ii.invoice_id = iv.id AND ii.domain_id = iv.domain_id)
              LEFT JOIN ".TB_PREFIX."preferences pr   ON (iv.preference_id = pr.pref_id AND pr.domain_id = iv.domain_id)
              LEFT JOIN (
          SELECT ap1.domain_id, SUM(COALESCE(ap1.ac_amount, 0)) AS inv_paid
          FROM  ".TB_PREFIX."payment ap1
              LEFT JOIN ".TB_PREFIX."invoices iv1   ON (ap1.ac_inv_id = iv1.id AND ap1.domain_id = iv1.domain_id)
              LEFT JOIN ".TB_PREFIX."preferences pr1 ON (pr1.pref_id = iv1.preference_id AND pr1.domain_id = iv1.domain_id)
          WHERE pr1.status = 1
          GROUP BY ap1.domain_id
          ) ap ON (ap.domain_id = iv.domain_id)
        WHERE
                  pr.status   = 1
              AND ii.domain_id = :domain_id
        GROUP BY
        	aging;
          ";
        }
        
        $results = dbQuery($sql, ':domain_id', $auth_session->domain_id);
        
        $sum_total = 0;
        $sum_paid  = 0;
        $sum_owing = 0;
        $periods   = array();
        
        while($period = $results->fetch()) {
            $sum_total += $period['inv_total'];
            $sum_paid  += $period['inv_paid'];
            $sum_owing += $period['inv_owing'];
            array_push($periods, $period);
        }
        
        $this->smarty->assign('data', $periods);
        $this->smarty->assign('sum_total', $sum_total);
        $this->smarty->assign('sum_paid', $sum_paid);
        $this->smarty->assign('sum_owing', $sum_owing);
        $this->smarty->assign('pageActive', 'report');
        $this->smarty->assign('active_tab', '#home');
    }
    
    public function reportDebtorsByAgingAction()
    {
        global $auth_session;
        
        if ($db_server == 'pgsql') {
            $sql = "SELECT
        iv.id,
        b.name AS biller,
        c.name AS customer,
        
        coalesce(ii.total, 0) AS inv_total,
        coalesce(ap.total, 0) AS inv_paid,
        coalesce(ii.total, 0) - coalesce(ap.total, 0) as inv_owing,
        
        to_char(iv.date,'YYYY-MM-DD') as date,
        age(iv.date) as age,
        (CASE   WHEN age(iv.date) <= '14 days'::interval THEN '0-14'
                WHEN age(iv.date) <= '30 days'::interval THEN '15-30'
                WHEN age(iv.date) <= '60 days'::interval THEN '31-60'
                WHEN age(iv.date) <= '90 days'::interval THEN '61-90'
                ELSE '90+'
        END) as aging
        
	FROM
        ".TB_PREFIX."invoices iv INNER JOIN
	".TB_PREFIX."biller b ON (b.id = iv.biller_id) INNER JOIN
	".TB_PREFIX."customers c ON (c.id = iv.customer_id) LEFT JOIN
        (SELECT i.invoice_id, sum(i.total) AS total
         FROM ".TB_PREFIX."invoice_items i GROUP BY i.invoice_id
        ) ii ON (iv.id = ii.invoice_id) LEFT JOIN
        (SELECT p.ac_inv_id, sum(p.ac_amount) AS total
         FROM ".TB_PREFIX."payments p GROUP BY p.ac_inv_id
        ) ap ON (iv.id = ap.ac_inv_id)
	ORDER BY
        age DESC;
    ";
        } else {
        
            $sql = "SELECT
			iv.id,
			iv.index_id,
			pr.pref_inv_wording,
			b.name AS biller,
			c.name AS customer,
--			COUNT(ii.invoice_id) AS items,
			SUM(COALESCE(ii.total, 0)) AS inv_total,
			COALESCE(ap.inv_paid, 0) AS inv_paid,
--    inv_total - inv_paid AS inv_owing,
			SUM(COALESCE(ii.total, 0)) - COALESCE(ap.inv_paid, 0) AS inv_owing,
        
			DATE_FORMAT(`date`,'%Y-%m-%e') AS `date`,
			(SELECT DATEDIFF(NOW(),`date`)) AS age,
			(CASE WHEN DATEDIFF(NOW(),`date`) <= 14 THEN '0-14'
				  WHEN DATEDIFF(NOW(),`date`) <= 30 THEN '15-30'
				  WHEN DATEDIFF(NOW(),`date`) <= 60 THEN '31-60'
				  WHEN DATEDIFF(NOW(),`date`) <= 90 THEN '61-90'
				  ELSE '90+'
			END ) AS Aging
        
		FROM
            ".TB_PREFIX."invoices iv
            LEFT JOIN ".TB_PREFIX."invoice_items ii ON (ii.invoice_id    = iv.id      AND ii.domain_id = iv.domain_id)
            LEFT JOIN ".TB_PREFIX."biller b         ON (iv.biller_id     = b.id       AND  b.domain_id = iv.domain_id)
            LEFT JOIN ".TB_PREFIX."customers c      ON (iv.customer_id   = c.id       AND  c.domain_id = iv.domain_id)
            LEFT JOIN ".TB_PREFIX."preferences pr   ON (iv.preference_id = pr.pref_id AND pr.domain_id = iv.domain_id)
            LEFT JOIN (
				SELECT ac_inv_id, domain_id, SUM(COALESCE(ac_amount, 0)) AS inv_paid
					FROM ".TB_PREFIX."payment
					GROUP BY ac_inv_id, domain_id
			) ap ON (ap.ac_inv_id = iv.id AND ap.domain_id = iv.domain_id)
		WHERE
				pr.status    = 1
			AND iv.domain_id = :domain_id
		GROUP BY
			iv.id
		HAVING
			inv_owing > 0
		ORDER BY
			age DESC;
		";
        }
        
        $invoice_results = dbQuery($sql, ':domain_id', $auth_session->domain_id);
        
        $total_owed = 0;
        $periods = array();
        
        while($invoice = $invoice_results->fetch()) {
            $periods[$invoice['Aging']]['name'] = $invoice['Aging'];
        
            if (!array_key_exists('invoices', $periods[$invoice['Aging']])) {
                $periods[$invoice['Aging']]['invoices'] = array();
            }
        
            array_push($periods[$invoice['Aging']]['invoices'], $invoice);
        
            $periods[$invoice['Aging']]['sum_total'] += $invoice['inv_owing'];
        
            $total_owed += $invoice['inv_owing'];
        }
        
        $this->smarty->assign('data', $periods);
        $this->smarty->assign('total_owed', $total_owed);
        $this->smarty->assign('pageActive', 'report');
        $this->smarty->assign('active_tab', '#home');
    }

                    
    public function reportDebtorsByAmountAction()
    {
        global $auth_session;
        global $db_server;
        
        if ($db_server == 'pgsql') {
            $sql = "SELECT
                        iv.id,
                        iv.index_id AS index_id,
                        b.name AS biller,
                        c.name AS customer,
                        
                        coalesce(ii.total, 0) AS inv_total,
                        coalesce(ap.total, 0) AS inv_paid,
                        coalesce(ii.total, 0) - coalesce(ap.total, 0) AS inv_owing,
                        iv.date
                FROM
                    ".TB_PREFIX."invoices iv
                	INNER JOIN ".TB_PREFIX."customers c ON (c.id = iv.customer_id)
                	INNER JOIN ".TB_PREFIX."biller b ON (b.id = iv.biller_id)
                    LEFT JOIN (SELECT i.invoice_id, sum(i.total) AS total
                         FROM ".TB_PREFIX."invoice_items i GROUP BY i.invoice_id
                        ) ii ON (iv.id = ii.invoice_id)
                    LEFT JOIN (SELECT p.ac_inv_id, sum(p.ac_amount) AS total
                         FROM ".TB_PREFIX."payment p GROUP BY p.ac_inv_id
                        ) ap ON (iv.id = ap.ac_inv_id)
                ORDER BY
                        inv_owing DESC;
            ";
        } else {
            $sql = "SELECT
                  iv.id,
                  iv.index_id,
            	  pr.pref_inv_wording,
                  b.name AS biller,
                  c.name AS customer,
                  SUM(COALESCE(ii.total, 0)) AS inv_total,
                  COALESCE(ap.inv_paid, 0) AS inv_paid,
                  SUM(COALESCE(ii.total, 0)) - COALESCE(ap.inv_paid, 0) AS inv_owing,
                  `date`
            	FROM
                    ".TB_PREFIX."invoices iv
                    LEFT JOIN ".TB_PREFIX."invoice_items ii ON (ii.invoice_id = iv.id         AND ii.domain_id = iv.domain_id)
                    LEFT JOIN ".TB_PREFIX."preferences pr   ON (pr.pref_id = iv.preference_id AND pr.domain_id = iv.domain_id)
                    LEFT JOIN ".TB_PREFIX."biller b         ON (iv.biller_id =  b.id          AND  b.domain_id = iv.domain_id)
                    LEFT JOIN ".TB_PREFIX."customers c      ON (iv.customer_id =  c.id        AND  c.domain_id = iv.domain_id)
                    LEFT JOIN (
            	    SELECT ac_inv_id, domain_id, SUM(COALESCE(ac_amount, 0)) AS inv_paid
            			FROM ".TB_PREFIX."payment
            			GROUP BY ac_inv_id, domain_id
            	) ap ON (ap.ac_inv_id = iv.id AND ap.domain_id = iv.domain_id)
            	WHERE
            		    pr.status = 1
            		AND iv.domain_id = :domain_id
            	GROUP BY
            		iv.id
            	ORDER BY
                    inv_owing DESC;
            ";
        }
        
        $invoice_results = dbQuery($sql, ':domain_id', $auth_session->domain_id);
        
        $total_owed = 0;
        $invoices   = array();
        
        while($invoice = $invoice_results->fetch()) {
            $total_owed += $invoice['inv_owing'];
            array_push($invoices, $invoice);
        }
        
        $this->smarty->assign('data', $invoices);
        $this->smarty->assign('total_owed', $total_owed);
        $this->smarty->assign('pageActive', 'report');
        $this->smarty->assign('active_tab', '#home');
    }
    
    public function reportDebtorsOwingByCustomerAction()
    {
        global $auth_session;
        global $db_server;
        
        if ($db_server == 'pgsql') {
            $sql = "SELECT
                        c.id AS cid,
                        c.name AS customer,
                        sum(coalesce(ii.total, 0)) AS inv_total,
                        sum(coalesce(ap.ac_amount, 0)) AS inv_paid,
                        sum(coalesce(ii.total, 0)) -
                        sum(coalesce(ap.ac_amount, 0)) AS inv_owing
                        
                FROM
                        ".TB_PREFIX."customers c LEFT JOIN
                        ".TB_PREFIX."invoices iv ON (c.id = iv.customer_id) LEFT JOIN
                	(SELECT i.invoice_id, coalesce(sum(i.total), 0) AS total
                         FROM ".TB_PREFIX."invoice_items i GROUP BY i.invoice_id
                        ) ii ON (iv.id = ii.invoice_id) LEFT JOIN
                	(SELECT p.ac_inv_id, coalesce(sum(p.ac_amount), 0) AS ac_amount
                         FROM ".TB_PREFIX."payment p GROUP BY p.ac_inv_id
                        ) ap ON (iv.id = ap.ac_inv_id)
                GROUP BY
                        c.id, c.name
                ORDER BY
                        inv_owing DESC;
           ";
        } else {
            $sql = "
                SELECT
                        c.id AS cid
                      , c.name AS customer
                      , SUM(COALESCE(ii.total, 0)) AS inv_total
                      , COALESCE(ap.inv_paid, 0) AS inv_paid
                      , SUM(COALESCE(ii.total, 0)) - COALESCE(ap.inv_paid, 0) AS inv_owing
                FROM
                      ".TB_PREFIX."customers c
                      LEFT JOIN ".TB_PREFIX."invoices iv      ON (iv.customer_id = c.id AND iv.domain_id = c.domain_id)
                      LEFT JOIN ".TB_PREFIX."invoice_items ii ON (ii.invoice_id = iv.id AND ii.domain_id = iv.domain_id)
                      LEFT JOIN ".TB_PREFIX."preferences pr   ON (iv.preference_id = pr.pref_id AND pr.domain_id = iv.domain_id)
                      LEFT JOIN (
                  SELECT
                	  iv1.customer_id
                	, ap1.domain_id
                	, SUM(COALESCE(ap1.ac_amount, 0)) AS inv_paid
                  FROM  ".TB_PREFIX."payment ap1
                      LEFT JOIN ".TB_PREFIX."invoices iv1   ON (ap1.ac_inv_id = iv1.id AND ap1.domain_id = iv1.domain_id)
                      LEFT JOIN ".TB_PREFIX."preferences pr1 ON (pr1.pref_id = iv1.preference_id AND pr1.domain_id = iv1.domain_id)
                  WHERE
                      pr1.status = 1
                  GROUP BY iv1.customer_id, ap1.domain_id
                      ) ap ON (ap.customer_id = c.id AND ap.domain_id = c.domain_id)
                WHERE
                          pr.status   = 1
                      AND c.domain_id = :domain_id
                GROUP BY
                	c.id;
            ";
        }
        
        $customer_results = dbQuery($sql, ':domain_id', $auth_session->domain_id);
        
        $total_owed = 0;
        $customers = array();
        
        while($customer = $customer_results->fetch()) {
            $total_owed += $customer['inv_owing'];
            array_push($customers, $customer);
        }
        
        $this->smarty->assign('data', $customers);
        $this->smarty->assign('total_owed', $total_owed);
        $this->smarty->assign('pageActive', 'report');
        $this->smarty->assign('active_tab', '#home');
    }
    
    public function reportInvoiceProfitAction()
    {
        global $auth_session;
        
        $start_date = isset($_POST['start_date']) ? $_POST['start_date'] : date("Y-m-d", strtotime('01-'.date('m').'-'.date('Y').' 00:00:00')) ;
        $end_date   = isset($_POST['end_date'])   ? $_POST['end_date']   : date("Y-m-d", strtotime('-1 second',strtotime('+1 month',strtotime('01-'.date('m').'-'.date('Y').' 00:00:00')))); ;
        
        $invoice             = new \invoice();
        $invoice->start_date = $start_date;
        $invoice->end_date   = $end_date;
        $invoice->having     = "date_between";
        $invoice->having_and = "real";
        $invoice_all         = $invoice->select_all();
        $invoices            = $invoice_all->fetchAll();
        
        foreach($invoices as $k=>$v)
        {
            //get list of all products
            $sql                = "SELECT DISTINCT(product_id) FROM ".TB_PREFIX."invoice_items WHERE invoice_id = :id AND domain_id = :domain_id";
            $sth                = dbQuery($sql, ':id',$v['id'], ':domain_id', $auth_session->domain_id);
            $products           = $sth->fetchAll();
            $invoice_total_cost = "0";
        
            foreach($products as $pk=>$pv)
            {
                $quantity           = "";
                $cost               = "";
                $product_total_cost = "";
                $sqlp               = "SELECT SUM(quantity) FROM ".TB_PREFIX."invoice_items WHERE product_id = :product_id and invoice_id = :invoice_id AND domain_id = :domain_id";
                $sthp               = dbQuery($sqlp, ':product_id',$pv['product_id'], ':invoice_id',$v['id'], ':domain_id', $auth_session->domain_id);
                $quantity           = $sthp->fetchColumn();
                #$sqlc              = "select (SELECT sum(cost) / sum(quantity)) as avg_cost  from si_inventory where product_id = :product_id";
                $sqlc               = "select (SELECT (SUM(cost * quantity) / SUM(quantity))) AS avg_cost FROM ".TB_PREFIX."inventory WHERE product_id = :product_id AND domain_id = :domain_id";
                $sthp               = dbQuery($sqlc, ':product_id',$pv['product_id'], ':domain_id', $auth_session->domain_id);
                $cost               = $sthp->fetchColumn();
                $product_total_cost = $quantity * $cost;
                $invoice_total_cost = $invoice_total_cost + $product_total_cost;
            }
            $invoices[$k]['cost']         =  $invoice_total_cost;
            $invoices[$k]['profit']       =  $invoices[$k]['invoice_total'] - $invoices[$k]['cost'];
            $invoice_totals['sum_total']  = $invoice_totals['sum_total'] + $invoices[$k]['invoice_total']  ;
            $invoice_totals['sum_cost']   = $invoice_totals['sum_cost'] + $invoices[$k]['cost']  ;
            $invoice_totals['sum_profit'] = $invoice_totals['sum_profit'] + $invoices[$k]['profit']  ;
        }
        
        $this->smarty->assign('invoices', $invoices);
        $this->smarty->assign('invoice_totals', $invoice_totals);
        $this->smarty->assign('start_date', $start_date);
        $this->smarty->assign('end_date', $end_date);
        $this->smarty->assign('pageActive', 'report');
        $this->smarty->assign('active_tab', '#home');
    }
    
    public function reportProductsSoldByCustomerAction()
    {
        global $auth_session;
        
        $sql = "
            SELECT
        		  SUM(ii.quantity) AS sum_quantity
        		, c.name, p.description
        	FROM ".TB_PREFIX."customers c
        		INNER JOIN ".TB_PREFIX."invoices iv      ON (c.id  = iv.customer_id AND c.domain_id  = iv.domain_id)
                INNER JOIN ".TB_PREFIX."invoice_items ii ON (iv.id = ii.invoice_id  AND iv.domain_id = ii.domain_id)
                INNER JOIN ".TB_PREFIX."products p       ON (p.id  = ii.product_id  AND p.domain_id  = ii.domain_id)
                INNER JOIN ".TB_PREFIX."preferences pr   ON (pr.pref_id = iv.preference_id AND pr.domain_id = iv.domain_id)
            WHERE p.visible
        	    AND pr.status = 1
        	    AND c.domain_id = :domain_id
            GROUP BY p.description, c.name
            ORDER BY c.name";
        
        $product_result = dbQuery($sql, ':domain_id', $auth_session->domain_id);
        
        $customers      = array();
        
        while($product = $product_result->fetch()) {
            $p = array();
            $p['description'] = $product['description'];
            $p['sum_quantity'] = $product['sum_quantity'];
        
            $customers[$product['name']]['name'] = $product['name'];
        
            if (!array_key_exists('products', $customers[$product['name']])) {
                $customers[$product['name']]['products'] = array();
            }
        
            array_push($customers[$product['name']]['products'], $p);
        
            $customers[$product['name']]['total_quantity'] += $product['sum_quantity'];
        }
        
        $this->smarty->assign('data', $customers);
        $this->smarty->assign('pageActive', 'report');
        $this->smarty->assign('active_tab', '#home');
    }
    
    public function reportProductsSoldTotalAction()
    {
        global $auth_session;
        
        $sql = "
            SELECT
            	  p.description
            	, SUM(ii.quantity) AS sum_quantity
            FROM ".TB_PREFIX."invoice_items ii
            	INNER JOIN ".TB_PREFIX."invoices iv    ON (ii.invoice_id = iv.id AND iv.domain_id = ii.domain_id)
            	INNER JOIN ".TB_PREFIX."products p     ON (p.id = ii.product_id  AND p.domain_id = ii.domain_id)
            	INNER JOIN ".TB_PREFIX."preferences pr ON (pr.pref_id = iv.preference_id AND pr.domain_id = iv.domain_id)
            WHERE 	p.visible
                AND pr.status = 1
                AND p.domain_id = :domain_id
            GROUP BY
            	p.description
        ";
        
        $product_sales = dbQuery($sql, ':domain_id', $auth_session->domain_id);
        
        $total_quantity = 0;
        $products = array();
        
        while($product = $product_sales->fetch()) {
            $total_quantity += $product['sum_quantity'];
            array_push($products, $product);
        }
        
        $this->smarty->assign('data', $products);
        $this->smarty->assign('total_quantity', $total_quantity);
        $this->smarty->assign('pageActive', 'report');
        $this->smarty->assign('active_tab', '#home');
    }
    
    public function reportSalesByPeriodsAction()
    {
        global $auth_session;
        
        $max_years=10;
        
        // Get earliest invoice date
        $sql="SELECT MIN(iv.date) AS date
	  FROM ".TB_PREFIX."invoices iv
		INNER JOIN ".TB_PREFIX."preferences pr ON (pr.pref_id = iv.preference_id AND pr.domain_id = iv.domain_id)
	  WHERE pr.status = 1
		AND iv.domain_id = :domain_id";
        $sth = dbQuery($sql, ':domain_id', $auth_session->domain_id);
        $invoice_start_array = $sth->fetch();
        $first_invoice_year = date('Y', strtotime( $invoice_start_array['date'] ) );
        
        //Get total for each month of each year from first invoice
        $this_year= date('Y');
        $year = $first_invoice_year ;
        
        $total_years= $this_year - $first_invoice_year +1;
        if( $total_years > $max_years){
            $year= $this_year - $max_years +1;
        }
        
        function _myRate($this_year_amount, $last_year_amount,$precision=2){
            if(!$last_year_amount){return '';}
            $rate=round( ($this_year_amount - $last_year_amount) / $last_year_amount * 100 , $precision);
            return $rate;
        }
        
        //loop for each year
        
        $years=array();
        $data=array();
        
        while ( $year <= $this_year ){
        
            // loop for each month
            $month = 1;
        
            while ($month <= 12){
                //make month nice for mysql - accounts table doesnt like it if not 08 etc..
                if ( $month < 10 ){
                    $month="0".$month;
                };
        
                // Monthly Sales ----------------------------
                $total_month_sales_sql = "
			SELECT SUM(ii.total) AS month_total
				FROM ".TB_PREFIX."invoice_items ii
					INNER JOIN ".TB_PREFIX."invoices iv ON (ii.invoice_id = iv.id AND iv.domain_id = ii.domain_id)
					INNER JOIN ".TB_PREFIX."preferences pr ON (pr.pref_id = iv.preference_id AND pr.domain_id = iv.domain_id)
        					WHERE
        					pr.status = '1'
        					AND ii.domain_id = :domain_id
        					AND iv.date LIKE '$year-$month%'
        					";
                $total_month_sales = dbQuery($total_month_sales_sql, ':domain_id', $auth_session->domain_id);
                $total_month_sales_array = $total_month_sales -> fetch();
        
                $data['sales']['months'][$month][$year] = $total_month_sales_array['month_total'];
                $data['sales']['months_rate'][$month][$year] = _myRate($data['sales']['months'][$month][$year],	$data['sales']['months'][$month][$year -1]);
        
        
                // Monthly Payment ----------------------------
                $total_month_payments_sql = "SELECT SUM(ac_amount) AS month_total_payments FROM ".TB_PREFIX."payment WHERE domain_id = :domain_id AND ac_date LIKE '$year-$month%'";
                $total_month_payments = dbQuery($total_month_payments_sql, ':domain_id', $auth_session->domain_id);
                $total_month_payments_array = $total_month_payments -> fetch();
        
                $data['payments']['months'][$month][$year] 		= $total_month_payments_array['month_total_payments'];
                $data['payments']['months_rate'][$month][$year] = _myRate($data['payments']['months'][$month][$year],	$data['payments']['months'][$month][$year -1]);
        
                $month++;
            }
        
            // Total Annual Sales ----------------------------
            $total_year_sales_sql = "
		SELECT SUM(ii.total) AS year_total
			FROM ".TB_PREFIX."invoice_items ii
				INNER JOIN ".TB_PREFIX."invoices iv ON (ii.invoice_id = iv.id AND iv.domain_id = ii.domain_id)
				INNER JOIN ".TB_PREFIX."preferences pr ON (pr.pref_id = iv.preference_id AND pr.domain_id = iv.domain_id)
        				WHERE
        				pr.status = '1'
        				AND ii.domain_id = :domain_id
        				AND iv.date LIKE '$year%'
        				";
            $total_year_sales                      = dbQuery($total_year_sales_sql, ':domain_id', $auth_session->domain_id);
            $total_year_sales_array                = $total_year_sales -> fetch();
            $data['sales']['total'][$year]         = $total_year_sales_array['year_total'];
            $data['sales']['total_rate'][$year]    = _myRate($data['sales']['total'][$year],	$data['sales']['total'][$year -1]);
        
            // Total Annual Payment ----------------------------
            $total_year_payments_sql               = "SELECT SUM(ac_amount) AS year_total_payments FROM ".TB_PREFIX."payment WHERE domain_id = :domain_id AND ac_date LIKE '$year%'";
            $total_year_payments                   = dbQuery($total_year_payments_sql, ':domain_id', $auth_session->domain_id);
            $total_year_payments_array             = $total_year_payments -> fetch();
            $data['payments']['total'][$year]      = $total_year_payments_array['year_total_payments'];
            $data['payments']['total_rate'][$year] = _myRate($data['payments']['total'][$year],	$data['payments']['total'][$year -1]);
        
            $years[]=$year ;
            $year++;
        }
        
        $years=array_reverse($years);
        
        $this->smarty->assign('data',		$data);
        $this->smarty->assign('all_years',$years);
        $this->smarty->assign('pageActive', 'report');
        $this->smarty->assign('active_tab', '#home');
    }
    
    public function reportSalesCustomersTotalAction()
    {
        global $auth_session;
        global $dbh;
        
        $sql = "SELECT c.name, SUM(ii.total) AS sum_total
            FROM
                ".TB_PREFIX."customers c
        		INNER JOIN ".TB_PREFIX."invoices iv ON (c.id = iv.customer_id AND c.domain_id = iv.domain_id)
                INNER JOIN ".TB_PREFIX."invoice_items ii ON (iv.id = ii.invoice_id AND iv.domain_id = ii.domain_id)
                INNER JOIN ".TB_PREFIX."preferences pr ON (pr.pref_id = iv.preference_id AND pr.domain_id = iv.domain_id)
            WHERE
                   pr.status = '1'
               AND c.domain_id = :domain_id
            GROUP BY c.name;";
        
        $customer_sales = dbQuery($sql, ':domain_id', $auth_session->domain_id) or die(htmlsafe(end($dbh->errorInfo())));
        
        $total_sales = 0;
        $customers = array();
        
        while($customer = $customer_sales->fetch()) {
            $total_sales += $customer['sum_total'];
            array_push($customers, $customer);
        }
        
        $this->smarty->assign('data', $customers);
        $this->smarty->assign('total_sales', $total_sales);
        $this->smarty->assign('pageActive', 'report');
        $this->smarty->assign('active_tab', '#home');
    }
    
    public function reportSalesTotalAction()
    {
        global $auth_session;
        
        $sql = "SELECT
    			  pr.index_group AS `group`
    			, GROUP_CONCAT(DISTINCT pr.pref_description SEPARATOR ',') AS template
    			, COUNT(DISTINCT ii.invoice_id) AS `count`
    			, SUM(ii.total) AS sum_total
            FROM
                ".TB_PREFIX."invoice_items ii
        		INNER JOIN ".TB_PREFIX."invoices iv ON (iv.id = ii.invoice_id AND iv.domain_id = ii.domain_id)
                INNER JOIN ".TB_PREFIX."preferences pr ON (pr.pref_id = iv.preference_id AND pr.domain_id = iv.domain_id)
            WHERE
                   pr.status = '1'
               AND ii.domain_id = :domain_id
        	GROUP BY
        		pr.index_group
        ";
        
        $sth = dbQuery($sql, ':domain_id', $auth_session->domain_id);
        
        $grand_total_sales = 0;
        $total_sales       = array();
        
        while($sales = $sth->fetch()) {
            $grand_total_sales += $sales['sum_total'];
            array_push($total_sales, $sales);
        }
        
        //    $smarty->assign('total_sales', $sth->fetchColumn());
        $this->smarty->assign('data', $total_sales);
        $this->smarty->assign('grand_total_sales', $grand_total_sales);
        $this->smarty->assign('pageActive', 'report_sale');
        $this->smarty->assign('active_tab', '#money');
    }
    
    public function reportTaxTotalAction()
    {
        global $auth_session;
        
        $sql = "
            SELECT SUM(ii.tax_amount) AS sum_tax_total
            FROM ".TB_PREFIX."invoice_items ii
            	INNER JOIN ".TB_PREFIX."invoices iv ON (ii.invoice_id = iv.id AND ii.domain_id = iv.domain_id)
            	INNER JOIN ".TB_PREFIX."preferences pr ON (pr.pref_id = iv.preference_id AND pr.domain_id = iv.domain_id)
            WHERE pr.status = 1 AND ii.domain_id = :domain_id
        ";
        
        $sth = dbQuery($sql, ':domain_id', $auth_session->domain_id);
        
        $this->smarty->assign('total_taxes', $sth->fetchColumn());
        $this->smarty->assign('pageActive', 'report');
        $this->smarty->assign('active_tab', '#home');
    }
}