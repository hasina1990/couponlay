<?php
class Admin_Page_IndexController extends Ccc_Controller_Action_Admin
{
    public function indexAction()
    {
        try
        {
            $this->_setTitle('Manage Pages')->_setActiveTab('pages');        
            $this->getResponse()->appendBody(Ccc::getBlock("admin/page_index_index")->toHtml());
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
                        'html'=>Ccc::getBlock("admin/page_index_index")->toHtml(),
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
                        'html'=>Ccc::getBlock("admin/page_index_grid")->toHtml(),
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
                        'html'=>Ccc::getBlock("admin/page_index_create")->toHtml(),
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
            $page = Ccc::getModel("page/page")->find($id);
            if(!$page->valid())
            {
                throw new Ccc_Controller_Action_Admin_Exception($this->_getTranslate()->translate('Page ID is not valid.'));
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
            
            $postData = $this->getRequest()->getPost('page',array());
            if(!$postData)            
            {
                throw new Exception($this->_getTranslate()->translate('Invalid data posted.'));
            }
            
            $pageModel = Ccc::getModel("page/page");
            if($id = $this->getRequest()->getParam('id', 0))
            {         
                $page = $pageModel->find($id);
                if(!$page->valid())
                {
                    throw new Exception($this->_getTranslate()->translate('Invalid data posted.'));
                }
                
                $page = $page->current();
                $postData["updated_date"]  = date('Y-m-d H:i:s');
            }
            else
            {
                $page = $pageModel->createRow();
                $postData["created_date"]  = date('Y-m-d H:i:s');
            }
            
            $page->setFromArray($postData);
            
            $page->name = trim($page->name);
            $page->title = trim($page->title);
            if(!$page->url_key)
            {
                $page->url_key = str_replace(" ", "-", strtolower($page->title));
                $page->url_key = preg_replace('/[^A-Za-z0-9\-]/', '', $page->url_key);
            }
            
            $page->url_key = trim($page->url_key);
            
            if(!$page->validate())
            {
                throw new Ccc_Controller_Action_Json_Exception(json_encode($page->getErrorsWithColumn())); 
            } 
                              
            if($page->isDuplicateRecord())
            {
                throw new Ccc_Controller_Action_Json_Exception($this->_getTranslate()->translate(json_encode(array('name'=>'page with this name already exists.'))));
            }
            
            $page->save();
                           
            $response = array(
                'responseType'=>"success",
                'message'=>$this->_getTranslate()->translate('Page was saved successfully.'),
                'action'=>array(
                    array(
                        'element'=>'main-container',
                        'url'=>$this->view->baseUrl('admin/page_index/index-json')
                    )),
                'redirectURL' => $this->view->baseUrl('admin/page_index/index-json')  
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
                $page = Ccc::getModel("page/page")->find($_id);
                if($page->valid())
                {
                    $page = $page->current();
                    $page->delete();
                    $cnt++;
                }
            }
            
            $message  = $this->_getTranslate()->translate("%s Page(s) were deleted successfully.");
            
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
