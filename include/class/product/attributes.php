<?php

class product_attributes
{
    public function get($id)
    {
        $domain_id = domain_id::get();
        $sql = "SELECT pa.*, pat.name AS type
                FROM ".TB_PREFIX."products_attributes pa
                    LEFT JOIN ".TB_PREFIX."products_attribute_type pat
                        ON (pa.type_id = pat.id)
                WHERE pa.id = :id
                AND pa.domain_id = :domain_id";

		$sth = dbQuery($sql, ':id', $id, ':domain_id', $domain_id);
        $attribute = $sth->fetch();

        return $attribute;
    }

    public function getName($id)
    {
        $domain_id = domain_id::get();
        $sql = "SELECT * FROM ".TB_PREFIX."products_attributes WHERE id = :id AND domain_id = :domain_id";
        $sth = dbQuery($sql, ':id', $id, ':domain_id', $domain_id);
        $attribute = $sth->fetch();
        return $attribute['name'];
    }

    public function getType($id)
    {
        $attribute = product_attributes::get($id);
        return $attribute['type'];
    }

    public function getValue($attribute_id, $value_id)
    {
        $type = product_attributes::getType($attribute_id);

        if($type == 'list')
        {
            $domain_id = domain_id::get();
            $sql = "SELECT value FROM ".TB_PREFIX."products_values WHERE id = :id AND domain_id = :domain_id";
            $sth = dbQuery($sql, ':id', $value_id, ':domain_id', $domain_id);
            $attribute = $sth->fetch();

            return $attribute['value'];
        } else {
            return $value_id;
        }

    }

	public function getVisible($id)
    {
        $domain_id = domain_id::get();
        $sql = "SELECT visible FROM ".TB_PREFIX."products_attributes WHERE id = :id AND domain_id = :domain_id";
        $sth = dbQuery($sql, ':id', $id, ':domain_id', $domain_id);
        $attribute = $sth->fetch();
        if($attribute['visible'] =='1')
        {
             return true;
        } else {
            return false;
        }

    }

	public function getAll()
    {
        $domain_id = domain_id::get();
        $sql = "SELECT * FROM ".TB_PREFIX."products_attributes WHERE domain_id = :domain_id";
        $sth = dbQuery($sql, ':domain_id', $domain_id);
        $attributes = $sth->fetchAll();
        return $attributes;
    }
}
