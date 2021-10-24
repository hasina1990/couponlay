<?php
class Admin_View_Block_Cache_Index_Create extends Admin_View_Block_Abstract
{
    public function __construct()
    {
        parent::__construct();
        $this->setTemplate('cache/index/create.phtml');
    }
    
    public function getIndexJsonUrl()
    {
        return $this->url(array('action'=>'index-json','id'=>null));
    }
    
    public function getSaveUrl($cache)
    {
        return $this->url(array('action'=>'save'));
    }
    
    public function getCacheData()
    {
        $object = Zend_Controller_Front::getInstance();
        if($id = (int)$object->getRequest()->getParam('id', 0))
        {
            $cache = Ccc::getModel("cache/cache")->find($id);
            if(!$cache->valid())
            {
                throw new Ccc_Controller_Action_Admin_Exception('Block ID is not valid.');
                //throw new Ccc_Controller_Action_Admin_Exception($this->_getTranslate()->translate('Block ID is not valid.'));
            }
            $cache = $cache->current();
        }
        else
        {
            $cache = Ccc::getModel("cache/cache")->createRow();
        }
        
        return $cache;
    } 
    
    public function getObject()
    {
        $object = Zend_Controller_Front::getInstance();
        return $object->getRequest()->getParam('obj');
    }
}
