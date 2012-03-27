<?php
class SimpleInvoices_Db_Table_Index extends SimpleInvoices_Db_Table_Abstract
{
    protected $_name = "index";
    
    /**
    * Insert method
    * Makes sure the domain_id gets inserted
    * 
    * @param mixed $data
    * @return array
    */
    public function insert(array $data)
    {
        $auth_session = Zend_Registry::get('auth_session');
        
        $data['domain_id'] = $auth_session->domain_id;
        
        return parent::insert($data);
    }
    
    public function incrementIndex($node,$sub_node = NULL)
    {
        $data = array('id' => $this->NEXT($node, $sub_node));
        
        if ($data['id'] == 1) {
            $data['node'] = $node;
            if (!is_null($sub_node) && !empty($sub_node)) {
                $data['sub_node'] = $sub_node;
            }
            
            if ($this->insert($data)) return $data['id'];
            throw new ErrorException('Unable to insert value in index table', 0, 1);
        }  else {
            $auth_session = Zend_Registry::get('auth_session');
            
            $where = array();
            $where[] = $this->getAdapter()->quoteInto('domain_id = ?', $auth_session->domain_id);
            $where[] = $this->getAdapter()->quoteInto('node = ?', $node);
            if (!is_null($sub_node) && !empty($sub_node)) {
                $where[] = $this->getAdapter()->quoteInto('sub_node = ?', $sub_node); 
            }
            
            if (parent::update($data, $where)) return $data['id'];
            throw new ErrorException('Unable to update index table', 0, 1);
        }
    }
    
    
    public function getNext($node, $sub_node = NULL)
    {
        $auth_session = Zend_Registry::get('auth_session');
        
        $select = $this->select();
        $select->from($this->_name, array('id'));
        $select->where('domain_id = ?', $auth_session->domain_id);
        $select->where('node = ?', $node);
        if (!is_null($sub_node) && !empty($sub_node)) {
            $select->where('sub_node = ?', $sub_node);
        }
        
        $row =  $this->getAdapter()->fetchRow($select);
        
        if (!$row) {
            return 1;
        } elseif (is_numeric($row['id'])) {
            return (((int)$row['id']) + 1);
        } else {
            return 1;
        }
    }
    
    
    public static function INCREMENT($node, $sub_node = NULL)
    {
        $table = new self();
        return $table->incrementIndex($node, $sub_node);
    }
    
    public static function NEXT($node, $sub_node = NULL)
    {
        $table = new self();
        return $table->getNext($node, $sub_node);
    }
}
?>
