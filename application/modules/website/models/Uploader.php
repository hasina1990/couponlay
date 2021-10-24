<?php
class Website_Model_Uploader extends Core_Model_Uploader
{
    protected $_website = null;
    
    const IMAGE_PREFIX = "website_";
    const DEFAULT_IMAGE_NAME = "default.jpg";
    const MAIN_IMAGE_PATH = "website/main";
    const MAIN_IMAGE_HEIGHT = 267;
    const MAIN_IMAGE_WIDTH = 350;
    
    const ICON_IMAGE_PATH = "website/icon";
    const ICON_IMAGE_HEIGHT = 76;
    const ICON_IMAGE_WIDTH = 200;
    
    const SMALL_IMAGE_PATH = "website/icon/small";
    const SMALL_IMAGE_HEIGHT = 38;
    const SMALL_IMAGE_WIDTH = 100;
    
    public function getIconImageUrl($icon = null)
    {
        if(!$icon)
        {
            $icon = $this->_website->icon;
        }
        
         $file = Ccc::getBaseDir($this->getImagePath().DS.Website_Model_Uploader::ICON_IMAGE_PATH).DS.$icon; 
         if(file_exists($file) && !is_dir($file))
         {
             return Ccc::getModel("core/url")->getWebsiteUrl()."/".$this->getImagePath().Website_Model_Uploader::ICON_IMAGE_PATH."/".$icon;
         } 
         
         return Ccc::getModel("core/url")->getWebsiteUrl()."/".$this->getImagePath().Website_Model_Uploader::ICON_IMAGE_PATH."/".Website_Model_Uploader::DEFAULT_IMAGE_NAME;  
    }
    
    public function getSmallImageUrl($icon = null)
    {
        if(!$icon)
        {
            $icon = $this->_website->icon;
        }
        
         $file = Ccc::getBaseDir($this->getImagePath().DS.Website_Model_Uploader::SMALL_IMAGE_PATH).DS.$icon; 
         if(file_exists($file) && !is_dir($file))
         {
             return Ccc::getModel("core/url")->getWebsiteUrl()."/".$this->getImagePath().Website_Model_Uploader::SMALL_IMAGE_PATH."/".$icon;
         } 
         
         return Ccc::getModel("core/url")->getWebsiteUrl()."/".$this->getImagePath().Website_Model_Uploader::SMALL_IMAGE_PATH."/".Website_Model_Uploader::DEFAULT_IMAGE_NAME;  
    }
     
    public function saveWebsiteIconImage()
    {
        if(!$this->_website)
        {
            throw new Exception("Website is not valid.");
        }
        
        $oldImage = $this->_website->icon;
        $websiteName = Website_Model_Uploader::IMAGE_PREFIX.$this->_website->website_id."_".time();
        
        $this->setImagePath(Website_Model_Uploader::ICON_IMAGE_PATH);
        $this->setImageName($websiteName);
        $this->setHeight(Website_Model_Uploader::ICON_IMAGE_HEIGHT);
        $this->setWidth(Website_Model_Uploader::ICON_IMAGE_WIDTH);
        $this->_website->icon = $this->uploadImage();
        $this->_website->save();
        
        $imagePath = Ccc::getBaseDir($this->getImagePath()).DS.$this->_website->icon;
       
        $file = array(
            'tmp_name'=>$imagePath, 
            'name' => basename($imagePath), 
            'error' => 0,  
            'type' => mime_content_type($imagePath),  
            'size' => filesize($imagePath)
        );
        
        Ccc::getModel('website/uploader')->setWebsite($this->_website)->setFile($file)->saveWebsiteSmallImage($websiteName);
        if($oldImage)
        {
            $this->removeWebsiteIconImage($oldImage);
        }
        
        return $this;
    }
    
    public function saveWebsiteSmallImage($websiteName)
    {
        if(!$this->_website)
        {
            throw new Exception("Website is not valid.");
        }
               
        $file = $this->getFile();
        $this->setImagePath(Website_Model_Uploader::SMALL_IMAGE_PATH);
        $this->setImageName($websiteName);
        
        $extension = end(explode('.',$this->_website->icon));
        copy($file["tmp_name"], $this->getImagePath().DS.$websiteName.".".$extension);
        
        $this->setHeight(Website_Model_Uploader::SMALL_IMAGE_HEIGHT);
        $this->setWidth(Website_Model_Uploader::SMALL_IMAGE_WIDTH);
        $smallIcon = $this->uploadImage();
        return $this;
    }
        
    public function removeWebsiteIconImage($oldIcon)
    {
        $uploader = Ccc::getModel("website/uploader");
        $uploader->setImagePath(Website_Model_Uploader::ICON_IMAGE_PATH);   
        $uploader->setImageName($oldIcon);
        
        $file = Ccc::getBaseDir($uploader->getImagePath()).DS.$oldIcon; 
        if(file_exists($file) || !is_dir($file))
         {
             unlink($file);
         }
         
         return $this;
    }
    
    public function getMainImageUrl()
    {
         $file = Ccc::getBaseDir($this->getImagePath().DS.Website_Model_Uploader::MAIN_IMAGE_PATH).DS.$this->_website->main_image; 
         if(file_exists($file) && !is_dir($file))
         {
             return Ccc::getModel("core/url")->getWebsiteUrl()."/".$this->getImagePath().Website_Model_Uploader::MAIN_IMAGE_PATH."/".$this->_website->main_image;
         } 
         
         return Ccc::getModel("core/url")->getWebsiteUrl()."/".$this->getImagePath().Website_Model_Uploader::MAIN_IMAGE_PATH."/".Website_Model_Uploader::DEFAULT_IMAGE_NAME;  
    }
     
    public function saveWebsiteMainImage()
    {
        if(!$this->_website)
        {
            throw new Exception("Website is not valid.");
        }
        
        $oldImage = $this->_website->main_image;
        
        $this->setImagePath(Website_Model_Uploader::MAIN_IMAGE_PATH);
        $this->setImageName(Website_Model_Uploader::IMAGE_PREFIX.$this->_website->website_id."_".time());
        $this->setHeight(Website_Model_Uploader::MAIN_IMAGE_HEIGHT);
        $this->setWidth(Website_Model_Uploader::MAIN_IMAGE_WIDTH);
        $this->_website->main_image = $this->uploadImage();
        $this->_website->save();
        
        if($oldImage)
        {
            $this->removeWebsiteMainImage($oldImage);
        }
        
        return $this;
    }
    
    public function removeWebsiteMainImage($oldIcon)
    {
        $uploader = Ccc::getModel("website/uploader");
        $uploader->setImagePath(Website_Model_Uploader::MAIN_IMAGE_PATH);   
        $uploader->setImageName($oldIcon);
        
        $file = Ccc::getBaseDir($uploader->getImagePath()).DS.$oldIcon; 
        if(file_exists($file) || !is_dir($file))
         {
             unlink($file);
         }
         
         return $this;
    }
    
    public function setWebsite($website)
    {
        if(!($website instanceof Website_Model_Website_Row))
        {
             throw new Exception("website must be instance of Website_Model_Website_Row");
        }
        elseif(!$website->website_id)
        {
            throw new Exception("website is not valid.");
        }
        
        $this->_website = $website;
        return $this;
    }
    
    public function getWebsite()
    {
        return $this->_website;
    }
}