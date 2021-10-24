<?php
class Admin_View_Block_Website_Index_Create extends Admin_View_Block_Abstract
{
    protected $_website = null;
    public function __construct()
    {
        parent::__construct();
        $this->setTemplate('website/index/create.phtml');
    }
    
    public function getWebsite()
    {
        if(!$this->_category)
        {
            $object = Zend_Controller_Front::getInstance();
            if($id = (int)$object->getRequest()->getParam('id', 0))
            {
                $website = Ccc::getModel("website/website")->find($id);
                if(!$website->valid())
                {
                    throw new Exception($this->_getTranslate()->translate('Website ID is not valid.'));
                }
                
                $this->_website = $website->current();
            }
            else
            {
                $this->_website = Ccc::getModel("website/website")->createRow();
            }
        }
        
        return $this->_website;
    }
    
    public function getCatgeories()
    {
        $categoryModel  = Ccc::getModel('category/category');
        
        $select = $categoryModel->select()
                            ->setIntegrityCheck(false)
                            ->from(array('C'=>$categoryModel->getTableName()),array('category_id', 'name'))
                            ->order("category_id ASC");
        
        return $categoryModel->getAdapter()->fetchPairs($select);
    }
    
    public function getIsEnabledOptions()
    {
        return array(
            Website_Model_Website::IS_ENABLED_YES => Website_Model_Website::IS_ENABLED_YES_TEXT,
            Website_Model_Website::IS_ENABLED_NO => Website_Model_Website::IS_ENABLED_NO_TEXT
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
