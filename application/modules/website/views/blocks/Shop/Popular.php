<?php
class Website_View_Block_Shop_Popular extends User_View_Block_Widget_Grid
{   
	public function __construct()                     
	{
        parent::__construct();
		$this->setTemplate('shop/popular.phtml');
	}

    public function getWebsites()
    { 
        $websiteModel     = Ccc::getModel("website/website");
            
        $select = $websiteModel->select()
                                ->from(array("W"=>$websiteModel->getTableName()),array("website_id", "name"))
                                ->where("is_enabled = ?", Website_Model_Website::IS_ENABLED_YES)
                                ->order('W.view_count DESC')
                                ->limit(25);
       
        return $websiteModel->getAdapter()->fetchPairs($select);
    }
     
    public function getRequestObject()
    {
        $object = Zend_Controller_Front::getInstance();
        return $object;
    }   
}
