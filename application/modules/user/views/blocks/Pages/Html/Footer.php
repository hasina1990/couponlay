<?php
class User_View_Block_Pages_Html_Footer extends Core_View_Block_Abstract
{
    public function __construct()
    {
        parent::__construct();
        $this->setTemplate('pages/html/footer.phtml');
    }
   
   public function getFooterOneTitleConfig()
    {
        $config = Ccc::getModel("config/system_config")->fetchRow("access_key = '".Config_Model_System_Config::FOOETR_ONE_TITLE."'");
        if($config)
        {
            if($config->value)
            {
                return $config->value;
            }
        }
      
        return null;
    }
    
    public function getFooterOneDescriptionConfig()
    {
        $config = Ccc::getModel("config/system_config")->fetchRow("access_key = '".Config_Model_System_Config::FOOETR_ONE_DESCRIPTION."'");
        if($config)
        {
            if($config->value)
            {
                return $config->value;
            }
        }
      
        return null;
    }
    
    public function getFooterTwoTitleConfig()
    {
        $config = Ccc::getModel("config/system_config")->fetchRow("access_key = '".Config_Model_System_Config::FOOETR_TWO_TITLE."'");
        if($config)
        {
            if($config->value)
            {
                return $config->value;
            }
        }
      
        return null;
    }
    
    public function getFooterTwoDescriptionConfig()
    {
        $config = Ccc::getModel("config/system_config")->fetchRow("access_key = '".Config_Model_System_Config::FOOETR_TWO_DESCRIPTION."'");
        if($config)
        {
            if($config->value)
            {
                return $config->value;
            }
        }
      
        return null;
    }
    
    public function getFooterThreeTitleConfig()
    {
        $config = Ccc::getModel("config/system_config")->fetchRow("access_key = '".Config_Model_System_Config::FOOETR_THREE_TITLE."'");
         if($config)
        {
            if($config->value)
            {
                return $config->value;
            }
        }
      
        return null;
    }
    
    public function getFooterThreeDescriptionConfig()
    {
        $config = Ccc::getModel("config/system_config")->fetchRow("access_key = '".Config_Model_System_Config::FOOETR_THREE_DESCRIPTION."'");
        if($config)
        {
            if($config->value)
            {
                return $config->value;
            }
        }
      
        return null;
    }
    
    public function getCopyRightConfig()
    {
        $config = Ccc::getModel("config/system_config")->fetchRow("access_key = 'general/website/copyrighttext'");
        if($config)
        {
            if($config->value)
            {
                return $config->value;
            }
        }
      
        return date("Y-1")."-".date("Y")." Couponlay.in<br/>All Rights Reserved";
    }
}
