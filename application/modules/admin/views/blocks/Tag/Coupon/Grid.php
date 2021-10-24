<?php
class Admin_View_Block_Tag_Coupon_Grid extends Admin_View_Block_Widget_Grid
{
    protected $_defaultSort     = 'C.coupon_id';
    protected $_tag = null;
    
    public function __construct()
    {
        parent::__construct();
        $this->setTemplate('tag/coupon/grid.phtml');
    }
    
    protected function _prepareQuery()
    {
        $couponModel     = Ccc::getModel('coupon/coupon');
        
        $select = $couponModel->select()
                                ->setIntegrityCheck(false)
                                ->from(array('C'=>$couponModel->getTableName()), array('*'));
        
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
        $couponModel     = Ccc::getModel('coupon/coupon');
        $select = $this->_prepareQuery();
        return $couponModel->getAdapter()->fetchCol($select);
    }
    
    protected function _setFilter($select)
    {
        Ccc::getModel('admin/search')->setSearch();
        
        if((int)Ccc::getHelper('admin/search')->search()->coupon_id_from)
        {
            $select->where("C.coupon_id >= ?", (int)Ccc::getHelper('admin/search')->search()->coupon_id_from);
        }
        if((int)Ccc::getHelper('admin/search')->search()->coupon_id_to)
        {
            $select->where("C.coupon_id <= ?", (int)Ccc::getHelper('admin/search')->search()->coupon_id_to);
        }
                
        if(Ccc::getHelper('admin/search')->search()->name = (string)trim(Ccc::getHelper('admin/search')->search()->name))
        {
            $select->where("C.name Like ?", "%".Ccc::getHelper('admin/search')->search()->name."%");
        }
                                
        if(Ccc::getHelper('admin/search')->search()->seo_title = (string)trim(Ccc::getHelper('admin/search')->search()->seo_title))
        {
            $select->where("C.seo_title Like ?", "%".Ccc::getHelper('admin/search')->search()->seo_title."%");
        }
        
        if(Ccc::getHelper('admin/search')->search()->seo_keyword = (string)trim(Ccc::getHelper('admin/search')->search()->seo_keyword))
        {
            $select->where("C.seo_keyword Like ?", "%".Ccc::getHelper('admin/search')->search()->seo_keyword."%");
        }
            
        if(Ccc::getHelper('admin/search')->search()->created_date_from)
        {
            $select->where("DATE(C.created_date) >= ?", date('Y-m-d', strtotime(Ccc::getHelper('admin/search')->search()->created_date_from)));
        }
                 
        if(Ccc::getHelper('admin/search')->search()->created_date_to)
        {
            $select->where("DATE(C.created_date) <= ?", date('Y-m-d', strtotime(Ccc::getHelper('admin/search')->search()->created_date_to)));
        }
        
        if(Ccc::getHelper('admin/search')->search()->updated_date_from)
        {
            $select->where("DATE(C.updated_date) >= ?", date('Y-m-d', strtotime(Ccc::getHelper('admin/search')->search()->updated_date_from)));
        }
        
        if(Ccc::getHelper('admin/search')->search()->updated_date_to)
        {
            $select->where("DATE(C.updated_date) <= ?", date('Y-m-d', strtotime(Ccc::getHelper('admin/search')->search()->updated_date_to)));
        }
       
        if((int)Ccc::getHelper('admin/search')->search()->is_enabled)
        {
            $select->where("C.is_enabled = ?", (int)Ccc::getHelper('admin/search')->search()->is_enabled);
        }
        
        return $select;
    }
    
    public function getIsEnabled($coupon)
    {
        if($coupon->is_enabled == Coupon_Model_Coupon::IS_ENABLED_YES)
        {
            return Coupon_Model_Coupon::IS_ENABLED_YES_TEXT;
        }
        
        return Coupon_Model_Coupon::IS_ENABLED_NO_TEXT;
    }
    
    public function getIsEnabledOptions()
    {
        return array(
            0 => "",
            Coupon_Model_Coupon::IS_ENABLED_YES => Coupon_Model_Coupon::IS_ENABLED_YES_TEXT,
            Coupon_Model_Coupon::IS_ENABLED_NO => Coupon_Model_Coupon::IS_ENABLED_NO_TEXT
        );
    }
    
    public function getResetUrl()
    {
        return $this->url(array('module'=>'admin','controller'=>'tag_coupon','action'=>'reset','id'=>(int)$this->getRequest()->getParam("id", 0),'page'=>1),null,true);
    }
           
    public function getGridUrl()
    {
        return $this->url(array('action'=>'grid','id'=>(int)$this->getRequest()->getParam("id", 0)));
    }
}
