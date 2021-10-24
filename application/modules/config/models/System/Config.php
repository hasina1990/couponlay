<?php
class Config_Model_System_Config extends Core_Model_Table_Abstract
{
	protected $_name = 'system_config';
	protected $_primary   = 'entity_id';
    protected $_rowClass = 'Config_Model_System_Config_Row';
	protected $_rowsetClass = 'Config_Model_System_Config_Rowset';
    
    const WEBSITE_NAME              = "general/website/name";
    const WEBSITE_SEO_TITLE         = "general/website/seo_title";
    const WEBSITE_SEO_DESCRIPTION   = "general/website/seo_description";
    const WEBSITE_SEO_KEYWORD       = "general/website/seo_keywords";
    const WEBSITE_LOGO              = "general/website/logo";
    const WEBSITE_FACEBOOK          = "general/website/facebook";
    const WEBSITE_TWITTER           = "general/website/twitter";
    const WEBSITE_GOOGLE            = "general/website/google";
    const FOOETR_ONE_TITLE          = "general/website/footer_one_title";
    const FOOETR_ONE_DESCRIPTION    = "general/website/footer_one_description";
    const FOOETR_TWO_TITLE          = "general/website/footer_two_title";
    const FOOETR_TWO_DESCRIPTION    = "general/website/footer_two_description";
    const FOOETR_THREE_TITLE          = "general/website/footer_three_title";
    const FOOETR_THREE_DESCRIPTION    = "general/website/footer_three_description";
    
    // field type constants
    const FIELD_TYPE_TEXT_KEY       = 'text';
    const FIELD_TYPE_BOOLEAN_KEY    = 'boolean';
    const FIELD_TYPE_SELECT_KEY     = 'select';
    
    const FIELD_TYPE_TEXT       = 'Text';
    const FIELD_TYPE_BOOLEAN    = 'Boolean';
    const FIELD_TYPE_SELECT     = 'Select';
    
    // value constants
    const BOOLEAN_VALUE_YES = True;
    const BOOLEAN_VALUE_NO  = False;
    
    const BOOLEAN_VALUE_YES_TEXT = "YES";
    const BOOLEAN_VALUE_NO_TEXT  = "NO";
    
    /**
    *    cache code
    **/
    const CACHE_CODE = 'system_config';
    
    public function getFieldTypeOption()
    {           
        //$_options = array();               
        
        $_options[self::FIELD_TYPE_TEXT_KEY]       = self::FIELD_TYPE_TEXT;
        $_options[self::FIELD_TYPE_BOOLEAN_KEY]    = self::FIELD_TYPE_BOOLEAN;
        $_options[self::FIELD_TYPE_SELECT_KEY]     = self::FIELD_TYPE_SELECT;
        
        return $_options;
    }
    /**
    *    Get Field Type Options Function. This function is used to get options for Field Type.
    *    @return options.
    **/                        
    public function getBooleanValueOption()
    {           
        //$_options = array();               
        
        $_options[self::BOOLEAN_VALUE_YES]  = self::BOOLEAN_VALUE_YES_TEXT;
        $_options[self::BOOLEAN_VALUE_NO]   = self::BOOLEAN_VALUE_NO_TEXT;
        
        return $_options;
    }
    /**
    *    Check Access Key Function. This function check the record is accessible or not.
    *    @param mixed $access_key.
    *    @param mixed $id.
    *   @return true | false.
    **/
    public function checkAccessKey($access_key, $id)
    {
        $config = $this->fetchRow("access_key = '".$access_key."' AND entity_id != '".$id."'");
        
        if(isset($config->entity_id))
        {
            return true;
        }
        return false;
    }
    /**
    *    Get System Config Function. This function is used to get value of Syatem Configuration.
    *   @param mixed $access_key.
    *    @return config.
    **/
    public function getSystemConfig($access_key)
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
            $current->setTagsArray(array($cache->tag, md5($access_key)));
            
            return $current->getSystemConfigCache($access_key);
            
        }
        else
        {
            return $this->getSystemConfigCache($access_key);
        }
    }
    /**
    *    Get System Config Function. This function is used to get value of Syatem Configuration cache.
    *   @param mixed $access_key.
    *    @return config|null.
    **/
    public function getSystemConfigCache($access_key)
    {
        $access_key = trim($access_key);
        
        if(!$access_key)
        {
            throw new Ccc_Core_Model_System_Exception('"$access_key" must have non-empty value.');
        }
        
        $config = $this->fetchRow("access_key = '".$access_key."'");
        if($config)
        {
            return $config->value;
        }
        return null;
    }
	
    public function updateVisitorCount()
    {
        $config = $this->fetchRow("access_key = 'general/website/visitor_count'");
        if($config)
        {
             $config->value  = (int)$config->value + 1;
             $config->save();
        }             
        return $this;        
    }
}
