<?php 
class Admin_IndexController extends Ccc_Controller_Action_Admin
{
    public function indexAction()
    {                              
        $this->_setTitle('Manage Admins')->_setActiveTab('system');
        $this->getResponse()->appendBody(Ccc::getBlock("admin/admin_index")->toHtml());
        $this->getHelper('viewRenderer')->setNoRender(true);
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
						'html'=>Ccc::getBlock('admin/admin_index')->toHtml()
					)
                )
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
                        'html'=>Ccc::getBlock('admin/admin_grid')->toHtml()
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
            $this->view->login_id=Ccc::getSingleton('admin/login')->getLogin()->admin_id;
            $response = array(
                'responseType'=>"success",
                'content'=>array(
                    array(
                        'element'=>'main-container',
                        'html'=>Ccc::getBlock('admin/admin_create')->toHtml(),
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
            $admin = Ccc::getModel("admin/admin")->find($id);
            if(!$admin->valid())
            {
                throw new Ccc_Controller_Action_Admin_Exception($this->_getTranslate()->translate('Record ID is not valid.'));
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
            
            $postData  = $this->getRequest()->getPost('admin', array());
            if(!$postData)
            {
                throw new Exception($this->_getTranslate()->translate('Invalid data posted.'));
            }
               
            $adminModel = Ccc::getModel("admin/admin");
            if($id = $this->getRequest()->getParam('id', 0))
            {
                $admin = $adminModel->find($id);        
                if(!$admin->valid())
                {
                    throw new Exception($this->_getTranslate()->translate('Invalid data posted.'));
                }
                if($postData['password'] == NULL || $postData['password'] == '')            
                {
                    unset($postData['password']);
                }
                $admin = $admin->current();                
                $postData['updated_date'] = date("Y-m-d H:i:s");
            }
            else
            {
                $admin = $adminModel->createRow();
                $postData['created_date'] = date("Y-m-d H:i:s");
            }
            if($id)
            {
                if(isset($postData['password']))
                {
                    if(!$adminModel->validatePassword($postData['password']))
                    {
                        throw new Ccc_Controller_Action_Json_Exception($this->_getTranslate()->translate(json_encode(array('password'=>'Password must have length between 6 to 20.'))));
                    }
                }
            }
            else
            {
                if(!$adminModel->validatePassword($postData['password']))
                {
                    throw new Ccc_Controller_Action_Json_Exception($this->_getTranslate()->translate(json_encode(array('password'=>'Password must have length between 6 to 20.'))));
                }
            }
            $admin->setFromArray($postData);            
            
            if(!$admin->validate())
            {
                throw new Ccc_Controller_Action_Json_Exception(($admin->getErrorsWithColumnInJsonFormat()));
            } 
            
            if(isset($postData['password']))
            {
                $admin->setNewPassword($postData['password']);
            }
            
            if($admin->isDuplicateRecord())
            {
                throw new Ccc_Controller_Action_Json_Exception($this->_getTranslate()->translate(json_encode(array('email'=>'Admin with this email address already exists.'))));
            }
            
            $admin->save();  
            $response = array(
                'responseType'=>"success",
                'message'=>$this->_getTranslate()->translate('Admin was saved successfully.'),
                'action'=>array(
                    array(
                        'element'=>'main-container',
                        'url'=>$this->view->baseUrl('admin/index/index-json')
                    )),
            );
        }
        catch(Ccc_Controller_Action_Json_Exception $e)
        {
            $response = array(
                'responseType'=>"failure",
                'message'=>json_decode($this->_getTranslate()->translate($e->getMessage()),1)
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
            
            $ids = explode(',',$postData);   
            if(!count($ids))
            {
                throw new Exception($this->_getTranslate()->translate('Please select atleast one record to delete.'));
            }
            
            $cnt = 0;
            
            $loginModel = Ccc::getSingleton('admin/login');
            
            if($loginModel->isLoggedIn())
            {
                $login_id=$loginModel->getLogin()->admin_id;
            
                foreach($ids as $_id)
                {
                    if($_id!=$login_id)
                    {
                        $config = Ccc::getModel("admin/admin")->find($_id);
                        if($config->valid())
                        {
                            $config->current()->delete();
                            $cnt++;        
                        }
                    }
                }
            }            
            $message = "%s Admin(s) were deleted successfully.";
            
            $response = array(
                'responseType'    => "success",
                'message'        => $this->_getTranslate()->translate(sprintf($message, $cnt)),
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
                'message'=>$this->_getTranslate()->translate($e->getMessage())
            );
        }
        
        $this->getResponse()->appendBody(Zend_Json::encode($response));
        $this->getHelper('viewRenderer')->setNoRender(true);
        $this->getHelper('layout')->disableLayout();
    }
}