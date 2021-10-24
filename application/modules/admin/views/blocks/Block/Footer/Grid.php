<?php
class Admin_View_Block_Block_Footer_Grid extends Admin_View_Block_Widget_Grid
{
    protected $_defaultSort     = 'B.block_id';
    
    public function __construct()
    {
        parent::__construct();
        $this->setTemplate('block/footer/grid.phtml');
    }
    
    public function getSaveUrl()
    {
        return $this->url(array('action'=>'save'));
    }
    
    public function getFooterOneTitleConfig()
    {
        $config = Ccc::getModel("config/system_config")->fetchRow("access_key = '".Config_Model_System_Config::FOOETR_ONE_TITLE."'");
        if(!$config)
        {
            $config =  Ccc::getModel("config/system_config")->createRow();
        }
      
        return $config;
    }
    
    public function getFooterOneDescriptionConfig()
    {
        $config = Ccc::getModel("config/system_config")->fetchRow("access_key = '".Config_Model_System_Config::FOOETR_ONE_DESCRIPTION."'");
        if(!$config)
        {
            $config =  Ccc::getModel("config/system_config")->createRow();
        }
      
        return $config;
    }
    
    public function getFooterTwoTitleConfig()
    {
        $config = Ccc::getModel("config/system_config")->fetchRow("access_key = '".Config_Model_System_Config::FOOETR_TWO_TITLE."'");
        if(!$config)
        {
            $config =  Ccc::getModel("config/system_config")->createRow();
        }
      
        return $config;
    }
    
    public function getFooterTwoDescriptionConfig()
    {
        $config = Ccc::getModel("config/system_config")->fetchRow("access_key = '".Config_Model_System_Config::FOOETR_TWO_DESCRIPTION."'");
        if(!$config)
        {
            $config =  Ccc::getModel("config/system_config")->createRow();
        }
      
        return $config;
    }
    
    public function getFooterThreeTitleConfig()
    {
        $config = Ccc::getModel("config/system_config")->fetchRow("access_key = '".Config_Model_System_Config::FOOETR_THREE_TITLE."'");
        if(!$config)
        {
            $config =  Ccc::getModel("config/system_config")->createRow();
        }
      
        return $config;
    }
    
    public function getFooterThreeDescriptionConfig()
    {
        $config = Ccc::getModel("config/system_config")->fetchRow("access_key = '".Config_Model_System_Config::FOOETR_THREE_DESCRIPTION."'");
        if(!$config)
        {
            $config =  Ccc::getModel("config/system_config")->createRow();
        }
      
        return $config;
    }
}
