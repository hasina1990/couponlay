<?php
class Admin_View_Block_Pages_Html_Container extends Admin_View_Block_Abstract
{   
	public function __construct()
	{
		parent::__construct();
		$this->setTemplate('pages/html/container.phtml');
	}
}
