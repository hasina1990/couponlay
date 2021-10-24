<?php
class Admin_View_Block_Page_Index_Create extends Admin_View_Block_Abstract
{
    protected $_page = null;
    
    public function __construct()
    {
        parent::__construct();
        $this->setTemplate('page/index/create.phtml');
    }
    
    public function getPage()
    {
        if(!$this->_page)
        {
            $object = Zend_Controller_Front::getInstance();
            if($id = (int)$object->getRequest()->getParam('id', 0))
            {
                $page = Ccc::getModel("page/page")->find($id);
                if(!$page->valid())
                {
                    throw new Exception($this->_getTranslate()->translate('Page ID is not valid.'));
                }
                
                $this->_page = $page->current();
            }
            else
            {
                $this->_page = Ccc::getModel("page/page")->createRow();
            }
        }
        
        return $this->_page;
    }
    
    public function getIsEnabledOptions()
    {
        return array(
            Page_Model_Page::IS_ENABLED_YES => Page_Model_Page::IS_ENABLED_YES_TEXT,
            Page_Model_Page::IS_ENABLED_NO => Page_Model_Page::IS_ENABLED_NO_TEXT
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
