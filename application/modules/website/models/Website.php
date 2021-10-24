<?php
class Website_Model_Website extends Core_Model_Table_Abstract
{
	protected $_name = 'website';
    protected $_primary = 'website_id';
    protected $_rowClass = 'Website_Model_Website_Row';
    protected $_rowsetClass = 'Website_Model_Website_Rowset';
  
    const IS_ENABLED_YES = 1;
    const IS_ENABLED_NO  = 2; 
    const IS_ENABLED_YES_TEXT = 'Yes';
    const IS_ENABLED_NO_TEXT  = 'No';
              
    public function getIsEnabledOptions()
    {
        $options = array(
            Website_Model_Website::IS_ENABLED_YES => Website_Model_Website::IS_ENABLED_YES_TEXT,
            Website_Model_Website::IS_ENABLED_NO  => Website_Model_Website::IS_ENABLED_NO_TEXT
        );
        return $options;
    }
}