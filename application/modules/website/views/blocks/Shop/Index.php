<?php
class Website_View_Block_Shop_Index extends User_View_Block_Widget_Grid
{   
	public function __construct()                     
	{
        parent::__construct();
		$this->setTemplate('shop/index.phtml');
	}

    public function getWebsites($startingCharacter)
    { 
        $websiteModel     = Ccc::getModel("website/website");
            
        $select = $websiteModel->select()
                                ->from(array("W"=>$websiteModel->getTableName()),array("*"))
                                ->where("is_enabled = ?", Website_Model_Website::IS_ENABLED_YES)
                                ->where("W.name regexp '^[".$startingCharacter."]+'")
                                ->order('W.name Asc');
       
        return $websiteModel->fetchAll($select);
    }
     
    public function getRequestObject()
    {
        $object = Zend_Controller_Front::getInstance();
        return $object;
    }   
    
    public function isLetterSelected()
    {
        if($startingCharacter = $this->getRequestObject()->getRequest()->getParam("websiteStartCharacter", null))
        {
            return $startingCharacter;
        }
        
        return false;
    }
}
