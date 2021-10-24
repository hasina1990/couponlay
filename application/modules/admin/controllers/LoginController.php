<?php

class Admin_LoginController extends Ccc_Controller_Action_Admin
{
    public function indexAction()
    {           
		$this->_forward('login');
    }
	
	public function loginAction()
    {	                   
        $this->_setLayout('admin_login')->_setTitle('login');	
		$this->getResponse()->appendBody(Ccc::getBlock("admin/login")->toHtml());
        $this->getHelper('viewRenderer')->setNoRender(true);	
    }
	
	public function loginPostAction()
    {
		try
        {
            if(!$this->getRequest()->isPost())
            {
                throw new Exception($this->_getTranslate()->translate('Invalid request.'));
            }
            
            $login = $this->getRequest()->getParam('login', array());
            
            if(!$login)            
            {   
				throw new Exception($this->_getTranslate()->translate('Please enter valid email and password.'));				
            }
            
            if(!(isset($login['email_address']) && isset($login['password'])))
            {                
                throw new Exception($this->_getTranslate()->translate('Please enter valid email and password.'));
            }
            
			$loginModel = Ccc::getModel("admin/login");
            
            if(!$loginModel->login($login))
            {                
                throw new Exception($this->_getTranslate()->translate('Please enter valid email and password.'));
            }
			                                
           $response = array(
				'responseType'=>'success',
				'message'=>$this->_getTranslate()->translate("You are successfully logged in."),
                'redirectUrl'=>$this->getHelper("url")->url(array('controller'=>'dashboard', 'action'=>'index'))
			);
        }
        catch(Exception $e)
        {
            $response = array('responseType'=>'failure', 'message'=>$this->_getTranslate()->translate($e->getMessage()));
        }       
        
        $this->getResponse()->appendBody(Zend_Json::encode($response));
        $this->getHelper('viewRenderer')->setNoRender(true);
        $this->getHelper('layout')->disableLayout();
    }
    public function processLogoutAction()
    {
        if(Ccc::getModel("admin/login")->logout())
        {
            $this->_redirect('admin/');
        }    
    }
}