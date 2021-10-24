<?php
/**
*	Cache_Model_Cache_Row
**/
class Cache_Model_Cache_Row extends Core_Model_Table_Row_Abstract
{
	/**
    *	$_tableClass protected Variable. It contains table class name for Cache row class.
    *	@var mixed.
    **/
	protected $_tableClass = 'Cache_Model_Cache';
    protected $_validationClassModel = 'cache/cache_row_validation';
    
    public function isDuplicateRecord()
    {
        $select = $this->select();    
        if($this->code)
        {
            $select->where('code = ?', $this->code);
        }
        
        if($this->cache_id)
        {
            $select->where('cache_id != ?', $this->cache_id);
        }
        
        return $this->getTable()->fetchRow($select);
    }
    
    public function _beforeSave()
    {
        Ccc::getModel('cache/cache')->clearCache(Cache_Model_Cache::CACHE_CODE);
        return parent::_beforeSave();
    }
}
