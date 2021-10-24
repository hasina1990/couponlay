<?php
class Admin_View_Block_Admin_Create extends Admin_View_Block_Abstract
{
    public function __construct()
    {
        parent::__construct();
        $this->setTemplate('index/create.phtml');
    }
    
    public function getIndexJsonUrl()
    {
        return $this->url(array('action'=>'index-json','id'=>null));
    }
    
    public function getSaveUrl($admin)
    {
        return $this->url(array('action'=>'save'));
    }
    
    public function getAdminData()
    {
        $object = Zend_Controller_Front::getInstance();
        if($id = (int)$object->getRequest()->getParam('id', 0))
        {   
            $admin = Ccc::getModel("admin/admin")->find($id);
            if(!$admin->valid())
            {
                throw new Ccc_Controller_Action_Admin_Exception($this->_getTranslate()->translate('Block ID is not valid.'));
            }
            $admin = $admin->current();
        }
        else
        {
            $admin = Ccc::getModel("admin/admin")->createRow();
        }
        
        return $admin;
    }
    /*public function getAdminTypeOption()
    {
        return Ccc::getModel('admin/admin')->getAdminTypeOptions();    
    }*/
    /**
    *   Get Is Enabled Options Function. Used to get options that Admin is enabled or not by calling getIsEnabledOptions from admin model of admin module.
    *   @return options.
    **/
    public function getIsEnabledOptions()
    {
        return Ccc::getModel('admin/admin_status')->getOptions();
    }
    
    public function getLoginId()
    {
        return Ccc::getSingleton('admin/login')->getLogin()->admin_id;
    }
    
    /*public function getAllRole()
    {
        $roles = Ccc::getModel('role/role')->fetchAll();
        
        $options = array('');
        foreach($roles as $_role)
        {
            $options[$_role->role_id] = $_role->role_name;
        }
        
        return $options;
    }*/
    
    public function getObject()
    {
        $object = Zend_Controller_Front::getInstance();
        return $object->getRequest()->getParam('obj');
    }
}
