<?php

class sub_customer
{
    public static function insertCustomer() {
        global $db_server;
        global $auth_session;
        global $config;
        extract( $_POST );
        $sql = "INSERT INTO 
            ".TB_PREFIX."customers
            (
             domain_id, attention, name, street_address, street_address2,
             city, state, zip_code, country, phone, mobile_phone,
             fax, email, notes,
             credit_card_holder_name, credit_card_number,
             credit_card_expiry_month, credit_card_expiry_year,
             parent_customer_id,
             custom_field1, custom_field2,
             custom_field3, custom_field4, enabled
            )
            VALUES 
            (
             :domain_id ,:attention, :name, :street_address, :street_address2,
             :city, :state, :zip_code, :country, :phone, :mobile_phone,
             :fax, :email, :notes, 
             :credit_card_holder_name, :credit_card_number,
             :credit_card_expiry_month, :credit_card_expiry_year, 
             :parent_customer_id,
             :custom_field1, :custom_field2,
             :custom_field3, :custom_field4, :enabled
            )";
        //cc
        $enc = new encryption();
        $key = $config->encryption->default->key;	
        $encrypted_credit_card_number = $enc->encrypt($key, $credit_card_number);

        return dbQuery($sql,
                ':attention', $attention,
                ':name', $name,
                ':street_address', $street_address,
                ':street_address2', $street_address2,
                ':city', $city,
                ':state', $state,
                ':zip_code', $zip_code,
                ':country', $country,
                ':phone', $phone,
                ':mobile_phone', $mobile_phone,
                ':fax', $fax,
                ':email', $email,
                ':notes', $notes,
                ':credit_card_holder_name', $credit_card_holder_name,
                ':credit_card_number', $encrypted_credit_card_number,
                ':credit_card_expiry_month', $credit_card_expiry_month,
                ':credit_card_expiry_year', $credit_card_expiry_year,
                ':parent_customer_id', $parent_customer_id,
                ':custom_field1', $custom_field1,
                ':custom_field2', $custom_field2,
                ':custom_field3', $custom_field3,
                ':custom_field4', $custom_field4,
                ':enabled', $enabled,
                ':domain_id',$auth_session->domain_id
                );
    }

    public static function updateCustomer() {
        global $db;
        global $config;

		// $encrypted_credit_card_number = '';
		$is_new_cc_num = ($_POST['credit_card_number_new'] !='');

            $sql = "UPDATE 
                   ".TB_PREFIX."customers 
                   SET 
                     name = :name,
                     attention = :attention,
                     street_address = :street_address,
                     street_address2 = :street_address2,
                     city = :city,
                     state = :state,
                     zip_code = :zip_code,
                     country = :country,
                     phone = :phone,
                     mobile_phone = :mobile_phone,
                     fax = :fax,
                     email = :email,
                     credit_card_holder_name = :credit_card_holder_name,
                   " . (($is_new_cc_num) ? 'credit_card_number = :credit_card_number,' : '') . "
                     credit_card_expiry_month = :credit_card_expiry_month,
                     credit_card_expiry_year = :credit_card_expiry_year,
                     notes = :notes,
                     parent_customer_id = :parent_customer_id,
                     custom_field1 = :custom_field1,
                     custom_field2 = :custom_field2,
                     custom_field3 = :custom_field3,
                     custom_field4 = :custom_field4,
                     enabled = :enabled
                   WHERE
                     id = :id";

        if($is_new_cc_num)
        {
            $credit_card_number = $_POST['credit_card_number_new'];
            //cc
            $enc = new encryption();
            $key = $config->encryption->default->key;	
            $encrypted_credit_card_number = $enc->encrypt($key, $credit_card_number);

            return $db->query($sql,
                    ':name', $_POST[name],
                    ':attention', $_POST[attention],
                    ':street_address', $_POST[street_address],
                    ':street_address2', $_POST[street_address2],
                    ':city', $_POST[city],
                    ':state', $_POST[state],
                    ':zip_code', $_POST[zip_code],
                    ':country', $_POST[country],
                    ':phone', $_POST[phone],
                    ':mobile_phone', $_POST[mobile_phone],
                    ':fax', $_POST[fax],
                    ':email', $_POST[email],
                    ':notes', $_POST[notes],
                    ':credit_card_number', $encrypted_credit_card_number,
                    ':credit_card_holder_name', $_POST[credit_card_holder_name],
                    ':credit_card_expiry_month', $_POST[credit_card_expiry_month],
                    ':credit_card_expiry_year', $_POST[credit_card_expiry_year],
                    ':parent_customer_id', $_POST['parent_customer_id'],
                    ':custom_field1', $_POST[custom_field1],
                    ':custom_field2', $_POST[custom_field2],
                    ':custom_field3', $_POST[custom_field3],
                    ':custom_field4', $_POST[custom_field4],
                    ':enabled', $_POST['enabled'],
                    ':id', $_GET['id']
                    );
        } else {
            return $db->query($sql,
                    ':name', $_POST[name],
                    ':attention', $_POST[attention],
                    ':street_address', $_POST[street_address],
                    ':street_address2', $_POST[street_address2],
                    ':city', $_POST[city],
                    ':state', $_POST[state],
                    ':zip_code', $_POST[zip_code],
                    ':country', $_POST[country],
                    ':phone', $_POST[phone],
                    ':mobile_phone', $_POST[mobile_phone],
                    ':fax', $_POST[fax],
                    ':email', $_POST[email],
                    ':notes', $_POST[notes],
                    ':credit_card_holder_name', $_POST[credit_card_holder_name],
                    ':credit_card_expiry_month', $_POST[credit_card_expiry_month],
                    ':credit_card_expiry_year', $_POST[credit_card_expiry_year],
                    ':parent_customer_id', $_POST['parent_customer_id'],
                    ':custom_field1', $_POST[custom_field1],
                    ':custom_field2', $_POST[custom_field2],
                    ':custom_field3', $_POST[custom_field3],
                    ':custom_field4', $_POST[custom_field4],
                    ':enabled', $_POST['enabled'],
                    ':id', $_GET['id']
                    );
        }
    }
}
