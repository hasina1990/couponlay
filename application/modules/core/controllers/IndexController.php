<?php
class Core_IndexController extends Ccc_Controller_Action_Front
{
    protected function _authenticate()
    {
        return array('upload-image', 'upload-file');
    }
           
    public function uploadImageAction()
    {        
        $data = Ccc::getModel('core/editor')->setFile($_FILES['file'])->saveEditorImage();
        echo stripslashes(json_encode($data));
        $this->getHelper('viewRenderer')->setNoRender(true);
        $this->getHelper('layout')->disableLayout();
    }
    
    public function uploadFileAction()
    {
        $data = Ccc::getModel('core/editor')->setFile($_FILES['file'])->saveEditorFile();
        echo stripslashes(json_encode($data));
        $this->getHelper('viewRenderer')->setNoRender(true);
        $this->getHelper('layout')->disableLayout();
    }    
}
