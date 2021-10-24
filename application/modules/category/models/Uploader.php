<?php
class Category_Model_Uploader extends Core_Model_Uploader
{
    protected $_category = null;
    
    const DEFAULT_IMAGE_NAME = "default.png";
    const IMAGE_PATH = "category";
    const IMAGE_HEIGHT = 150;
    const IMAGE_WIDTH = 150;
    const IMAGE_PREFIX = "category_";
    
    public function getCategoryImageUrl()
    {
         $file = Ccc::getBaseDir($this->getImagePath().DS.Category_Model_Uploader::IMAGE_PATH).DS.$this->_category->icon_url; 
         if(file_exists($file) && !is_dir($file))
         {
             return Ccc::getModel("core/url")->getWebsiteUrl()."/".$this->getImagePath().Category_Model_Uploader::IMAGE_PATH."/".$this->_category->icon_url;
         } 
         
         return Ccc::getModel("core/url")->getWebsiteUrl()."/".$this->getImagePath().Category_Model_Uploader::IMAGE_PATH."/".Category_Model_Uploader::DEFAULT_IMAGE_NAME;  
    }
    
    public function setCategory($category)
    {
        if(!($category instanceof Category_Model_Category_Row))
        {
             throw new Exception("category must be instance of Category_Model_Category_Row");
        }
        elseif(!$category->category_id)
        {
            throw new Exception("category is not valid.");
        }
        
        $this->_category = $category;
        return $this;
    }
    
    public function getCategory()
    {
        return $this->_category;
    }
    
    public function saveCategoryImage()
    {
        if(!$this->_category)
        {
            throw new Exception("Category Image is not saved.");
        }
        
        $oldIcon = $this->_category->icon_url;
        
        $this->setImagePath(Category_Model_Uploader::IMAGE_PATH);
        $this->setImageName(Category_Model_Uploader::IMAGE_PREFIX.$this->_category->category_id."_".time());
        $this->setHeight(Category_Model_Uploader::IMAGE_HEIGHT);
        $this->setWidth(Category_Model_Uploader::IMAGE_WIDTH);
        $this->_category->icon_url = $this->uploadImage();
        $this->_category->save();
        
        if($oldIcon)
        {
            $this->removeCategoryIcon($oldIcon);
        }
        
        return $this;
    }
    
    public function removeCategoryIcon($oldIcon)
    {
        $uploader = Ccc::getModel("category/uploader");
        $uploader->setImagePath(Category_Model_Uploader::IMAGE_PATH);   
        $uploader->setImageName($oldIcon);
        
        $file = Ccc::getBaseDir($uploader->getImagePath()).DS.$oldIcon; 
        if(file_exists($file) || !is_dir($file))
         {
             unlink($file);
         }
         
         return $this;
    }
}