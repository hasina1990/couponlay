<?php
class Admin_View_Block_Home_Index_Grid extends Admin_View_Block_Widget_Grid
{
    protected $selectValues=null;
    
    public function __construct()
    {
        parent::__construct();
        $this->setTemplate('home/index/grid.phtml');
    }

    public function getSaveUrl()
    {
        return $this->url(array('action'=>'save'));
    }
    
    public function getWebsiteLogoConfig()
    {
        $config = Ccc::getModel("config/system_config")->fetchRow("access_key = '".Config_Model_System_Config::WEBSITE_LOGO."'");
        if(!$config)
        {
            return null;
        }
      
        $logoImage = Ccc::getModel('config/uploader')->setConfig($config)->getConfigImageUrl();
        return $logoImage;
    }
    
    public function getWebsiteNameConfig()
    {
        $config = Ccc::getModel("config/system_config")->fetchRow("access_key = '".Config_Model_System_Config::WEBSITE_NAME."'");
        if(!$config)
        {
            $config =  Ccc::getModel("config/system_config")->createRow();
        }
      
        return $config;
    }
    
    public function getWebsiteSeoTitleConfig()
    {
        $config = Ccc::getModel("config/system_config")->fetchRow("access_key = '".Config_Model_System_Config::WEBSITE_SEO_TITLE."'");
        if(!$config)
        {
            $config =  Ccc::getModel("config/system_config")->createRow();
        }
      
        return $config;
    }
    
    public function getWebsiteSeoDescriptionConfig()
    {
        $config = Ccc::getModel("config/system_config")->fetchRow("access_key = '".Config_Model_System_Config::WEBSITE_SEO_DESCRIPTION."'");
        if(!$config)
        {
            $config =  Ccc::getModel("config/system_config")->createRow();
        }
      
        return $config;
    }
    
    public function getWebsiteSeoKeywordConfig()
    {
        $config = Ccc::getModel("config/system_config")->fetchRow("access_key = '".Config_Model_System_Config::WEBSITE_SEO_KEYWORD."'");
        if(!$config)
        {
            $config =  Ccc::getModel("config/system_config")->createRow();
        }
      
        return $config;
    }
    
    public function getWebsiteFacebookConfig()
    {
        $config = Ccc::getModel("config/system_config")->fetchRow("access_key = '".Config_Model_System_Config::WEBSITE_FACEBOOK."'");
        if(!$config)
        {
            $config =  Ccc::getModel("config/system_config")->createRow();
        }
      
        return $config;
    }
    
    public function getWebsiteTwitterConfig()
    {
        $config = Ccc::getModel("config/system_config")->fetchRow("access_key = '".Config_Model_System_Config::WEBSITE_TWITTER."'");
        if(!$config)
        {
            $config =  Ccc::getModel("config/system_config")->createRow();
        }
      
        return $config;
    }
    
    public function getWebsiteGoogleConfig()
    {
        $config = Ccc::getModel("config/system_config")->fetchRow("access_key = '".Config_Model_System_Config::WEBSITE_GOOGLE."'");
        if(!$config)
        {
            $config =  Ccc::getModel("config/system_config")->createRow();
        }
      
        return $config;
    }
}