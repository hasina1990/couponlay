<?php
class Page_View_Block_Index_Index extends Core_View_Block_Abstract
{
	public function __construct()
	{
		parent::__construct();
		$this->setTemplate('index/index.phtml');
	}  
    
    public function getPage()
    {
        $object = Zend_Controller_Front::getInstance();
        $pageId = (int)$object->getRequest()->getParam("page_id", null);
        if(!(int)$pageId)
        {
            throw new Exception("Invalid path");
        }
        
        $page = Ccc::getModel("page/page")->fetchRow("page_id = ".$pageId);
        if(!$page)
        {
            throw new Exception("Invalid path");
        }
        
        return $page;
    }
}
