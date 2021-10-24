<?php
class Admin_View_Block_Admin_Grid extends Admin_View_Block_Widget_Grid
{
    protected $_defaultSort     = 'admin_id';
    protected $_admin;

    public function __construct()
    {
        parent::__construct();
        $this->setTemplate('index/grid.phtml');
    }

    protected function _prepareQuery()
    {
        $adminModel = Ccc::getModel('admin/admin');
        $select     = $adminModel->select();
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
        $select = $this->_prepareQuery();
        return Ccc::getModel("admin/admin")->fetchAll($select)->getAllIds();
    }

    protected function _setFilter($select)
    {
        Ccc::getModel('admin/search')->setSearch();

        $adminModel = Ccc::getModel('admin/admin');
        $timezoneOffset =  $this->_getTimezone()->getTimezoneOffset();
         
        if((int)Ccc::getHelper('admin/search')->search()->admin_id_from)
        {
            Ccc::getHelper('admin/search')->search()->admin_id_from = (int)Ccc::getHelper('admin/search')->search()->admin_id_from;
            $select->where("admin_id >= ?", Ccc::getHelper('admin/search')->search()->admin_id_from);
        }
        if((int)Ccc::getHelper('admin/search')->search()->admin_id_to)
        {
            Ccc::getHelper('admin/search')->search()->admin_id_to=(int)Ccc::getHelper('admin/search')->search()->admin_id_to;
            $select->where("admin_id <= ?", Ccc::getHelper('admin/search')->search()->admin_id_to);
        }

        if(Ccc::getHelper('admin/search')->search()->first_name)
        {
            $select->where("first_name Like ?", "%".Ccc::getHelper('admin/search')->search()->first_name."%");
        }

        if(Ccc::getHelper('admin/search')->search()->last_name)
        {
            $select->where("last_name Like ?", "%".Ccc::getHelper('admin/search')->search()->last_name."%");
        }

        if(Ccc::getHelper('admin/search')->search()->created_date_from)
        {
            $select->where("DATE(created_date + INTERVAL {$timezoneOffset} SECOND) >= ?", date('Y-m-d', strtotime(Ccc::getHelper('admin/search')->search()->created_date_from)));
        }

        if(Ccc::getHelper('admin/search')->search()->created_date_to)
        {
            $select->where("DATE(created_date + INTERVAL {$timezoneOffset} SECOND) <= ?", date('Y-m-d', strtotime(Ccc::getHelper('admin/search')->search()->created_date_to)));
        }

        if(Ccc::getHelper('admin/search')->search()->updated_date_from)
        {
            $select->where("DATE(updated_date + INTERVAL {$timezoneOffset} SECOND) >= ?", date('Y-m-d', strtotime(Ccc::getHelper('admin/search')->search()->updated_date_from)));
        }

        if(Ccc::getHelper('admin/search')->search()->updated_date_to)
        {
            $select->where("DATE(updated_date + INTERVAL {$timezoneOffset} SECOND) <= ?", date('Y-m-d', strtotime(Ccc::getHelper('admin/search')->search()->updated_date_to)));
        }

        if(Ccc::getHelper('admin/search')->search()->email)
        {
            $select->where('email Like ?', "%".Ccc::getHelper('admin/search')->search()->email."%");
        }

        if(Ccc::getHelper('admin/search')->search()->is_enabled != null)
        {
            if(Ccc::getHelper('admin/search')->search()->is_enabled > 0)
            {
                $select->where('is_enabled = ?', Ccc::getHelper('admin/search')->search()->is_enabled);
            }
        }
       /* if(Ccc::getHelper('admin/search')->search()->role != null)
        {
            if(Ccc::getHelper('admin/search')->search()->role > 0)
            {
                $select->where('R.role_id = ?', Ccc::getHelper('admin/search')->search()->role);
            }
        }*/
       return $select;
    }

    public function getResetUrl()
    {
        return $this->url(array('module'=>'admin','controller'=>'index','action'=>'reset','page'=>1),null,true);
    }

    public function getDeleteUrl()
    {
        return $this->url(array('action'=>'delete'));
    }

    public function getGridUrl()
    {
        return $this->url(array('action'=>'grid'));
    }

    public function getEditUrl($admin)
    {
        return $this->url(array('action'=>'edit','id'=>$admin->admin_id));
    }
    public function admin($admin = null)
    {
        if($admin instanceof Admin_Model_Admin_Row)
        {
            $this->_admin = $admin;
        }                                            
        return $this;    
    }

    public function getIsEnabledOptions()
    {
        $options = Ccc::getModel('admin/admin_status')->getOptions();
        if($options && !$this->_admin)
        {
            $options[0] = '';
            ksort($options);
        }
        return $options;
    }

    public function getIsEnabledText()
    {
        $options = $this->getIsEnabledOptions();
        $text = null;
        if($options && $this->_admin)
        {
            $text = (array_key_exists($this->_admin->is_enabled, $options)) ? $options[$this->_admin->is_enabled] : null;            
        }
        return $text;    
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



    /*public function isMasterAdminRow($admin)
    {
        if(!$admin instanceof Admin_Model_Admin_Row)
        {
            throw new Exception("admin must be object of Admin_Model_Admin_Row");
        }

        if($admin->role_id == Role_Model_Role::ROLE_MASTER)
        {
            return true;
        }

        return false;
    }*/

    /*public function isMasterAdmin()
    {
    $adminModel = Ccc::getModel('admin/admin');
    return $adminModel->isMasterAdmin();
    }*/

    public function getLogin()
    {
        $loginModel = Ccc::getSingleton('admin/login');
        if($loginModel->isLoggedIn())
        {
            return $loginModel->getLogin();
        }

        return false;
    }

    public function getObject()
    {
        $object = Zend_Controller_Front::getInstance();
        return $object->getRequest()->getParam('obj');
    }


}
