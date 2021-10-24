<?php
class Tag_Model_Tag extends Core_Model_Table_Abstract
{
	protected $_name = 'tag';
    protected $_primary = 'tag_id';
    protected $_rowClass = 'Tag_Model_Tag_Row';
    protected $_rowsetClass = 'Tag_Model_Tag_Rowset';
  
    const IS_ENABLED_YES = 1;
    const IS_ENABLED_NO  = 2; 
    const IS_ENABLED_YES_TEXT = 'Yes';
    const IS_ENABLED_NO_TEXT  = 'No';
   
    public function getIsEnabledOptions()
    {
        $options = array(
            Tag_Model_Tag::IS_ENABLED_YES => Tag_Model_Tag::IS_ENABLED_YES_TEXT,
            Tag_Model_Tag::IS_ENABLED_NO  => Tag_Model_Tag::IS_ENABLED_NO_TEXT
        );
        return $options;
    }
}