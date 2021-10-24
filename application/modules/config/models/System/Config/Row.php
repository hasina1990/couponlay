<?php
class Config_Model_System_Config_Row extends Core_Model_Table_Row_Abstract
{
	protected $_tableClass = 'Config_Model_System_Config';
    protected $_validationClassModel = 'config/system_config_row_validation';

    public function isDuplicateRecord()
    {
        $select = $this->select();
        
        if($this->access_key)
        {
            $select->where('access_key = ?', $this->access_key);
        }
        
        if($this->entity_id)
        {
            $select->where('entity_id != ?', $this->entity_id);
        }
        
        return $this->getTable()->fetchRow($select);
    }
    
    public function _beforeSave()
    {
        Ccc::getModel('cache/cache')->clearCache(Config_Model_System_Config::CACHE_CODE);
        return parent::_beforeSave();
    }
    
    public function delete()
    {
         Ccc::getModel('cache/cache')->clearCache(Config_Model_System_Config::CACHE_CODE);
         return parent::delete();
    }
}