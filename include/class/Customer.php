<?php

class Customer {
    public static function get($id) {
        global $pdoDb;

        $pdoDb->addSimpleWhere("domain_id", domain_id::get(), "AND");
        $pdoDb->addSimpleWhere("id", $id);
        return $pdoDb->request("SELECT", "customers");
    }

    public static function get_all($enabled_only = false) {
        global $LANG, $pdoDb;

        if ($enabled_only) {
            $pdoDb->addSimpleWhere("enabled", ENABLED, "AND");
        }
        $pdoDb->addSimpleWhere("domain_id", domain_id::get());
        $pdoDb->setOrderBy("name");
        $rows = $pdoDb->request("SELECT", "customers");

        $customers = array();
        foreach($rows as $customer) {
            $customer['enabled'] = ($customer['enabled'] == ENABLED ? $LANG['enabled'] : $LANG['disabled']);
            $customer['total']   = calc_customer_total($customer['id']);
            $customer['paid']    = calc_customer_paid($customer['id']);
            $customer['owing']   = $customer['total'] - $customer['paid'];
            $customers[]         = $customer;
        }

        return $customers;
    }

    public static function insert() {
        global $pdoDb;

        // @formatter:off
        $pdoDb->setExcludedFields(array("id" => 1));
        $fauxPost = array('attention'       => $this->attention,
                          'name'            => $this->name,
                          'street_address'  => $this->street_address,
                          'street_address2' => $this->street_address2,
                          'city'            => $this->city,
                          'state'           => $this->state,
                          'zip_code'        => $this->zip_code,
                          'country'         => $this->country,
                          'phone'           => $this->phone,
                          'mobile_phone'    => $this->mobile_phone,
                          'fax'             => $this->fax,
                          'email'           => $this->email,
                          'notes'           => $this->notes,
                          'custom_field1'   => $this->custom_field1,
                          'custom_field2'   => $this->custom_field2,
                          'custom_field3'   => $this->custom_field3,
                          'custom_field4'   => $this->custom_field4,
                          'enabled'         => $this->enabled,
                          'domain_id'       => domain_id::get());
        // @formatter:on
        $pdoDb->setFauxPost($fauxPost);
        return $pdoDb->request("INSERT", "customers");
    }
}
