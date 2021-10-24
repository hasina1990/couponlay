<?php
class User_View_Block_Pages_Html_Header extends Core_View_Block_Abstract
{
    public function __construct()
    {
        parent::__construct();
        $this->setTemplate('pages/html/header.phtml');
    }
    
    public function getSearchUrl()
    {
        return $this->url(array('module'=>'user', 'controller'=>'search', 'action'=>'index'));
    }
    
    public function getCategories()
    {
        $categoryModel = Ccc::getModel("category/category");
        
        $select =  $categoryModel->select()
                                ->from($categoryModel->getTableName())
                                ->where("is_enabled = ?", Category_Model_Category::IS_ENABLED_YES)
                                ->order("created_date ASC");
                                
        return $categoryModel->fetchAll($select); 
    }
    
    public function getMenus()
    {
        $menuModel = Ccc::getModel("core/menu");
        
        $select = $menuModel->select()
                            ->from($menuModel->getTableName())
                            ->where("is_enabled = ?", Core_Model_Menu::IS_ENABLED_YES)
                            ->where("is_footer_link = ?", Core_Model_Menu::IS_FOOETR_LINK_NO)
                            ->order("sort_order ASC");
                            
        return  $menuModel->fetchAll($select);
    }
    
    public function getLogoURL()
    {
        $homeConfig = Ccc::getModel("config/system_config")->fetchRow("access_key = '".Config_Model_System_Config::WEBSITE_LOGO."'");
        if(!$homeConfig)
        {
            return null;
        }
        elseif(!$homeConfig->value)
        {
            return null;
        }
        
        return Ccc::getModel('config/uploader')->setConfig($homeConfig)->getConfigImageUrl();
    }
    
    public function getWebsiteName()
    {
        return Ccc::getModel("config/system_config")->getSystemConfig("general/website/name");
    }
    
    public function getHomeUrl()
    {
        return $this->baseUrl();
    }
    
    public function getWebsite()
    {
        if($this->getRequestObject()->getRequest()->getParam("website_id", 0))
        {
            $website = Ccc::getModel("user/session")->getSession("website", null);
            if($website)
            {
                return $website;
            }
        }
        return null;
    }
    
    public function getRequestObject()
    {
        $object = Zend_Controller_Front::getInstance();
        return $object;
    }
    
    public function isHomePage()
    {
        $params = $this->getRequestObject()->getRequest()->getParams();
        if($params["module"] == "user" && $params["controller"] == "index" && $params["action"] == "index")
        {
            return true;
        }
        
        return false;
    }
    
    public function getAllWebsitesForAutoComplete()
    {
          $websiteModel     = Ccc::getModel("website/website");
          
          $select = $websiteModel->select()
                                ->from(array("W"=>$websiteModel->getTableName()),array("name"))
                                ->where("is_enabled = ?", Website_Model_Website::IS_ENABLED_YES)
                                ->order('W.name ASC');
         
         return $websiteModel->getAdapter()->fetchCol($select);                        
    }
}
