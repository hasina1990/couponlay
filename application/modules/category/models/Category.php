<?php
class Category_Model_Category extends Core_Model_Table_Abstract
{
	protected $_name = 'category';
    protected $_primary = 'category_id';
    protected $_rowClass = 'Category_Model_Category_Row';
    protected $_rowsetClass = 'Category_Model_Category_Rowset';
  
    const IS_ENABLED_YES = 1;
    const IS_ENABLED_NO  = 2; 
    const IS_ENABLED_YES_TEXT = 'Yes';
    const IS_ENABLED_NO_TEXT  = 'No';
   
    const DEFAULT_IMAGE_NAME   = 'default.png';
    const IMAGE                = 'agent';
    const IMAGE_HEIGHT         = 75;
    const IMAGE_WIDTH          = 75;
    
    public function getDefaultImage()
    {
        $path = Ccc::getModel("agent/uploader")->getAgentImagePath();   
        return Ccc::getBaseDir($path."/".Agent_Model_Agent::IMAGE."/".Agent_Model_Agent::DEFAULT_IMAGE_NAME);
    }
    
    public function getIsEnabledOptions()
    {
        $options = array(
            Category_Model_Category::IS_ENABLED_YES => Category_Model_Category::IS_ENABLED_YES_TEXT,
            Category_Model_Category::IS_ENABLED_NO  => Category_Model_Category::IS_ENABLED_NO_TEXT
        );
        return $options;
    }
}