<?php
class Admin_View_Block_Website_Coupon_Index extends Admin_View_Block_Abstract
{
    public function __construct()
    {
        parent::__construct();
        $this->setTemplate('website/coupon/index.phtml');
    }
    
    public function getCreateUrl()
    {
        return $this->url(array('action'=>'create'));
    }
    
    public function getGridUrl()
    {
        return $this->url(array('action'=>'grid'));
    }
    
    public function getWebsite()
    {
         $object = Zend_Controller_Front::getInstance();
        if($id = (int)$object->getRequest()->getParam('wid', 0))
        {
            $website = Ccc::getModel("website/website")->find($id);
            if(!$website->valid())
            {
                throw new Exception($this->_getTranslate()->translate('Website ID is not valid.'));
            }
            
            return $website->current();
        }
    }
    
    public function getBackUrl()
    {
        return $this->url(array('module'=>'admin','controller'=>'website_index','action'=>'index-json','wid'=>null));
    }
}
