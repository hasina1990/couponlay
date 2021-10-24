<?php
/**
*	Cache_Model_Cache_Row
**/
class Cron_Model_Cron_Row extends Core_Model_Table_Row_Abstract
{
	/**
    *	$_tableClass protected Variable. It contains table class name for Cache row class.
    *	@var mixed.
    **/
	protected $_tableClass = 'Cron_Model_Cron';
    protected $_validationClassModel = 'cron/cron_row_validation';
    
    public function _beforeSave()
    {
        Ccc::getModel('cache/cache')->clearCache(Cron_Model_Cron::CACHE_CODE);
        return parent::_beforeSave();
    }
     
    public function delete()
    {
         Ccc::getModel('cache/cache')->clearCache(Cron_Model_Cron::CACHE_CODE);
         return parent::delete();
    }
}
