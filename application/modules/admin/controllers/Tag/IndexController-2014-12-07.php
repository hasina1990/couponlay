<?php
class Admin_Tag_IndexController extends Ccc_Controller_Action_Admin
{
    public function indexAction()
    {
        try
        {
            $this->_setTitle('Manage Tags')->_setActiveTab('tag');        
            $this->getResponse()->appendBody(Ccc::getBlock("admin/tag_index_index")->toHtml());
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
                        'html'=>Ccc::getBlock("admin/tag_index_index")->toHtml(),
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
                        'html'=>Ccc::getBlock("admin/tag_index_grid")->toHtml(),
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
                        'html'=>Ccc::getBlock("admin/tag_index_create")->toHtml(),
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
            $tag = Ccc::getModel("tag/tag")->find($id);
            if(!$tag->valid())
            {
                throw new Ccc_Controller_Action_Admin_Exception($this->_getTranslate()->translate('Tag ID is not valid.'));
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
            
            $postData = $this->getRequest()->getPost('tag',array());
            if(!$postData)            
            {
                throw new Exception($this->_getTranslate()->translate('Invalid data posted.'));
            }
            
            $errors = array();
            
            $tagModel = Ccc::getModel("tag/tag");
            if($id = $this->getRequest()->getParam('id', 0))
            {         
                $tag = $tagModel->find($id);
                if(!$tag->valid())
                {
                    throw new Exception($this->_getTranslate()->translate('Invalid data posted.'));
                }
                $tag = $tag->current();
                
                $postData["updated_date"]  = date('Y-m-d H:i:s');
            }
            else
            {
                $tag = $tagModel->createRow();
                $postData["created_date"]  = date('Y-m-d H:i:s');
            }
           
            $tag->setFromArray($postData);
            $tag->name = trim($tag->name);
            if(!$tag->url_key)
            {
                $tag->url_key = str_replace(" ", "-", strtolower($tag->name));
            }
            
            $tag->url_key = trim($tag->url_key);
            
            if(!$tag->validate())
            {
                $errors = $errors + $tag->getErrorsWithColumn();
            } 
            
            if(count($errors))
            {
                throw new Ccc_Controller_Action_Json_Exception(json_encode($errors));
            }
            
            if($tag->isDuplicateRecord())
            {
                throw new Ccc_Controller_Action_Json_Exception($this->_getTranslate()->translate(json_encode(array('name'=>'tag with this name already exists.'))));
            }
            
            if($tag->isDuplicateURLRecord())
            {
                throw new Ccc_Controller_Action_Json_Exception($this->_getTranslate()->translate(json_encode(array('url_key'=>'tag with this URL already exists.'))));
            }
            
            $tag->save();
            
            $response = array(
                'responseType'=>"success",
                'message'=>$this->_getTranslate()->translate('Tag was saved successfully.'),
                'action'=>array(
                    array(
                        'element'=>'main-container',
                        'url'=>$this->view->baseUrl('admin/tag_index/index-json')
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
                $tag = Ccc::getModel("tag/tag")->find($_id);
                if($tag->valid())
                {
                    $tag = $tag->current();
                    $tag->delete();
                    $cnt++;
                }
            }
            
            $message  = $this->_getTranslate()->translate("%s Tag(s) were deleted successfully.");
            
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
