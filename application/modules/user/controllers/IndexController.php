<?php
class User_IndexController extends Ccc_Controller_Action_Front
{
    protected function _authenticate()
    {
        return array('index');
    }
    
    public function preDispatch()
    {   
        parent::preDispatch();  
        $this->_setLayout('front');
    }
    
    public function indexAction()
    {
        if($seo = Ccc::getSingleton('config/system_config')->getSystemConfig('general/website/seo_title'))
        {
            $this->setTitle(Ccc::getSingleton('config/system_config')->getSystemConfig('general/website/name')." : ".$seo);
        }
        else
        {
            $this->setTitle(Ccc::getSingleton('config/system_config')->getSystemConfig('general/website/name'));
        }
        
        $this->setMetaTag("description", Ccc::getSingleton('config/system_config')->getSystemConfig('general/website/seo_description'));
        $this->setMetaTag("keywords", Ccc::getSingleton('config/system_config')->getSystemConfig('general/website/seo_keywords'));
        $this->setMetaTag("application-name", Ccc::getSingleton('config/system_config')->getSystemConfig('general/website/seo_title'));
        $this->setMetaTag("application-url", Ccc::getSingleton('config/system_config')->getSystemConfig('website/base/url')); 
        
        $this->getResponse()->appendBody(Ccc::getBlock("user/index_index")->toHtml());
        $this->getHelper('viewRenderer')->setNoRender(true);
    }    
}
