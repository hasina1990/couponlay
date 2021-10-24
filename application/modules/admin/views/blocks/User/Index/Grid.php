<?php
class Admin_View_Block_User_Index_Grid extends Admin_View_Block_Widget_Grid
{
    protected $_defaultSort     = 'U.user_id';
    
    public function __construct()
    {
        parent::__construct();
        $this->setTemplate('user/index/grid.phtml');
    }
    
    protected function _prepareQuery()
    {
        $userModel          = Ccc::getModel('user/user');
        
        $select = $userModel->select()
                                ->setIntegrityCheck(false)
                                ->from(array('U'=>$userModel->getTableName()),array('*'));
        
        $select         = $this->setOrder($select);
        $select         = $this->_setFilter($select);
        return $select;
    }
    
    public function getCollection()
    {
        $select = $this->_prepareQuery();
        $paginator = Zend_Paginator::factory($select);
        $paginator->setCurrentPageNumber($this->getCurrentPage());
        $paginator->setDefaultItemCountPerPage($this->getRecordPerPage());
        return $paginator->setPageRange($this->getPageRange());
    }
    
    public function getAllIds()
    {
        $userModel          = Ccc::getModel('user/user');
        $select = $this->_prepareQuery();
        return $userModel->fetchAll($select)->getAllIds();
    }
    
    protected function _setFilter($select)
    {
		Ccc::getModel('admin/search')->setSearch();
		
        if((int)Ccc::getHelper('admin/search')->search()->user_id_from)
        {
            $select->where("U.user_id >= ?", (int)Ccc::getHelper('admin/search')->search()->user_id_from);
        }
        if((int)Ccc::getHelper('admin/search')->search()->user_id_to)
        {
            $select->where("U.user_id <= ?", (int)Ccc::getHelper('admin/search')->search()->user_id_to);
        }
                
        if(Ccc::getHelper('admin/search')->search()->email = (string)trim(Ccc::getHelper('admin/search')->search()->email))
        {
            $select->where("U.email Like ?", "%".Ccc::getHelper('admin/search')->search()->email."%");
        }
                            
        if((int)Ccc::getHelper('admin/search')->search()->is_enabled)
        {
            $select->where("U.is_enabled = ?", (int)Ccc::getHelper('admin/search')->search()->is_enabled);
        }
        
        return $select;
    }
    
    public function getIsEnabled($user)
    {
        if($user->is_enabled == User_Model_User::IS_ENABLED_YES)
        {
            return User_Model_User::IS_ENABLED_YES_TEXT;
        }
        
        return User_Model_User::IS_ENABLED_NO_TEXT;
    }
    
    public function getIsEnabledOptions()
    {
        return array(
            0 => "",
            User_Model_User::IS_ENABLED_YES => User_Model_User::IS_ENABLED_YES_TEXT,
            User_Model_User::IS_ENABLED_NO => User_Model_User::IS_ENABLED_NO_TEXT
        );
    }
    
    public function getResetUrl()
    {
        return $this->url(array('module'=>'admin','controller'=>'user_index','action'=>'reset','page'=>1),null,true);
    }
                
    public function getGridUrl()
    {
        return $this->url(array('action'=>'grid'));
    }
}
