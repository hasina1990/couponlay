<?php
class Block_Model_Block_Row extends Core_Model_Table_Row_Abstract
{
	protected $_tableClass = 'Block_Model_Block';
    protected $_validationClassModel = 'block/block_row_validation';
    
    public function isDuplicateRecord()
    {
        $select = $this->select();    
                   
        if($this->name)
        {
            $select->where('name = ?', $this->name);
        }
        
        if($this->block_id)
        {
            $select->where('block_id != ?', $this->block_id);
        }
        
        return $this->getTable()->fetchRow($select);
    }
}