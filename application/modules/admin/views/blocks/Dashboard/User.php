<?php
class Admin_View_Block_Dashboard_User extends Admin_View_Block_Widget_Grid
{
    public function __construct()
    {
        parent::__construct();
        $this->setTemplate('dashboard/user.phtml');
    }
    
    protected function _prepareQuery()
    {
        $userModel          = Ccc::getModel('user/user');
        
        $select = $userModel->select()
                                ->setIntegrityCheck(false)
                                ->from(array('U'=>$userModel->getTableName()),array('*'))
                                ->order("created_date DESC");
        
        return $select;
    }
    
    public function getCollection()
    {
        $select = $this->_prepareQuery();
        $select->limit(5);  
        return Ccc::getModel('user/user')->fetchAll($select);
    }
    
    public function getTotalUserCount()
    {
        $select = $this->_prepareQuery();
        return count(Ccc::getModel('user/user')->getAdapter()->fetchCol($select));
    }
    
    public function getIsEnabled($user)
    {
        if($user->is_enabled == User_Model_User::IS_ENABLED_YES)
        {
            return User_Model_User::IS_ENABLED_YES_TEXT;
        }
        
        return User_Model_User::IS_ENABLED_NO_TEXT;
    }
    
    public function getManageSubscribeUserUrl()
    {
        return $this->url(array('module'=>'admin','controller'=>'user_index','action'=>'index-json','page'=>1),null,true);
    }
}
