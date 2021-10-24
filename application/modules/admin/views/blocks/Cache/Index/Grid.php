<?php
class Admin_View_Block_Cache_Index_Grid extends Admin_View_Block_Widget_Grid
{
    protected $_defaultSort     = 'cache_id';
    
    public function __construct()
    {
        parent::__construct();
        $this->setTemplate('cache/index/grid.phtml');
    }
    
    protected function _prepareQuery()
    {
        $cacheModel = Ccc::getModel('cache/cache');
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
        return Ccc::getModel('cache/cache')->fetchAll($select)->getAllIds();
    }
    protected function _setFilter($select)
    {   
        Ccc::getModel('admin/search')->setSearch();
        
        $timezoneOffset =  $this->_getTimezone()->getTimezoneOffset();
        if((int)Ccc::getHelper('admin/search')->search()->cache_id_from)
        {
            Ccc::getHelper('admin/search')->search()->cache_id_from = (int)Ccc::getHelper('admin/search')->search()->cache_id_from;
            $select->where("cache_id >= ?", Ccc::getHelper('admin/search')->search()->cache_id_from);
        }
        if((int)Ccc::getHelper('admin/search')->search()->cache_id_to)
        {
            Ccc::getHelper('admin/search')->search()->cache_id_to=(int)Ccc::getHelper('admin/search')->search()->cache_id_to;
            $select->where("cache_id <= ?", Ccc::getHelper('admin/search')->search()->cache_id_to);
        }
        if(Ccc::getHelper('admin/search')->search()->name)
        {
            $select->having("name LIKE ?", "%".Ccc::getHelper('admin/search')->search()->name."%");
        }
        
        if(Ccc::getHelper('admin/search')->search()->prefix)
        {
            $select->having("prefix LIKE ?", "%".Ccc::getHelper('admin/search')->search()->prefix."%");
        }
        
        if(Ccc::getHelper('admin/search')->search()->lifetime)
        {
            $select->where("lifetime = ?", Ccc::getHelper('admin/search')->search()->lifetime);
        }
        
        if(Ccc::getHelper('admin/search')->search()->code)
        {
            $select->having("code LIKE ?", "%".Ccc::getHelper('admin/search')->search()->code."%");
        }
        if(Ccc::getHelper('admin/search')->search()->tag)
        {
            $select->having("tag LIKE ?", "%".Ccc::getHelper('admin/search')->search()->tag."%");
        }
        
        if(Ccc::getHelper('admin/search')->search()->created_from_date)
        {
            $select->where("DATE(created_date + INTERVAL {$timezoneOffset} SECOND) >= ?", Ccc::getHelper('admin/search')->search()->created_from_date);
        }
                 
        if(Ccc::getHelper('admin/search')->search()->created_to_date)
        {
            $select->where("DATE(created_date + INTERVAL {$timezoneOffset} SECOND) <= ?", Ccc::getHelper('admin/search')->search()->created_to_date);
        }
        
        if(Ccc::getHelper('admin/search')->search()->updated_from_date)
        {
            $select->where("DATE(updated_date + INTERVAL {$timezoneOffset} SECOND) >= ?", Ccc::getHelper('admin/search')->search()->updated_from_date);
        }
        
        if(Ccc::getHelper('admin/search')->search()->updated_to_date)
        {
            $select->where("DATE(updated_date + INTERVAL {$timezoneOffset} SECOND) <= ?", Ccc::getHelper('admin/search')->search()->updated_to_date);
        }
        return $select;
    }
    
    public function getResetUrl()
    {
        return $this->url(array('module'=>'admin','controller'=>'cache_index','action'=>'reset','page'=>1),null,true);
    }
    
    public function getCleanUrl()
    {
        return $this->url(array('action'=>'clean'));
    }
    
    public function getGridUrl()
    {
        return $this->url(array('action'=>'grid'));
    }
    
    public function getEditUrl($cache)
    {
        return $this->url(array('action'=>'edit','id'=>$cache->cache_id));
    }     
    public function getIsEnabledOptions()
    {
        return Ccc::getModel('cache/cache_status')->getOptions();
    }
    
    public function getObject()
    {
        $object = Zend_Controller_Front::getInstance();
        return $object->getRequest()->getParam('obj');
    }
}
