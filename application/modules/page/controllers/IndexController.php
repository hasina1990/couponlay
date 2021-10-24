<?php
class Page_IndexController extends Ccc_Controller_Action_Front
{
    protected function _authenticate()
    {
        return array('index');
    }
    
    public function preDispatch()
    {   
        parent::preDispatch();  
        $this->_setLayout('front');
    }
    
    public function indexAction()
    {
        try
        {
            $pageId = (int)$this->getRequest()->getParam("page_id", null);
            if(!(int)$pageId)
            {
                throw new Exception("Invalid path");
            }
            
            $page = Ccc::getModel("page/page")->fetchRow("page_id = ".$pageId);
            if(!$page)
            {
                throw new Exception("Invalid path");
            }
        }
        catch(Exception $e)
        {
            throw new Exception($e->getMessage());
        }
        $this->setTitle($page->title);
        $this->view->page = $page;
        $this->getResponse()->appendBody(Ccc::getBlock("page/index_index")->toHtml());
        $this->getHelper('viewRenderer')->setNoRender(true);   
    }    
}
