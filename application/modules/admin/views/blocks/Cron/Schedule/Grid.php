<?php
class Admin_View_Block_Cron_Schedule_Grid extends Admin_View_Block_Widget_Grid
{
    protected $_defaultSort     = 'CSM.schedule_id';
    protected $_option;
	
	public function __construct()
    {
        parent::__construct();
        $this->setTemplate('cron/schedule/grid.phtml');
    }
	
    protected function _prepareQuery()
    {
        $cronModel = Ccc::getModel("cron/cron");
        $cronScheduleModel = Ccc::getModel("cron/cron_schedule");
        
        $select = $cronScheduleModel->select()->from(array('CSM'=>$cronScheduleModel->getTableName()))
                                    ->setIntegrityCheck(false) 
                                    ->join(array('CM'=>$cronModel->getTableName()),'`CM`.`cron_id`=`CSM`.`cron_id`',array('job_code'=>'CM.job_code'));
        
        $select = $this->setOrder($select);
        $select = $this->_setFilter($select);
        return $select;  
    }
                              
    public function getCollection()
    {
        $select = $this->_prepareQuery();
        $paginator = Zend_Paginator::factory($select);
        $paginator->setCurrentPageNumber($this->getCurrentPage());
        $paginator->setDefaultItemCountPerPage($this->getRecordPerPage());
        $profiles = $paginator->setPageRange($this->getPageRange());
        
        return $profiles;
    }
    
    public function getAllIds()
    {
        $model = Ccc::getModel("cron/cron_schedule");
        $select = $this->_prepareQuery();
        return $model->fetchAll($select)->getAllIds();
    }
    
    protected function _setFilter($select)
    {
        Ccc::getModel('admin/search')->setSearch();
        
        $timezoneOffset =  $this->_getTimezone()->getTimezoneOffset();
        
        if((int)Ccc::getHelper('admin/search')->search()->cron_schedule_id_from)
        {
            $select->where("CSM.cron_schedule_id >= ?", (int)Ccc::getHelper('admin/search')->search()->cron_schedule_id_from);
        }
        if((int)Ccc::getHelper('admin/search')->search()->cron_id_to)
        {
            $select->where("CSM.cron_schedule_id <= ?", (int)Ccc::getHelper('admin/search')->search()->cron_schedule_id_to);
        }
        if(Ccc::getHelper('admin/search')->search()->job_code)
        {
            $select->where("CM.job_code LIKE '%".Ccc::getHelper('admin/search')->search()->job_code."%'");            
        }
        
        if(Ccc::getHelper('admin/search')->search()->status)
        {
            $select->where("CSM.status LIKE '%".Ccc::getHelper('admin/search')->search()->status."%'");
        }
        
        if(Ccc::getHelper('admin/search')->search()->messages)
        {
            $select->where("messages LIKE '%".Ccc::getHelper('admin/search')->search()->messages."%'");
        }
        
        if(Ccc::getHelper('admin/search')->search()->schedule_at_from)
        {
           $select->where("DATE(CSM.schedule_at + INTERVAL {$timezoneOffset} SECOND) >= ?", Ccc::getHelper('admin/search')->search()->schedule_at_from);
        }
        
        if(Ccc::getHelper('admin/search')->search()->schedule_at_to)
        {
           $select->where("DATE(CSM.schedule_at + INTERVAL {$timezoneOffset} SECOND) <= ?", Ccc::getHelper('admin/search')->search()->schedule_at_to);
        }
        
        if(Ccc::getHelper('admin/search')->search()->executed_at_from)
        {
           $select->where("DATE(CSM.executed_at + INTERVAL {$timezoneOffset} SECOND) >= ?", Ccc::getHelper('admin/search')->search()->executed_at_from);
        }
        
        if(Ccc::getHelper('admin/search')->search()->executed_at_to)
        {
           $select->where("DATE(CSM.executed_at + INTERVAL {$timezoneOffset} SECOND) <= ?", Ccc::getHelper('admin/search')->search()->executed_at_to);
        }
        
        if(Ccc::getHelper('admin/search')->search()->finished_at_from)
        {
            $select->where("DATE(CSM.finished_at + INTERVAL {$timezoneOffset} SECOND) >= ?", Ccc::getHelper('admin/search')->search()->finished_at_from);
        }
        
        if(Ccc::getHelper('admin/search')->search()->finished_at_to)
        {
            $select->where("DATE(CSM.finished_at + INTERVAL {$timezoneOffset} SECOND) <= ?", Ccc::getHelper('admin/search')->search()->finished_at_to);
        }
        
        if(Ccc::getHelper('admin/search')->search()->created_at_from)
        {
            $select->where("DATE(CSM.created_at + INTERVAL {$timezoneOffset} SECOND) >= ?", Ccc::getHelper('admin/search')->search()->created_at_from);
        }
        
        if(Ccc::getHelper('admin/search')->search()->created_at_to)
        {
            $select->where("DATE(CSM.created_at + INTERVAL {$timezoneOffset} SECOND) <= ?", Ccc::getHelper('admin/search')->search()->created_at_to);
        }
        
        return $select;
    }    
    
    public function getResetUrl()
    {
        return $this->url(array('module'=>'admin','controller'=>'cron_schedule','action'=>'reset','page'=>1),null,true);
    }
    
    public function getGridUrl()
    {
        return $this->url(array('action'=>'grid'));
    }
    
    public function getDeleteUrl()
    {
        return $this->url(array('action'=>'delete'));
    }
}