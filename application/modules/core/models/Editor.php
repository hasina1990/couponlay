<?php
class Core_Model_Editor extends Core_Model_Uploader
{    
    const IMAGE_PATH = "editor";
    const IMAGE_PREFIX = "editor_";

    public function saveEditorImage()
    {        
        $this->setImagePath(Core_Model_Editor::IMAGE_PATH);
        $this->setImageName(Core_Model_Editor::IMAGE_PREFIX.time());
        $file = $this->uploadImage();
        
        $uploader = Ccc::getModel("core/editor");
        return array(
            "filelink" => Ccc::getModel("core/url")->getWebsiteUrl()."/".$uploader->getImagePath().Core_Model_Editor::IMAGE_PATH."/".$file
        );
    }
    
    public function saveEditorFile()
    {        
        $this->setImagePath(Core_Model_Editor::IMAGE_PATH);
        $this->setImageName(Core_Model_Editor::IMAGE_PREFIX.time());
        $file = $this->uploadFile();
        
        $uploader = Ccc::getModel("core/editor");
        return array(
            "filelink" => Ccc::getModel("core/url")->getWebsiteUrl()."/".$uploader->getImagePath().Core_Model_Editor::IMAGE_PATH."/".$file,
            "filename" => $file
        );
    }
    
    public function getFileUploader($file)
    {
        $image = new Ccc_Uploader($file);
        return $image;
    }
    
    public function uploadFile()
    {
        $file = $this->getFile();
        
        $uploader = $this->getFileUploader($file);
        
        $extension = end(explode('.',$file['name']));
        $fileName = $this->getImageName().".".$extension;  
        
        $imageData = $uploader->save($this->getImagePath(), $fileName);   
        $uploader->setPermission($fileName, 0777);

        return $fileName;
    }
}