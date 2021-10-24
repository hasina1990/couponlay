<?php
class Admin_View_Block_Tag_Website_Grid extends Admin_View_Block_Widget_Grid
{
    protected $_defaultSort     = 'W.website_id';
    protected $_tag = null;
    
    public function __construct()
    {
        parent::__construct();
        $this->setTemplate('tag/website/grid.phtml');
    }
    
    protected function _prepareQuery()
    {
        $websiteModel     = Ccc::getModel('website/website');
        
        $select = $websiteModel->select()
                                ->setIntegrityCheck(false)
                                ->from(array('W'=>$websiteModel->getTableName()), array('*'));
        
        $select         = $this->setOrder($select);
        $select         = $this->_setFilter($select);
        
        return $select;
    }
    
    public function getTag()
    {
        if(!$this->_tag)
        {
            if(!$id = (int)$this->getRequest()->getParam("id", 0))
            {
                throw new Exception("Tag is not valid.");
            }
            
            $tag   = Ccc::getModel('tag/tag')->fetchRow("tag_id = ".(int)$id);
            if(!$tag)
            {
                throw new Exception("Tag is not valid.");
            }
            
            $this->_tag = $tag;
        }
        
        return $this->_tag;
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
        $websiteModel     = Ccc::getModel('website/website');
        $select = $this->_prepareQuery();
        return $websiteModel->getAdapter()->fetchCol($select);
    }
    
    protected function _setFilter($select)
    {
        Ccc::getModel('admin/search')->setSearch();
        
        if((int)Ccc::getHelper('admin/search')->search()->website_id_from)
        {
            $select->where("W.website_id >= ?", (int)Ccc::getHelper('admin/search')->search()->website_id_from);
        }
        if((int)Ccc::getHelper('admin/search')->search()->website_id_to)
        {
            $select->where("W.website_id <= ?", (int)Ccc::getHelper('admin/search')->search()->website_id_to);
        }
                
        if(Ccc::getHelper('admin/search')->search()->name = (string)trim(Ccc::getHelper('admin/search')->search()->name))
        {
            $select->where("W.name Like ?", "%".Ccc::getHelper('admin/search')->search()->name."%");
        }
                     
        if(Ccc::getHelper('admin/search')->search()->created_date_from)
        {
            $select->where("DATE(W.created_date) >= ?", date('Y-m-d', strtotime(Ccc::getHelper('admin/search')->search()->created_date_from)));
        }
                 
        if(Ccc::getHelper('admin/search')->search()->created_date_to)
        {
            $select->where("DATE(W.created_date) <= ?", date('Y-m-d', strtotime(Ccc::getHelper('admin/search')->search()->created_date_to)));
        }
        
        if(Ccc::getHelper('admin/search')->search()->updated_date_from)
        {
            $select->where("DATE(W.updated_date) >= ?", date('Y-m-d', strtotime(Ccc::getHelper('admin/search')->search()->updated_date_from)));
        }
        
        if(Ccc::getHelper('admin/search')->search()->updated_date_to)
        {
            $select->where("DATE(W.updated_date) <= ?", date('Y-m-d', strtotime(Ccc::getHelper('admin/search')->search()->updated_date_to)));
        }
       
        if((int)Ccc::getHelper('admin/search')->search()->is_enabled)
        {
            $select->where("W.is_enabled = ?", (int)Ccc::getHelper('admin/search')->search()->is_enabled);
        }
        
        return $select;
    }
    
    public function getIsEnabled($website)
    {
        if($website->is_enabled == Website_Model_Website::IS_ENABLED_YES)
        {
            return Website_Model_Website::IS_ENABLED_YES_TEXT;
        }
        
        return Website_Model_Website::IS_ENABLED_NO_TEXT;
    }
    
    public function getIsEnabledOptions()
    {
        return array(
            0 => "",
            Website_Model_Website::IS_ENABLED_YES => Website_Model_Website::IS_ENABLED_YES_TEXT,
            Website_Model_Website::IS_ENABLED_NO => Website_Model_Website::IS_ENABLED_NO_TEXT
        );
    }
    
    public function getResetUrl()
    {
        return $this->url(array('module'=>'admin','controller'=>'tag_website','action'=>'reset','id'=>(int)$this->getRequest()->getParam("id", 0),'page'=>1),null,true);
    }
           
    public function getGridUrl()
    {
        return $this->url(array('action'=>'grid','id'=>(int)$this->getRequest()->getParam("id", 0)));
    }
}
