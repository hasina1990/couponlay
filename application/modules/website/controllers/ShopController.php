<?php
class Website_ShopController extends Ccc_Controller_Action_Front
{
    public function preDispatch()
    {
        parent::preDispatch();
        $this->_setLayout('front');
    }
    
    public function indexAction()
    {
        Ccc::getModel("user/session")->unsetSession("search");
        
        $title = "";
        if($character = $this->getRequest()->getParam("websiteStartCharacter", null))
        {
            $title = "Letter ".ucfirst($character).'- All Stores';    
        }
        else
        {
            $title = 'All Stores';
        }
        
        $this->setTitle($title);    
        $this->setMetaTag("description", $title);
        $this->setMetaTag("keywords", $title);
        $this->setMetaTag("application-name", Ccc::getSingleton('config/system_config')->getSystemConfig('general/website/seo_title'));
        $this->setMetaTag("application-url", Ccc::getSingleton('config/system_config')->getSystemConfig('website/base/url'));
        $this->getResponse()->appendBody(Ccc::getBlock("website/shop_index")->toHtml());
        $this->getHelper('viewRenderer')->setNoRender(true);
    }
}
