<?php

class product_attributes
{
    public function get($id)
    {
        $sql = "select * from ".TB_PREFIX."products_attributes where id = :id";
        $sth =  dbQuery($sql,':id',$id);
        $attribute = $sth->fetch();

        $sql2 = "select * from ".TB_PREFIX."products_attribute_type where id = :id";
        $sth2 =  dbQuery($sql2,':id',$attribute['type_id']);
        $name = $sth2->fetch();
        $attribute['type'] = $name['name'];

        return $attribute;
    }
    public function getName($id)
    {
        $sql = "select * from ".TB_PREFIX."products_attributes where id = :id";
        $sth =  dbQuery($sql,':id',$id);
        $attribute = $sth->fetch();
        return $attribute['name'];
    }
    public function getValue($attribute_id, $value_id)
    {
       
        $details = product_attributes::get($attribute_id);

        if($details['type'] == 'list')
        {
            $sql = "select value from ".TB_PREFIX."products_values where id = :id";
            $sth =  dbQuery($sql,':id',$value_id);
            $attribute = $sth->fetch();

            return $attribute['value'];
        } else {
            return $value_id;
        }

    }
    public function getVisible($id)
    {
        $sql = "select * from ".TB_PREFIX."products_attributes where id = :id";
        $sth =  dbQuery($sql,':id',$id);
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
        $sql = "select * from ".TB_PREFIX."products_attributes";
        $sth =  dbQuery($sql);
        $attributes = $sth->fetchAll();
        return $attributes;
    }
}
