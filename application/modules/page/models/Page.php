<?php
class Page_Model_Page extends Core_Model_Table_Abstract
{
	protected $_name = 'page';
    protected $_primary = 'page_id';
    protected $_rowClass = 'Page_Model_Page_Row';
    protected $_rowsetClass = 'Page_Model_Page_Rowset';
  
    const IS_ENABLED_YES = 1;
    const IS_ENABLED_NO  = 2; 
    const IS_ENABLED_YES_TEXT = 'Yes';
    const IS_ENABLED_NO_TEXT  = 'No';
   
    public function getIsEnabledOptions()
    {
        $options = array(
            Page_Model_Page::IS_ENABLED_YES => Page_Model_Page::IS_ENABLED_YES_TEXT,
            Page_Model_Page::IS_ENABLED_NO  => Page_Model_Page::IS_ENABLED_NO_TEXT
        );
        return $options;
    }
}