<?php
class Core_Model_Uploader
{
    protected $_imagePath  =  'media/'; 
    protected $_imageName = null;
    protected $_file = null;
    protected $_allowedExtensions = array('jpeg','gif','png','bmp','jpg');
    protected $_allowedMimeTypes = array("gif"=>"image/gif", "png"=>"image/png", "jpeg"=>"image/jpeg");
    protected $_height = null;
    protected $_width = null;
   
    public function setFile($file)
    {
        $this->_file =  $file;
        return $this;
    }
    
    public function getFile()
    {
        return $this->_file;
    }
    
    public function setHeight($height)
    {
        $this->_height =  $height;
        return $this;
    }
    
    public function getHeight()
    {
        return $this->_height;
    }
    
    public function setWidth($width)
    {
        $this->_width =  $width;
        return $this;
    }
    
    public function getWidth()
    {
        return $this->_width;
    }
    
    public function getUploader($file)
    {
        $image = new Ccc_Uploader_Image($file);
        return $image;
    }

    public function _setFileVariable($media)
    {
        $imagePath = Ccc::getBaseDir($this->getImagePath()).DS.$media->path;
        
        $file = array(
        'tmp_name'=>$imagePath, 
        'name' => basename($imagePath), 
        'error' => 0,  
        'type' => mime_content_type($imagePath),  
        'size' => filesize($imagePath)
        );

        $_FILES['image'] = $file;
        $this->setFile($file);
       
        return $file;
    }
    
    public function getImagePath()
    {
        return $this->_imagePath;
    }
    
    public function setImagePath($path)
    {
        if(!$path)
        {
            throw new Exception('path should not be null.');
        }

        $this->_imagePath = $this->_imagePath.DIRECTORY_SEPARATOR.$path;
        if(!is_dir($this->_imagePath))
        {
            if(!mkdir($this->_imagePath))
            {
                throw new Exception("Directory can not be created.");
            } 
        }
        
        if(!is_writable(Ccc::getBaseDir(str_replace('/', DIRECTORY_SEPARATOR, $this->_imagePath)))) 
        {
            throw new Exception('Destination folder is not writable or does not exists.');
        }
        
        return $this;
    }
    
    public function setImageName($name)
    {
        if(!$name)
        {
            throw new Exception('name should not be null.');
        }

        $this->_imageName = $name;
        return $this;
    }

    public function getImageName()
    {
        return $this->_imageName;
    }
    
    public function uploadImage()
    {
        $file = $this->getFile();
        
        $uploader = $this->getUploader($file);
        $uploader->setAllowedExtensions($this->_allowedExtensions);
        if(!$uploader->checkMimeType($this->_allowedMimeTypes))
        {
            throw new Exception("please upload File of type : ".implode(",", $this->_allowedExtensions).".");
        }
      
        $extension = end(explode('.',$file['name']));
        $fileName = $this->getImageName().".".$extension;  
        $imageData = $uploader->save($this->getImagePath(), $fileName);   
        $uploader->setPermission($fileName, 0777);
        
        if($this->getHeight() || $this->getWidth())
        {
            list($width, $height) = getimagesize($this->getImagePath().DS.$fileName);
            
            $uploader->setHeight($this->getHeight()); 
            $uploader->setWidth($this->getWidth());
            $uploader->setWidthExistImage($width);    
            $uploader->setHeightExistImage($height);    
            $uploader->createSelectedImage($this->getImagePath().DS.$fileName, $this->getImagePath().DS.$fileName);
            $uploader->setPermission($this->getImagePath().DS.$fileName, 0777);
        }
        
        return $fileName;
    }
}