<?php
class Admin_View_Block_System_Config_Group_Create extends Admin_View_Block_Abstract
{
    public function __construct()
    {
        parent::__construct();
        $this->setTemplate('system/config/group/create.phtml');
    }
    
    public function getIndexJsonUrl()
    {
        return $this->url(array('action'=>'index-json','id'=>null));
    }
    
    public function getSaveUrl($config)
    {
        return $this->url(array('action'=>'save'));
    }
    
    public function getSystemConfigGroupData()
    {
        $object = Zend_Controller_Front::getInstance();
        if($id = (int)$object->getRequest()->getParam('id', 0))
        {
            $group = Ccc::getModel("config/system_config_group")->find($id);
            if(!$group->valid())
            {
                throw new Exception($this->_getTranslate()->translate('Record ID is not valid.'));
            }
            $group = $group->current();
        }
        else
        {
            $group = Ccc::getModel("config/system_config_group")->createRow();
        }
        return $group;
    }
    
    public function getObject()
    {
        $object = Zend_Controller_Front::getInstance();
        return $object->getRequest()->getParam('obj');
    }
}
