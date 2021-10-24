<?php
class Admin_View_Block_System_Config_Group_Grid extends Admin_View_Block_Widget_Grid
{
    protected $_defaultSort     = 'system_config_group_id';
    protected $_config = null;               
//    protected $_defaultDir      = 'desc';
//    protected $_sortAction      = 'list';

    public function __construct()
    {
        parent::__construct();
        $this->setTemplate('system/config/group/grid.phtml');
    }
    
    protected function _prepareQuery()
    {
        $configModel = Ccc::getModel('config/system_config_group');
        $select         = $configModel->select();
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
        return Ccc::getModel("config/system_config_group")->fetchAll($select)->getAllIds();
    } 
    protected function _setFilter($select)
    {
        Ccc::getModel('admin/search')->setSearch();
        $timezoneOffset =  $this->_getTimezone()->getTimezoneOffset();
        
        if((int)Ccc::getHelper('admin/search')->search()->system_config_group_id_from)
        {
            Ccc::getHelper('admin/search')->search()->system_config_group_id_from = (int)Ccc::getHelper('admin/search')->search()->system_config_group_id_from;
            $select->where("system_config_group_id >= ?", Ccc::getHelper('admin/search')->search()->system_config_group_id_from);
        }
        if((int)Ccc::getHelper('admin/search')->search()->system_config_group_id_to)
        {
            Ccc::getHelper('admin/search')->search()->system_config_group_id_to=(int)Ccc::getHelper('admin/search')->search()->system_config_group_id_to;
            $select->where("system_config_group_id <= ?", Ccc::getHelper('admin/search')->search()->system_config_group_id_to);
        }
        if(Ccc::getHelper('admin/search')->search()->name)
        {
            $select->where("name Like ?", "%".Ccc::getHelper('admin/search')->search()->name."%");
        }
        if(Ccc::getHelper('admin/search')->search()->sort_order)
        {
            $select->where("sort_order = ?", Ccc::getHelper('admin/search')->search()->sort_order);
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
        return $select;
    }
    
    public function getResetUrl()
    {
        return $this->url(array('module'=>'admin','controller'=>'system_config_group','action'=>'reset','page'=>1),null,true);
    }
    
    public function getDeleteUrl()
    {
        return $this->url(array('action'=>'delete'));
    }
    
    public function getGridUrl()
    {
        return $this->url(array('action'=>'grid'));
    }
    
    public function getEditUrl($config)
    {
        return $this->url(array('action'=>'edit','id'=>$config->system_config_group_id));
    }
    
    public function getObject()
    {
        $object = Zend_Controller_Front::getInstance();
        return $object->getRequest()->getParam('obj');
    }
    
    /*public function system_Config($config = null)
    {
        if($config instanceof Config_Model_System_Config_Row)
        {
            $this->_config = $config;    
        }    
        return $this;
    }
    
    
    public function getFieldTypeOption()
    {
        $options = Ccc::getModel('config/system_config')->getFieldTypeOption();
        
        if($options && !$this->_config)
        {
            $options[0] = '';
            ksort($options);
        }
        
        return $options;
    }
    public function getOptionText()
    {
        $options = $this->getFieldTypeOption();
        
        if($options && $this->_config)
        {
            return (isset($options[$this->_config->field_type])) ? $options[$this->_config->field_type] : null;
        }
        return null;
    }
    public function getSystemGroupOptions($config = null)
    {
        $options = Ccc::getModel('admin/system_config_group')->getSystemGroups();
        if($options && !$this->_config)
        {
            $options[0] = '';
            ksort($options);
        }
        return $options;
    }*/
    /**
    *   This function return system group text.
    *   @return string
    */
    /*public function getSystemGroupText()
    {
        $options = $this->getSystemGroupOptions();
        
        if($options && $this->_config)
        {
            return (isset($options[$this->_config->system_config_group_id])) ? $options[$this->_config->system_config_group_id] : null;
        }
        
        return null;
    } */
}