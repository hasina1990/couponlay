<?php
class Admin_View_Block_Tag_Index_Create extends Admin_View_Block_Abstract
{
    protected $_tag = null;
    
    public function __construct()
    {
        parent::__construct();
        $this->setTemplate('tag/index/create.phtml');
    }
    
    public function getTag()
    {
        if(!$this->_tag)
        {
            $object = Zend_Controller_Front::getInstance();
            if($id = (int)$object->getRequest()->getParam('id', 0))
            {
                $tag = Ccc::getModel("tag/tag")->find($id);
                if(!$tag->valid())
                {
                    throw new Exception($this->_getTranslate()->translate('Tag ID is not valid.'));
                }
                
                $this->_tag = $tag->current();
            }
            else
            {
                $this->_tag = Ccc::getModel("tag/tag")->createRow();
            }
        }
        
        return $this->_tag;
    }
    
    public function getCategories()
    {
        $categoryModel     = Ccc::getModel('category/category');
        
        $select = $categoryModel->select()
                                ->setIntegrityCheck(false)
                                ->from(array('C'=>$categoryModel->getTableName()), array('category_id', 'name'));
                                
        return  array(0=>"")+$categoryModel->getAdapter()->fetchPairs($select);
    }
    
    public function getIsEnabledOptions()
    {
        return array(
            Tag_Model_Tag::IS_ENABLED_YES => Tag_Model_Tag::IS_ENABLED_YES_TEXT,
            Tag_Model_Tag::IS_ENABLED_NO => Tag_Model_Tag::IS_ENABLED_NO_TEXT
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
