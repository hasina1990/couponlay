<?php
class Banner_Model_Uploader extends Core_Model_Uploader
{
    protected $_banner = null;
    
    const DEFAULT_IMAGE_NAME = "default.gif";
    const IMAGE_PATH = "banner";
    const IMAGE_PREFIX = "banner_";
    
    const BANNER_IMAGE_HEIGHT = 288;
    const BANNER_IMAGE_WIDTH = 694;
    
    const AD_IMAGE_HEIGHT = 280;
    const AD_IMAGE_WIDTH = 400;
    
    public function getBannerImageUrl()
    {
         $file = Ccc::getBaseDir($this->getImagePath().DS.Banner_Model_Uploader::IMAGE_PATH).DS.$this->_banner->image; 
         if(file_exists($file) || !is_dir($file))
         {
             return Ccc::getModel("core/url")->getWebsiteUrl()."/".$this->getImagePath().Banner_Model_Uploader::IMAGE_PATH."/".$this->_banner->image;
         } 
         
         return Ccc::getModel("core/url")->getWebsiteUrl()."/".$this->getImagePath().Banner_Model_Uploader::IMAGE_PATH."/".Banner_Model_Uploader::DEFAULT_IMAGE_NAME;  
    }
    
    public function setBanner($banner)
    {
        if(!($banner instanceof Banner_Model_Banner_Row))
        {
             throw new Exception("banner must be instance of Banner_Model_Banner_Row");
        }
        elseif(!$banner->banner_id)
        {
            throw new Exception("banner is not valid.");
        }
        
        $this->_banner = $banner;
        return $this;
    }
    
    public function getBanner()
    {
        return $this->_banner;
    }
    
    public function saveBannerImage()
    {
        if(!$this->_banner)
        {
            throw new Exception("Banner Image is not saved.");
        }
        
        $oldImage = $this->_banner->image;
        
        $this->setImagePath(Banner_Model_Uploader::IMAGE_PATH);
        $this->setImageName(Banner_Model_Uploader::IMAGE_PREFIX.$this->_banner->banner_id."_".time());
        $this->setHeight(Banner_Model_Uploader::BANNER_IMAGE_HEIGHT);
        $this->setWidth(Banner_Model_Uploader::BANNER_IMAGE_WIDTH);
        $this->_banner->image = $this->uploadImage();
        $this->_banner->save();
        
        if($oldImage)
        {
            $this->removeBannerImage($oldImage);
        }
        
        return $this;
    }
    
    public function saveAdvertiseImage()
    {
        if(!$this->_banner)
        {
            throw new Exception("Banner Image is not saved.");
        }
        
        $oldImage = $this->_banner->image;
        
        $this->setImagePath(Banner_Model_Uploader::IMAGE_PATH);
        $this->setImageName(Banner_Model_Uploader::IMAGE_PREFIX.$this->_banner->banner_id."_".time());
        $this->setHeight(Banner_Model_Uploader::AD_IMAGE_HEIGHT);
        $this->setWidth(Banner_Model_Uploader::AD_IMAGE_WIDTH);
        $this->_banner->image = $this->uploadImage();
        $this->_banner->save();
        
        if($oldImage)
        {
            $this->removeBannerImage($oldImage);
        }
        
        return $this;
    }
    
    public function removeBannerImage($oldImage)
    {
        $uploader = Ccc::getModel("banner/uploader");
        $uploader->setImagePath(Banner_Model_Uploader::IMAGE_PATH);   
        $uploader->setImageName($oldImage);
        
        $file = Ccc::getBaseDir($uploader->getImagePath()).DS.$oldImage; 
        if(file_exists($file) || !is_dir($file))
         {
             unlink($file);
         }
         
         return $this;
    }
}