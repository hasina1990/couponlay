<?php
class Admin_View_Block_Page_Index_Grid extends Admin_View_Block_Widget_Grid
{
    protected $_defaultSort     = 'P.page_id';
    
    public function __construct()
    {
        parent::__construct();
        $this->setTemplate('page/index/grid.phtml');
    }
    
    protected function _prepareQuery()
    {
        $pageModel          = Ccc::getModel('page/page');
        
        $select = $pageModel->select()
                                ->setIntegrityCheck(false)
                                ->from(array('P'=>$pageModel->getTableName()),array('*'));
        
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
        $pageModel          = Ccc::getModel('page/page');
        $select = $this->_prepareQuery();
        return $pageModel->fetchAll($select)->getAllIds();
    }
    
    protected function _setFilter($select)
    {
		Ccc::getModel('admin/search')->setSearch();
		
        if((int)Ccc::getHelper('admin/search')->search()->page_id_from)
        {
            $select->where("P.page_id >= ?", (int)Ccc::getHelper('admin/search')->search()->page_id_from);
        }
        if((int)Ccc::getHelper('admin/search')->search()->page_id_to)
        {
            $select->where("P.page_id <= ?", (int)Ccc::getHelper('admin/search')->search()->page_id_to);
        }
                
        if(Ccc::getHelper('admin/search')->search()->name = (string)trim(Ccc::getHelper('admin/search')->search()->name))
        {
            $select->where("P.name Like ?", "%".Ccc::getHelper('admin/search')->search()->name."%");
        }
        
        if(Ccc::getHelper('admin/search')->search()->title = (string)trim(Ccc::getHelper('admin/search')->search()->title))
        {
            $select->where("P.title Like ?", "%".Ccc::getHelper('admin/search')->search()->title."%");
        }
                               
        if(Ccc::getHelper('admin/search')->search()->created_date_from)
        {
            $select->where("DATE(P.created_date) >= ?", date('Y-m-d', strtotime(Ccc::getHelper('admin/search')->search()->created_date_from)));
        }
                 
        if(Ccc::getHelper('admin/search')->search()->created_date_to)
        {
            $select->where("DATE(P.created_date) <= ?", date('Y-m-d', strtotime(Ccc::getHelper('admin/search')->search()->created_date_to)));
        }
        
        if(Ccc::getHelper('admin/search')->search()->updated_date_from)
        {
            $select->where("DATE(P.updated_date) >= ?", date('Y-m-d', strtotime(Ccc::getHelper('admin/search')->search()->updated_date_from)));
        }
        
        if(Ccc::getHelper('admin/search')->search()->updated_date_to)
        {
            $select->where("DATE(P.updated_date) <= ?", date('Y-m-d', strtotime(Ccc::getHelper('admin/search')->search()->updated_date_to)));
        }
       
        if((int)Ccc::getHelper('admin/search')->search()->is_enabled)
        {
            $select->where("P.is_enabled = ?", (int)Ccc::getHelper('admin/search')->search()->is_enabled);
        }
        
        return $select;
    }
    
    public function getIsEnabled($page)
    {
        if($page->is_enabled == Page_Model_Page::IS_ENABLED_YES)
        {
            return Page_Model_Page::IS_ENABLED_YES_TEXT;
        }
        
        return Page_Model_Page::IS_ENABLED_NO_TEXT;
    }
    
    public function getIsEnabledOptions()
    {
        return array(
            0 => "",
            Page_Model_Page::IS_ENABLED_YES => Page_Model_Page::IS_ENABLED_YES_TEXT,
            Page_Model_Page::IS_ENABLED_NO => Page_Model_Page::IS_ENABLED_NO_TEXT
        );
    }
    
    public function getResetUrl()
    {
        return $this->url(array('module'=>'admin','controller'=>'page_index','action'=>'reset','page'=>1),null,true);
    }
           
    public function getDeleteUrl()
    {
        return $this->url(array('action'=>'delete'));
    }
    
    public function getGridUrl()
    {
        return $this->url(array('action'=>'grid'));
    }
  
    public function getEditUrl($page)
    {
        return $this->url(array('action'=>'edit','id'=>$page->page_id));
    }
}
