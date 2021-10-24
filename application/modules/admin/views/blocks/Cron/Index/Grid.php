<?php
class Admin_View_Block_Cron_Index_Grid extends Admin_View_Block_Widget_Grid
{
    protected $_defaultSort     = 'created_at';
    protected $_option;
    protected $_cron;
	
	public function __construct()
    {
        parent::__construct();
        $this->setTemplate('cron/index/grid.phtml');
    }
	
    protected function _prepareQuery()
    {
        $cacheModel = Ccc::getModel('cron/cron');
        $select     = $cacheModel->select();
        $select     = $this->setOrder($select);
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
        return Ccc::getModel('cron/cron')->fetchAll($select)->getAllIds();
    }
    
    protected function _setFilter($select)
    {
        Ccc::getModel('admin/search')->setSearch();
        
        $timezoneOffset =  $this->_getTimezone()->getTimezoneOffset();
        
        if((int)Ccc::getHelper('admin/search')->search()->cron_id_from)
        {
            $select->where("cron_id >= ?", (int)Ccc::getHelper('admin/search')->search()->cron_id_from);
        }
        if((int)Ccc::getHelper('admin/search')->search()->cron_id_to)
        {
            $select->where("cron_id <= ?", (int)Ccc::getHelper('admin/search')->search()->cron_id_to);
        }
        if(Ccc::getHelper('admin/search')->search()->job_code)
        {
            $select->where("job_code LIKE '%".Ccc::getHelper('admin/search')->search()->job_code."%'");            
        }
        
        if(Ccc::getHelper('admin/search')->search()->model)
        {
            $select->where("model LIKE '%".Ccc::getHelper('admin/search')->search()->model."%'");
        }
        
        if(Ccc::getHelper('admin/search')->search()->description)
        {
            $select->where("description LIKE '%".Ccc::getHelper('admin/search')->search()->description."%'");
        }
        
        if(Ccc::getHelper('admin/search')->search()->cron_expr)
        {
           $select->where("cron_expr LIKE '%".Ccc::getHelper('admin/search')->search()->cron_expr."%'");
        }
        
        if(Ccc::getHelper('admin/search')->search()->status != null)
        {
            if(Ccc::getHelper('admin/search')->search()->status > 0)
            {
                $select->where('status = ?', Ccc::getHelper('admin/search')->search()->status);
            }
        }
        
        if(Ccc::getHelper('admin/search')->search()->created_date_from)
        {
           $select->where("DATE(created_at + INTERVAL {$timezoneOffset} SECOND) >= ?", Ccc::getHelper('admin/search')->search()->created_date_from);
        }
        
        if(Ccc::getHelper('admin/search')->search()->created_date_to)
        {
           $select->where("DATE(created_at + INTERVAL {$timezoneOffset} SECOND) <= ?", Ccc::getHelper('admin/search')->search()->created_date_to);
        }
        
        if(Ccc::getHelper('admin/search')->search()->updated_date_from)
        {
            $select->where("DATE(updated_at + INTERVAL {$timezoneOffset} SECOND) >= ?", Ccc::getHelper('admin/search')->search()->updated_date_from);
        }
        
        if(Ccc::getHelper('admin/search')->search()->updated_date_to)
        {
            $select->where("DATE(updated_at + INTERVAL {$timezoneOffset} SECOND) <= ?", Ccc::getHelper('admin/search')->search()->updated_date_to);
        }
        
        return $select;
    }    
    
    public function getResetUrl()
    {
        return $this->url(array('module'=>'admin','controller'=>'cron_index','action'=>'reset','page'=>1),null,true);
    }
    
    public function getGridUrl()
    {
        return $this->url(array('action'=>'grid'));
    }
    
    public function getDeleteUrl()
    {
        return $this->url(array('action'=>'delete'));
    }
    public function getEditUrl($cron)
    {
        return $this->url(array('action'=>'edit','id'=>$cron->cron_id));
    }   
    
    public function cron($cron = null)
    {
        if($cron instanceof Cron_Model_Cron_Row)
        {
            $this->_cron = $cron;
        }                                            
        return $this;    
    }
    
    public function getStatusOptions()
    {
        $options = Ccc::getModel('cron/cron_status')->getOptions();
        
        if($options && !$this->_cron)
        {
            $options[0] = '';
            ksort($options);
        }
        return $options;
    }
    
    public function getStatusText()
    {
        $options = Ccc::getModel('cron/cron_status')->getOptions();
        $text = null;
        if($options && $this->_cron)
        {
            $text = (array_key_exists($this->_cron->status, $options)) ? $options[$this->_cron->status] : null;            
        }
        return $text;    
    }      
}