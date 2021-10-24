<?php
class Category_ListController extends Ccc_Controller_Action_Front
{
    public function preDispatch()
    {                             
        parent::preDispatch();
        $this->_setLayout('front');
    }
    
    public function indexAction()
    {
        Ccc::getModel("user/session")->unsetSession("search");
        $this->setTitle('All Categories'); 
        $this->setMetaTag("description", "All Categories");
        $this->setMetaTag("keywords", "All Categories");
        $this->setMetaTag("application-name", Ccc::getSingleton('config/system_config')->getSystemConfig('general/website/seo_title'));
        $this->setMetaTag("application-url", Ccc::getSingleton('config/system_config')->getSystemConfig('website/base/url'));
        $this->getResponse()->appendBody(Ccc::getBlock("category/list_index")->toHtml());
        $this->getHelper('viewRenderer')->setNoRender(true);
    }
}