<?php
class Admin_Category_IndexController extends Ccc_Controller_Action_Admin
{
    public function indexAction()
    {
        try
        {
            $this->_setTitle('Manage Category')->_setActiveTab('category');        
            $this->getResponse()->appendBody(Ccc::getBlock("admin/category_index_index")->toHtml());
            $this->getHelper('viewRenderer')->setNoRender(true);
        }
        catch(Exception $e)
        {
            
        }
    }
    
    public function indexJsonAction()
    {
        try
        {
            $response = array(
                'responseType'=>"success",
                'content'=>array(
                    array(
                        'element'=>'main-container',
                        'html'=>Ccc::getBlock("admin/category_index_index")->toHtml(),
                    )
                ),
                'message'=>''
            );
        }
        catch(Exception $e)
        {
            $response = array(
                'responseType'=>"failure",
                'message'=>$this->_getTranslate()->translate($e->getMessage())
            );
        }
        
        $this->getResponse()->appendBody(Zend_Json::encode($response));
        $this->getHelper('viewRenderer')->setNoRender(true);
        $this->getHelper('layout')->disableLayout();
    }
    
    public function resetAction()
    {   
        $this->getRequest()->setActionName('grid');
        Ccc::getModel('admin/search')->unsetSearch(); 
        
        $response = array(
            'responseType'    => "success",
            'message'        => '',
            'action'=>array(
                array(
                    'element'=>'main-container',
                    'url'=>$this->view->url(array("action"=>"grid", "page"=>1))
                )),
        );
        
        $this->getResponse()->appendBody(Zend_Json::encode($response));        
        $this->getHelper('viewRenderer')->setNoRender(true);
        $this->getHelper('layout')->disableLayout();       
    }
    
    public function gridAction()
    {
        try
        { 
            $response = array(
                'responseType'=>"success",
                'content'=>array(
                    array(
                        'element'=>'grid-list',
                        'html'=>Ccc::getBlock("admin/category_index_grid")->toHtml(),
                    )
                ),
                'message'=>''
            );
        }
        catch(Exception $e)
        {
            $response = array(
                'responseType'=>"failure",
                'message'=>$this->_getTranslate()->translate($e->getMessage())
            );
        }
        
        $this->getResponse()->appendBody(Zend_Json::encode($response));
        $this->getHelper('viewRenderer')->setNoRender(true);
        $this->getHelper('layout')->disableLayout();
    }
            
    public function createAction()
    {
        try
        { 
            $response = array(
                'responseType'=>"success",
                'content'=>array(
                    array(
                        'element'=>'main-container',
                        'html'=>Ccc::getBlock("admin/category_index_create")->toHtml(),
                    )
                ),
                'message'=>''
            );
        }
        catch(Exception $e)
        {
            $response = array(
                'responseType'=>"failure",
                'message'=>$this->_getTranslate()->translate($e->getMessage())
            );
        }
        
        $this->getResponse()->appendBody(Zend_Json::encode($response));
        $this->getHelper('viewRenderer')->setNoRender(true);
        $this->getHelper('layout')->disableLayout();
    }
    
    public function editAction()
    {
        try
        {
            $id = (int)$this->getRequest()->getParam('id', 0);  
            $category = Ccc::getModel("category/category")->find($id);
            if(!$category->valid())
            {
                throw new Ccc_Controller_Action_Admin_Exception($this->_getTranslate()->translate('Category ID is not valid.'));
            }
            
            $this->_forward('create');            
        }
        catch(Exception $e)
        {
            $response = array(
                'responseType'=>"failure",
                'message'=>$this->_getTranslate()->translate($e->getMessage())
            );
            
            $this->getResponse()->appendBody(Zend_Json::encode($response));
            $this->getHelper('viewRenderer')->setNoRender(true);
            $this->getHelper('layout')->disableLayout();
        }
    }
        
    public function saveAction()
    {
        try
        {
            if(!$this->getRequest()->isPost())
            {
                throw new Exception($this->_getTranslate()->translate('Invalid Request.'));
            }
            
            $postData = $this->getRequest()->getPost('category',array());
            if(!$postData)            
            {
                throw new Exception($this->_getTranslate()->translate('Invalid data posted.'));
            }
            
            $errors = array();
            
            $categoryModel = Ccc::getModel("category/category");
            if($id = $this->getRequest()->getParam('id', 0))
            {         
                $category = $categoryModel->find($id);
                if(!$category->valid())
                {
                    throw new Exception($this->_getTranslate()->translate('Invalid data posted.'));
                }
                $category = $category->current();
                
                $postData["updated_date"]  = date('Y-m-d H:i:s');
            }
            else
            {
                $category = $categoryModel->createRow();
                $postData["created_date"]  = date('Y-m-d H:i:s');
            }
            
            $category->setFromArray($postData);
            $category->name = trim($category->name);
            if(!$category->url_key)
            {
                $category->url_key = str_replace(" ", "-", strtolower($category->name));
            }
            
            $category->url_key = trim($category->url_key);
            
            if(!$category->validate())
            {
                $errors = $errors + $category->getErrorsWithColumn();
            } 
            
            if(count($errors))
            {
                throw new Ccc_Controller_Action_Json_Exception(json_encode($errors));
            }
            
            if($category->isDuplicateRecord())
            {
                throw new Ccc_Controller_Action_Json_Exception($this->_getTranslate()->translate(json_encode(array('name'=>'category with this name already exists.'))));
            }
            
            if($category->isDuplicateURLRecord())
            {
                throw new Ccc_Controller_Action_Json_Exception($this->_getTranslate()->translate(json_encode(array('url_key'=>'category with this URL already exists.'))));
            }
            
            $category->save();
            if(count($_FILES))
            {
                Ccc::getModel('category/uploader')->setCategory($category)->setFile($_FILES['image'])->saveCategoryImage();
            }
            
            $response = array(
                'responseType'=>"success",
                'message'=>$this->_getTranslate()->translate('Category was saved successfully.'),
                'action'=>array(
                    array(
                        'element'=>'main-container',
                        'url'=>$this->view->baseUrl('admin/category_index/index-json')
                    )),
            );
        }
        catch(Ccc_Controller_Action_Json_Exception $e)
        {
            $response = array(
                'responseType'=>"failure",
                'message'=>json_decode($e->getMessage())
            );    
        }
        catch(Exception $e)
        {
            $message = json_encode(array($this->_getTranslate()->translate($e->getMessage())));
            $response = array(
                'responseType'=>"failure",
                'message'=>$message
            );
        }
        
        $this->getResponse()->appendBody(Zend_Json::encode($response));        
        $this->getHelper('viewRenderer')->setNoRender(true);
        $this->getHelper('layout')->disableLayout();    
    }
        
    public function deleteAction()
    {
        try
        {
            if(!$this->getRequest()->isPost())
            {
                throw new Exception($this->_getTranslate()->translate('Invalid Request.'));
            }
            
            $postData = $this->getRequest()->getPost('selectedIds');
            if(!$postData)
            {
                throw new Exception($this->_getTranslate()->translate('Invalid data posted.'));
            }
            
            $ids = explode(',', $postData);
            if(!count($ids))
            {
                throw new Exception($this->_getTranslate()->translate('Please select atleast one record to delete.'));
            }
            
            $cnt = 0;
            foreach($ids as $_id)
            {
                $category = Ccc::getModel("category/category")->find($_id);
                if($category->valid())
                {
                    $category = $category->current();
                    
                    if($category->icon_url)
                    {
                    Ccc::getModel("category/uploader")->removeCategoryIcon($category->icon_url);
                    }
                    $category->delete();
                    $cnt++;
                }
            }
            
            $message  = $this->_getTranslate()->translate("%s Category(s) were deleted successfully.");
            
            $response = array(
                'responseType' => "success",
                'message' => $this->_getTranslate()->translate(sprintf($message, $cnt)),
                'action'=>array(
                    array(
                        'element'=>'main-container',
                        'url'=>$this->view->url(array("action"=>"grid"))
                    )),
            );
        }
        catch(Exception $e)
        {
            $response = array(
                'responseType'=>"failure",
                'message'=>$this->_getTranslate()->translate($e->getMessage()),
            );
        }
        $this->getResponse()->appendBody(Zend_Json::encode($response));
        $this->getHelper('viewRenderer')->setNoRender(true);
        $this->getHelper('layout')->disableLayout();
    }
}
