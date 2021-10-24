<?php
class User_View_Block_Pages_Html_Menu extends Core_View_Block_Abstract
{
    public function __construct()
    {
        parent::__construct();
        $this->setTemplate('pages/html/menu.phtml');
    }
    
    public function getPropertyGroups()
    {
        $propertyGroupModel = Ccc::getModel("property/group");
        $select = $propertyGroupModel->select()
                           ->from(array($propertyGroupModel->getTableName()),array("property_group_id", "name"))
                           ->where("is_service = ?", Property_Model_Group::IS_SERVICE_NO)
                           ->limit(6);
        
        return $propertyGroupModel->getAdapter()->fetchPairs($select);
    }
   
    public function getPropertyGroupViewUrl($propertyGroupId)
    {
        if(!(int)$propertyGroupId)
        {
            return "#";
        }
        
        return $this->baseUrl("property/index/index/id/".(int)$propertyGroupId);
    }
    
    public function getServicePropertyUrl()
    {
        return $this->baseUrl("property/service/index/");
    }
    
    public function getSelectedProeprtyGroup()
    {
        $object = Zend_Controller_Front::getInstance();
        
        if($object->getRequest()->getActionName() == "index" && $object->getRequest()->getControllerName() == "index" && $object->getRequest()->getModuleName() == "property")
        {
            return (int)$object->getRequest()->getParam("id", 0);
        }
        return 0; 
    }
    
    public function getAgentUrl()
    {
        return $this->baseUrl('agent');
    }
    
    public function getBuilderUrl()
    {
        return $this->baseUrl('builder');
    }
    
    public function getInvestorUrl()
    {
        return $this->baseUrl('investor');
    }
}
