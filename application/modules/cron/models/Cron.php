<?php
/**
*	Cache_Model_Cache
**/
class Cron_Model_Cron extends Core_Model_Table_Abstract
{
	/**
    *	$_rowClass protected Variable. It contains row class name for Cache class.
    *	@var mixed.
    **/
    protected $_name = 'cron';
    protected $_primary   = 'cron_id';
    protected $_rowClass = 'Cron_Model_Cron_Row';
	protected $_rowsetClass = 'Cron_Model_Cron_Rowset';
    
    const CACHE_CODE = 'cron'; 
    
    public function getCollectionByCronID()
    {
        $collection = $this->getCollection();
        
        $result = array();
        
        if($collection)
        {
           foreach($collection as $cronJob)
            {
                $result[$cronJob->cron_id] = $cronJob->toArray();
            } 
        }
        
        return $result;
    }
    
    public function getCollection()
    {
        $cacheModel = Ccc::getModel('cache/cache');
        $cache = $cacheModel->getCacheByCode(self::CACHE_CODE);
        
        if($cache)
        {
            $frontend = array(                
                'lifetime'    => $cache->lifetime,
                'cached_entity'    => $this,
                'cache_id_prefix' => $cache->prefix
            );
            
            $backend = array(
                'file_name_prefix'    =>    $cache->prefix
            );            
            $current = $cacheModel->getInstance($frontend, $backend);
            $current->setTagsArray(array($cache->tag));

            return $current->getCollectionCache();
        }
        else
        {
            return $this->getCollectionCache();
        }
    }
    
    public function getCollectionCache()
    {
        $select = $this->select()
                        ->where("status = ?", Cron_Model_Cron_Status::STATUS_ENABLED_YES); 
                        
        return $this->fetchAll($select);
    }
}