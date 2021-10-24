<?php
class Config_Model_Uploader extends Core_Model_Uploader
{
    protected $_config = null;
    
    const DEFAULT_IMAGE_NAME = "logo.png";
    const IMAGE_PATH = "websitelogo";
    const IMAGE_HEIGHT = 50;
    const IMAGE_WIDTH = 150;
    const IMAGE_PREFIX = "logo";
    
    public function getConfigImageUrl()
    {
         $file = Ccc::getBaseDir($this->getImagePath().DS.Config_Model_Uploader::IMAGE_PATH).DS.$this->_config->value; 
         if(file_exists($file) && !is_dir($file))
         {
             return Ccc::getModel("core/url")->getWebsiteUrl()."/".$this->getImagePath().Config_Model_Uploader::IMAGE_PATH."/".$this->_config->value;
         } 
         
         return Ccc::getModel("core/url")->getWebsiteUrl()."/".$this->getImagePath().Config_Model_Uploader::IMAGE_PATH."/".Config_Model_Uploader::DEFAULT_IMAGE_NAME;  
    }
    
    public function setConfig($config)
    {
        if(!($config instanceof Config_Model_System_Config_Row))
        {
             throw new Exception("config must be instance of Config_Model_System_Config_Row");
        }
        elseif(!$config->entity_id)
        {
            throw new Exception("config is not valid.");
        }
        
        $this->_config = $config;
        return $this;
    }
    
    public function getConfig()
    {
        return $this->_config;
    }
    
    public function saveConfigImage()
    {
        if(!$this->_config)
        {
            throw new Exception("Config Image is not saved.");
        }
        
        $oldIcon = $this->_config->value;
        
        $this->setImagePath(Config_Model_Uploader::IMAGE_PATH);
        $this->setImageName(Config_Model_Uploader::IMAGE_PREFIX.$this->_config->entity_id."_".time());
        $this->setHeight(Config_Model_Uploader::IMAGE_HEIGHT);
        $this->setWidth(Config_Model_Uploader::IMAGE_WIDTH);
        $this->_config->value = $this->uploadImage();
        $this->_config->save();
        
        if($oldIcon)
        {
            $this->removeConfigIcon($oldIcon);
        }
        
        return $this;
    }
    
    public function removeConfigIcon($oldIcon)
    {
        $uploader = Ccc::getModel("config/uploader");
        $uploader->setImagePath(Config_Model_Uploader::IMAGE_PATH);   
        $uploader->setImageName($oldIcon);
        
        $file = Ccc::getBaseDir($uploader->getImagePath()).DS.$oldIcon; 
        if(file_exists($file) || !is_dir($file))
         {
             unlink($file);
         }
         
         return $this;
    }
}