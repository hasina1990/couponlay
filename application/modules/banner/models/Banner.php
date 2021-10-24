<?php
class Banner_Model_Banner extends Core_Model_Table_Abstract
{
	protected $_name = 'banner';
    protected $_primary = 'banner_id';
    protected $_rowClass = 'Banner_Model_Banner_Row';
    protected $_rowsetClass = 'Banner_Model_Banner_Rowset';
  
    const IS_ENABLED_YES = 1;
    const IS_ENABLED_NO  = 2; 
    const IS_ENABLED_YES_TEXT = 'Yes';
    const IS_ENABLED_NO_TEXT  = 'No';
   
    const IS_AD_YES = 1;
    const IS_AD_NO  = 2; 
    const IS_AD_YES_TEXT = 'Yes';
    const IS_AD_NO_TEXT  = 'No';
             
    public function getIsEnabledOptions()
    {
        $options = array(
            Banner_Model_Banner::IS_ENABLED_YES => Banner_Model_Banner::IS_ENABLED_YES_TEXT,
            Banner_Model_Banner::IS_ENABLED_NO  => Banner_Model_Banner::IS_ENABLED_NO_TEXT
        );
        return $options;
    }
    
    public function getIsAdOptions()
    {
        $options = array(
            Banner_Model_Banner::IS_AD_YES => Banner_Model_Banner::IS_AD_YES_TEXT,
            Banner_Model_Banner::IS_AD_NO  => Banner_Model_Banner::IS_AD_NO_TEXT
        );
        return $options;
    }
}