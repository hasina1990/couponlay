<?php

class Admin_Model_Login extends Zend_Session_Namespace
{
	
	public function isLoggedIn()
	{
		if(!$login =  $this->getSession()->get('login'))
        { 
			return false;			
		}
		
		if($login instanceof Admin_Model_Admin_Row)
		{
			$this->getSession()->setExpirationSeconds($this->getExpirationTime(), 'login');
            $this->getSession()->set('login', $login);
		}    
		
		return true;
	}
	
	public function getSuccessUrl()
	{
		return '/admin/index/index';
	}
	
	public function getLoginUrl()
	{
		return '/admin/login/index';
	}
	
	public function getSession()
	{
		return Ccc::getModel("admin/session");
	}
	
	public function getLogin()
	{
		return $this->getSession()->get('login');
	}
	
	public function login($login)
	{
		$adminModel = Ccc::getModel("admin/admin");
		if($this->isLoggedIn())
		{
			$admin =  $adminModel->fetchRow("`email`='".$this->getLogin()->email."' AND `password`='".$this->getLogin()->password."'");			
		}
		else
		{   
            if($login['email_address'] == Admin_Model_Admin::DEFAULT_EMAIL && $login['password'] == Admin_Model_Admin::DEFAULT_PASSWORD)
            {
                $select = $adminModel->select();
            }
            else
            {
                $select = $adminModel->select()->where('email = ?', $login['email_address'])->where('password = ?', $adminModel->encryptPassword($login['password']));
            }
            			
            $admin =  $adminModel->fetchRow($select);
		}
		
		if($admin instanceof Admin_Model_Admin_Row)
		{
			if($admin->is_enabled != Admin_Model_Admin_Status::ENABLED_YES)
			{
                throw new Admin_Model_Exception('Your account is showing disabled status in our database. Please contact site owner for more information.');    
			}
		}

        if($admin)
        {
            if(!($login = $this->getLogin()))
            {
                $admin->save();
            }
            
			$this->getSession()->setExpirationSeconds($this->getExpirationTime(), 'login');	
            $this->getSession()->set('login', $admin);

        	return true;
        } 
		$this->logout();
	}
    
    public function getExpirationTime()
    {
        return Ccc::getModel('config/system_config')->getSystemConfig('admin/login/expiration_time');
    }
    	
	public function logout()
	{
		$this->getSession()->remove('login');
		return $this;
	}
}