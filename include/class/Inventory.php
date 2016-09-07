<?php
/**
 * Class Inventory
 */
class Inventory {

    public static function count() {
        global $pdoDb;
        $pdoDb->addSimpleWhere("domain_id", domain_id::get());
        $pdoDb->addToFunctions("COUNT(*) AS count");
        $rows = $pdoDb->request("SELECT", "inventory");
        return $rows[0]['count'];
    }

    public static function select() {
        global $pdoDb;
        $join = new Join("LEFT", "inventory", "iv");
        $join->addSimpleItem("p.id", new DbField("iv.product_id"), "AND");
        $join->addSimpleItem("p.domain_id", new DbField("iv.domain_id"));
        $pdoDb->addToJoins($join);

        $pdoDb->addSimpleWhere("iv.domain_id", domain_id::get(), "AND");
        $pdoDb->addSimpleWhere("iv.id", $_GET['id']);

        $pdoDb->setSelectList(array("iv.*", "p.description"));
        $result = $pdoDb->request("SELECT", "products", "p");
        return $result[0];
    }

    public static function xml_select($type, $sort, $dir, $rp, $page) {
        global $pdoDb;

        $query = isset ( $_POST ['query'] ) ? $_POST ['query'] : null;
        $qtype = isset ( $_POST ['qtype'] ) ? $_POST ['qtype'] : null;
        if (!empty($qtype) && !empty($query)) {
            if (in_array($qtype, array ('p.description', 'iv.date', 'iv.quantity', 'iv.cost', 'total_cost'))) {
                $pdoDb->addToWhere(new WhereItem(false, $qtype, "LIKE", "%$query%", false, "AND"));
            }
        }
        $pdoDb->addSimpleWhere("inv.domain_id", domain_id::get());

        $oc = new OnClause();
        $oc->addSimpleItem("p.id", new DbField("inv.product_id"), "AND");
        $oc->addSimpleItem("p.domain_id", new DbField("inv.domain_id"));
        $pdoDb->addToJoins(array("LEFT", "inventory", "inv", $oc));

        if ($type == "count") {
            $pdoDb->addToFunctions("COUNT(*) AS count");
            $rows = $pdoDb->request("SELECT", "products", "p");
            return $rows[0]['count'];
        }

        if (empty($sort)) {
            $pdoDb->setOrderBy("p.id");
        } else {
            $pdoDb->setOrderBy($sort);
        }

        $start = (($page - 1) * $rp);
        $pdoDb->setLimit($rp, $start);

        $pdoDb->addToFunctions("coalesce(p.reorder_level,0) AS reorder_level");
        $pdoDb->addToFunctions("coalesce(inv.quantity * inv.cost,0) AS total_cost");
        $list = array("inv.id as id", "inv.product_id", "inv.date", "inv.quantity", "p.description", "inv.cost");
        $pdoDb->setSelectList($list);
        $pdoDb->setGroupBy("inv.id");

        $result = $pdoDb->request("SELECT", "products", "p");
        return $result;
    }

    public static function insert() {
        global $pdoDb;
        $pdoDb->setExcludedFields(array("id" => 1));
        $result = $pdoDb->request("INSERT", "inventory");
        return $result;
    }

    public static function update() {
        global $pdoDb;
        $pdoDb->setExcludedFields(array("id" => 1, "domain_id" => 1));
        $pdoDb->addSimpleWhere("id", $_GET['id'], "AND");
        $pdoDb->addSimpleWhere("domain_id", domain_id::get());
        $result = $pdoDb->request("UPDATE", "inventory");
        return $result;
    }

    public static function delete() {
        throw new Exception("inventory.php delete(): delete not supported.");
    }

    public static function check_reorder_level() {
        $rows = Product::xml_select('count',"","","","");
        $email = "";
        $result = array();
        $email_message = "";
        foreach ( $rows as $row ) {
            if ($row['quantity'] <= $row['reorder_level']) {
                $message = "The quantity of Product: $row[description] is " .
                           siLocal::number($row['quantity']) .
                           ", which is equal to or below its reorder level of $row[reorder_level]";
                $result['row_$row[id]']['message'] = $message;
                $email_message .= $message . "<br />\n";
            }
        }

        $email = new email ();
        $email->notes   = $email_message;
        $email->from    = $email->get_admin_email ();
        $email->to      = $email->get_admin_email ();
        $email->subject = "SimpleInvoices reorder level email";
        $email->send ();

        return $result;
    }
}
