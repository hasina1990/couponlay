<?php
class Core_Model_Table_Abstract extends Zend_Db_Table_Abstract
{
    const CACHE_CODE = "table_metadata";
    
    /**
    *    $_recordPerPage protected Variable. Used to store howmany records displayed Per Page.
    *    @var mixed.
    **/
    protected $_recordPerPage = 20;
    
    /**
    *    $_recordPerPageOptions protected Variable. Used to get options that howmany records displayed Per Page.
    *    @var array.
    **/
    protected $_recordPerPageOptions = array(
                                            10=>10,
                                            20=>20,
                                            30=>30,
                                            50=>50,
                                            100=>100,
                                            200=>200
                                            );
    
    /**
    *    $_pageRange protected Variable. Used to set range to be displayed in pagination.
    *    @var mixed.
    **/
    protected $_pageRange = 5;
    
    /**
    *    $_pageDefault protected Variable. Used to set default page to be displayed as pagination.
    *    @var mixed.
    **/
    protected $_pageDefault = 1;
    
    public function __construct()
    {    
        /*$frontend = array(                
            'lifetime'    => 86400,
            'cached_entity'    => $this,
            'automatic_serialization'=>false,
            'cache_id_prefix' => self::CACHE_CODE
        );
        
        $backend = array(
            'cache_dir' => Ccc::getBaseDir(DIRECTORY_SEPARATOR.'data'.DIRECTORY_SEPARATOR.'cache'),
            'file_name_prefix'    =>    self::CACHE_CODE,
            'cache_file_perm'   =>  0777
        );     
               
        $metadataCache = Zend_Cache::factory('Class',
                                    'File',
                                    $frontend,
                                    $backend
                                    );
                                    
        $metadataCache->setTagsArray(array(self::CACHE_CODE));
        if($metadataCache)
        {
            $this->setDefaultMetadataCache($metadataCache);
        }*/
               
        parent::__construct();
    }
    
    /*protected function _setupMetadata()
    {
        if ($this->metadataCacheInClass() && (count($this->_metadata) > 0)) {
            return true;
        }

        // Assume that metadata will be loaded from cache
        $isMetadataFromCache = true;

        // If $this has no metadata cache but the class has a default metadata cache
        if (null === $this->_metadataCache && null !== self::$_defaultMetadataCache) {
            // Make $this use the default metadata cache of the class
            $this->_setMetadataCache(self::$_defaultMetadataCache);
        }

        // If $this has a metadata cache
        if (null !== $this->_metadataCache) {
            // Define the cache identifier where the metadata are saved

            //get db configuration
            $dbConfig = $this->_db->getConfig();

            $port = isset($dbConfig['options']['port'])
                  ? ':'.$dbConfig['options']['port']
                  : (isset($dbConfig['port'])
                  ? ':'.$dbConfig['port']
                  : null);

            $host = isset($dbConfig['options']['host'])
                  ? ':'.$dbConfig['options']['host']
                  : (isset($dbConfig['host'])
                  ? ':'.$dbConfig['host']
                  : null);

            // Define the cache identifier where the metadata are saved
            $cacheId = md5( // port:host/dbname:schema.table (based on availabilty)
                    $port . $host . '/'. $dbConfig['dbname'] . ':'
                  . $this->_schema. '.' . $this->_name
            );
        }

        // If $this has no metadata cache or metadata cache misses
        if (null === $this->_metadataCache || !($metadata = $this->_metadataCache->load($cacheId))) {
            // Metadata are not loaded from cache
            $isMetadataFromCache = false;
            // Fetch metadata from the adapter's describeTable() method
            $metadata = $this->_db->describeTable($this->_name, $this->_schema);
            // If $this has a metadata cache, then cache the metadata
            if (null !== $this->_metadataCache && !$this->_metadataCache->save($metadata, $cacheId)) {
            }
        }

        // Assign the metadata to $this
        $this->_metadata = $metadata;

        // Return whether the metadata were loaded from cache
        return $isMetadataFromCache;
    }*/
    
    protected function _setupTableName() 
    {
        parent::_setupTableName();
        $this->_name = $this->_name;
    }

    public function getTableName()
    {
        return $this->_name;
    }
    public function getPrimary()
    {
        return $this->_primary[1];
    }
    
    /**
    *   Get Current Page Function. This fuction is used to get Current Page number.
    *   @return pageDefault|perpage.
    **/
    public function getCurrentPage()
    {
        $front = Zend_Controller_Front::getInstance();
        
        if($perpage = (int)$front->getRequest()->getParam('page'))
        {
            return $perpage;
        }
        
        return $this->_pageDefault;
    }
    
    /**
    *   Get Page Range Function. This fuction is used to get Page Range.
    *   @return pageRange.
    **/
    public function getPageRange()
    {
        return $this->_pageRange;
    }
    
    /**
    *   Get Record Per Page Function. This fuction is used to get howmany Records Per Page.
    *   @return recordPerPage|perpage.
    **/
    public function getRecordPerPage()
    {
        $front = Zend_Controller_Front::getInstance();
        
        if($perpage = (int)$front->getRequest()->getParam('perpage'))
        {
            if(in_array($perpage, $this->getRecordPerPageOption()))
            {
                return $perpage;
            }
        }
        
        return $this->_recordPerPage;            
    }
    
    /**
    *   Get Record Per Page Option Function. This fuction is used to get Option of howmany Records Per Page.
    *   @return recordPerPage.
    **/
    public function getRecordPerPageOption()
    {
        return $this->_recordPerPageOptions;
    }
    
    protected function _getTimezoneModel()
    {
        return Ccc::getModel('core/timezone');
    }
}