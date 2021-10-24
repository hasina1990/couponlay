<?php
class Admin_View_Block_Pages_Html_Header extends Admin_View_Block_Abstract
{
		
	public function __construct()
	{
		parent::__construct();
		$this->setTemplate('pages/html/header.phtml');
	}
	public function getLogoutUrl()
	{
		return $this->url(array('controller'=>'login', 'action'=>'process-logout'));
	}	
	
}
