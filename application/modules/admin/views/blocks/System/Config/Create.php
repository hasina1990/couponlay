<?php
class Admin_View_Block_System_Config_Create extends Admin_View_Block_Abstract
{ 
    protected $selectValues=null;
    
    public function __construct()
    {
        parent::__construct();
        $this->setTemplate('system/config/create.phtml');
    }
    
    public function getIndexJsonUrl()
    {
        return $this->url(array('action'=>'index-json','id'=>null));
    }
    
    public function getSaveUrl($config)
    {
        return $this->url(array('action'=>'save'));
    }
    
    public function getConfigData()
    {
        $object = Zend_Controller_Front::getInstance();
        if($id = (int)$object->getRequest()->getParam('id', 0))
        {
            $config = Ccc::getModel("config/system_config")->find($id);
            if(!$config->valid())
            {
                throw new Ccc_Controller_Action_Admin_Exception('Block ID is not valid.');
                //throw new Ccc_Controller_Action_Admin_Exception($this->_getTranslate()->translate('Block ID is not valid.'));
            }
            $config = $config->current();
            if($config->field_type == Config_Model_System_Config::FIELD_TYPE_SELECT_KEY)
            {
                $this->selectValues=json_decode($config->value);
            }
        }
        else
        {
            $config = Ccc::getModel("config/system_config")->createRow();
        }
        return $config;
    }
    public function getSelectValues()
    {
        return $this->selectValues;
    }
    public function getSystemGroupOptions()
    {
        return Ccc::getModel('config/system_config_group')->getSystemGroups();
    }
    public function getFieldTypeOption()
    {
        return Ccc::getModel('config/system_config')->getFieldTypeOption();
    }
    public function getBooleanValueOption()
    {
        return Ccc::getModel("config/system_config")->getBooleanValueOption();
    }
    
    public function getObject()
    {
        $object = Zend_Controller_Front::getInstance();
        return $object->getRequest()->getParam('obj');
    }
}
