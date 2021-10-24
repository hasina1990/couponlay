<?php
class Admin_View_Block_Login extends  Admin_View_Block_Abstract
{
	public function __construct()
    {
        parent::__construct();
        $this->setTemplate('login/login.phtml');
    }
	
}