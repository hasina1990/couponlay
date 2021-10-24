<?php
class Admin_Model_Admin_Row extends Core_Model_Table_Row_Abstract
{
	protected $_tableClass = 'Admin_Model_Admin';
    protected $_validationClassModel = 'admin/admin_row_validation';
    protected $_resources = array(); 
    
   /* public function isMasterAdmin()
    {
        return $this->getTable()->isMasterAdmin();
    }*/
    /**
    *    Set New Password Function. This function is used to set new encrypted password.
    *    @param mixed $password.
    *    return password.
    **/
    public function setNewPassword($password = null)
    {
        if($password)
        {     
            $this->password = $this->getTable()->encryptPassword($password);
        }
        return $this;
    }
    
    /**
    *    Send New Password Mail Function. This function is used to send new password mail to User.
    *    return void.
    **/
    public function sendNewPasswordMail()
    {        
        return $this;
    }
    
    /**
    *    Is Duplicate Record Function. This function is used to check the record is Duplicate or not.
    *    return record.
    **/
    public function isDuplicateRecord()
    {
        $select = $this->select();    
        if($this->email)
        {
            $select->where('email = ?', $this->email);
        }
        
        if($this->admin_id)
        {
            $select->where('admin_id != ?', $this->admin_id);
        }
        
        return $this->getTable()->fetchRow($select);
    }
    
     public function setResources()
    {
        $this->_resources = Ccc::getModel('role/role')->getResourceByAdminRole($this);
        return $this;
    }
    
    public function getResources()
    {
        if(!$this->_resources)
        {
            $this->setResources();
        }
        return $this->_resources;
    }
}