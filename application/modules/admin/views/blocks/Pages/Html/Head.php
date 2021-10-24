<?php
class Admin_View_Block_Pages_Html_Head extends Admin_View_Block_Abstract
{
		
	public function __construct()
	{
		parent::__construct();
		$this->setTemplate('pages/html/head.phtml');
	}
    public function getTitle()
    {
        return $this->_getSession()->getSession('pageTitle');
    }	
	
}
