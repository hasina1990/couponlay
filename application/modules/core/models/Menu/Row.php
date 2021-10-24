<?php
class Core_Model_Menu_Row extends Core_Model_Table_Row_Abstract
{
	protected $_tableClass = 'Core_Model_Menu';
    
    public function isDuplicateRecord()
    {
        $select = $this->select();    
        if($this->label)
        {
            $select->where('label = ?', $this->label);
        }
                                   
        if($this->id)
        {
            $select->where('id != ?', $this->id);
        }
        
        $select->where('is_footer_link = ?', $this->is_footer_link);
        
        return $this->getTable()->fetchRow($select);
    }
}