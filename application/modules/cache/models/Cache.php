<?php
/**
*	Cache_Model_Cache
**/
class Cache_Model_Cache extends Core_Model_Table_Abstract
{
	/**
    *	$_rowClass protected Variable. It contains row class name for Cache class.
    *	@var mixed.
    **/
    protected $_name = 'cache';
    protected $_primary   = 'cache_id';
    protected $_rowClass = 'Cache_Model_Cache_Row';
	protected $_rowsetClass = 'Cache_Model_Cache_Rowset';
    
    protected $_cache = null;
    protected $_frontendOptions = array('lifetime'=> 1296000, 'automatic_serialization'=>false, 'cache_id_prefix'=>null,'cached_entity'=> null);
    protected $_backendOptions = array('cache_dir' => 'data/cache', 'file_name_prefix' => null);
    
    
    /**
    *    cache code
    **/
    const CACHE_CODE = 'cache';

    /**
    *    __construct magic method.
    *    return void.
    **/    
    public function __construct()
    {
        parent::__construct();
        
        $this->_frontendOptions['cache_id_prefix'] = self::CACHE_CODE;
        $this->_backendOptions['file_name_prefix'] = self::CACHE_CODE;
        $this->_backendOptions['cache_dir'] = Ccc::getBaseDir(DIRECTORY_SEPARATOR.'data'.DIRECTORY_SEPARATOR.'cache');
    }
    
    /**
    *    getFrontEndOptions Function. return the catch options for frontend varibale.
    *    @return frontendoptions.
    **/
    public function getFrontEndOptions()
    {
        return $this->_frontendOptions;
    }
    
    /**
    *    setFrontEndOptions Function. set the catch options for frontend varibale.
    *    @param mixed $options.
    *    @return frontendoptions
    **/
    public function setFrontEndOptions($options)
    {
        if(isset($options['lifetime']))
        {
            if((int)$options['lifetime'] > 0)
            {
                $this->_frontendOptions['lifetime'] = (int)$options['lifetime'];
            }
        }
        
        if(isset($options['automatic_serialization']))
        {
            $options['automatic_serialization'] = (string)trim($options['automatic_serialization']);
            if($options['automatic_serialization'])
            {
                $this->_frontendOptions['automatic_serialization'] = $options['automatic_serialization'];
            }
        }
        
        if(isset($options['cache_id_prefix']))
        {
            $options['cache_id_prefix'] = (string)trim($options['cache_id_prefix']);
            if($options['cache_id_prefix'])
            {
                $this->_frontendOptions['cache_id_prefix'] = $options['cache_id_prefix'];
            }
        }
        
        if(isset($options['cached_entity']))
        {
            if($options['cached_entity'])
            {
                $this->_frontendOptions['cached_entity'] = $options['cached_entity'];
            }
        }
        return $this;
    }
    
    /**
    *    getBackEndOptions Function. return the catch options for backend varibale.
    *    return backendOptions.
    **/
    public function getBackEndOptions()
    {
        return $this->_backendOptions;
    }
    
    /**
    *    setBackEndOptions Function. set the catch options for backend varibale.
    *    @param mixed $options.
    *    return backendOptions.
    **/
    public function setBackEndOptions($options)
    {
        if(isset($options['cache_dir']))
        {
            $options['cache_dir'] = (string)trim($options['cache_dir']);
            if($options['cache_dir'])
            {
                if(!is_dir(Ccc::getBaseDir($options['cache_dir'])))
                {
                    throw new Core_Model_Exception('cache directory is not exists.');
                }
                
                $this->_frontendOptions['cache_dir'] = $options['cache_dir'];
            }
            
        }
        
        if(isset($options['file_name_prefix']))
        {
            if((string)$options['file_name_prefix'])
            {
                $this->_backendOptions['file_name_prefix'] = (string)$options['file_name_prefix'];
            }
        }
        
        return $this;
    }
    
    /**
    *    Get Instance Function. Used to get instance of cache.
    *    @param mixed $frontendoptions.
    *    @param mixed $backendOptions.
    *    @param mixed $type.
    *    @return cache.
    **/
    public function getInstance($frontendOptions = null, $backendOptions = null, $type = '')
    {
        if(!$this->_cache)
        {    
            $this->setFrontEndOptions($frontendOptions);
            $this->setBackEndOptions($backendOptions);
            
            $this->_cache = Zend_Cache::factory('Class',
                                        'File',
                                        $this->getFrontEndOptions(),
                                        $this->getBackEndOptions()
                                        );
                                        
          //Zend_Db_Table_Abstract::setDefaultMetadataCache($this->_cache);                                                                    
        }
        return $this->_cache;
    }
    
    /**
    *    Get Cache By Code Function. Used to get cache from given code from cache.
    *    @param mixed $code
    *    @return data.
    **/
    public function getCacheByCode($code)
    {
        $code = trim($code);
        if(!$code)
        {
            throw new Core_Model_Exception('"$code" should not be empty.');
        }
        
        $cacheModel = Ccc::getModel('cache/cache');
    
        $frontend = array(                
            'cached_entity'    => $this
        );
        
        $current = $cacheModel->getInstance($frontend);
               
        $current->setTagsArray(array(self::CACHE_CODE));
        return $current->getCacheByCodeCache($code);        
    }
    
    /**
    *    Get Cache By Code Cache Function. Used to get Cache of cache from given code from database.
    *    @param mixed $code
    *    @return data.
    **/
    public function getCacheByCodeCache($code)
    {   
        $select = $this->select()->where('code = ?', $code)->where('is_enabled = ?', Cache_Model_Cache_Status::IS_ENABLED_YES);   
        return $this->fetchRow($select);
    }
    
    /**
    *    Clear Cache Function. Used to clear content of Cache.
    *    @param mixed $code
    *    @param array $tags
    *    @return object.
    **/
    public function clearCache($code, $tags = array())
    {
        $cache = $this->getCacheByCode($code);
        
        if($cache)
        {
            $frontendOptions = array( 'cached_entity' => $this);
            $backendOpions = array('file_name_prefix' => $cache->prefix);
            
            if($code == Core_Model_Table_Abstract::CACHE_CODE)
            {
                $frontendOptions["cache_id_prefix"]  =  Core_Model_Table_Abstract::CACHE_CODE;
                $cacheInstance = $this->getInstance($frontendOptions,$backendOpions);
                if(count($cacheInstance->getIds()))
                {
                    foreach($cacheInstance->getIds() as $_id)
                    {
                        $cacheInstance->remove($_id);
                    }
                }
            }
            else
            {
                if(!empty($tags) && is_array($tags))
                {
                    $this->getInstance($frontendOptions,$backendOpions)->clean(Zend_Cache::CLEANING_MODE_MATCHING_TAG, $tags);
                }
                else
                {
                   $cacheInstance = $this->getInstance($frontendOptions,$backendOpions);
                   $cacheInstance->clean(Zend_Cache::CLEANING_MODE_MATCHING_TAG,array($cache->tag));
                }
            }
        }
        return $this;
    }
    
    
}