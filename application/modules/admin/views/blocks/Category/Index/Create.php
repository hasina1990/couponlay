<?php
class Admin_View_Block_Category_Index_Create extends Admin_View_Block_Abstract
{
    protected $_category = null;
    public function __construct()
    {
        parent::__construct();
        $this->setTemplate('category/index/create.phtml');
    }
    
    public function getCategory()
    {
        if(!$this->_category)
        {
            $object = Zend_Controller_Front::getInstance();
            if($id = (int)$object->getRequest()->getParam('id', 0))
            {
                $category = Ccc::getModel("category/category")->find($id);
                if(!$category->valid())
                {
                    throw new Exception($this->_getTranslate()->translate('Category ID is not valid.'));
                }
                
                $this->_category = $category->current();
            }
            else
            {
                $this->_category = Ccc::getModel("category/category")->createRow();
            }
        }
        
        return $this->_category;
    }
    
    public function getIsEnabledOptions()
    {
        return array(
            Category_Model_Category::IS_ENABLED_YES => Category_Model_Category::IS_ENABLED_YES_TEXT,
            Category_Model_Category::IS_ENABLED_NO => Category_Model_Category::IS_ENABLED_NO_TEXT
        );
    }
                 
    public function getIndexJsonUrl()
    {
        return $this->url(array('action'=>'index-json','id'=>null));
    }
    
    public function getSaveUrl()
    {
        return $this->url(array('action'=>'save'));
    }
}
