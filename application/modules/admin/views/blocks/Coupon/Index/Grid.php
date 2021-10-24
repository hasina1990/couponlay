<?php
class Admin_View_Block_Coupon_Index_Grid extends Admin_View_Block_Widget_Grid
{
    protected $_defaultSort     = 'C.coupon_id';
    
    public function __construct()
    {
        parent::__construct();
        $this->setTemplate('coupon/index/grid.phtml');
    }
    
    protected function _prepareQuery()
    {
        $couponModel          = Ccc::getModel('coupon/coupon');
        $websiteModel          = Ccc::getModel('website/website');
        
        $select = $couponModel->select()
                                ->setIntegrityCheck(false)
                                ->from(array('C'=>$couponModel->getTableName()),array('*'))
                                ->joinLeft(array('W'=>$websiteModel->getTableName()), "C.website_id = W.website_id", array("website_name"=>"W.name"));
        
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
        $couponModel          = Ccc::getModel('coupon/coupon');
        $select = $this->_prepareQuery();
        return $couponModel->fetchAll($select)->getAllIds();
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
        
        if((int)Ccc::getHelper('admin/search')->search()->view_count_from)
        {
            $select->where("C.view_count >= ?", (int)Ccc::getHelper('admin/search')->search()->view_count_from);
        }
        if((int)Ccc::getHelper('admin/search')->search()->view_count_to)
        {
            $select->where("C.view_count <= ?", (int)Ccc::getHelper('admin/search')->search()->view_count_to);
        }
         
        if(Ccc::getHelper('admin/search')->search()->wname = (string)trim(Ccc::getHelper('admin/search')->search()->wname))
        {
            $select->where("W.name Like ?", "%".Ccc::getHelper('admin/search')->search()->wname."%");
        }
            
        if(Ccc::getHelper('admin/search')->search()->name = (string)trim(Ccc::getHelper('admin/search')->search()->name))
        {
            $select->where("C.name Like ?", "%".Ccc::getHelper('admin/search')->search()->name."%");
        }
        
        if((int)Ccc::getHelper('admin/search')->search()->type)
        {
            $select->where("C.type = ?", (int)Ccc::getHelper('admin/search')->search()->type);
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
     
    public function getResetUrl()
    {
        return $this->url(array('module'=>'admin','controller'=>'coupon_index','action'=>'reset','page'=>1),null,true);
    }
           
    public function getDeleteUrl()
    {
        return $this->url(array('action'=>'delete'));
    }
    
    public function getGridUrl()
    {
        return $this->url(array('action'=>'grid'));
    }
  
    public function getEditUrl($coupon)
    {
        return $this->url(array('action'=>'edit','id'=>$coupon->coupon_id));
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
    
    public function getIsExpired($coupon)
    {
        if($coupon->is_expired == Coupon_Model_Coupon::IS_EXPIRED_YES)
        {
            return Coupon_Model_Coupon::IS_EXPIRED_YES_TEXT;
        }
        
        return Coupon_Model_Coupon::IS_EXPIRED_NO_TEXT;
    }
    
    public function getIsExpiredOptions()
    {
        return array(
            0 => "",
            Coupon_Model_Coupon::IS_EXPIRED_YES => Coupon_Model_Coupon::IS_EXPIRED_YES_TEXT,
            Coupon_Model_Coupon::IS_EXPIRED_NO => Coupon_Model_Coupon::IS_EXPIRED_NO_TEXT
        );
    }
    
    public function getCouponType($coupon)
    {
        if($coupon->type == Coupon_Model_Coupon::COUPON_TYPE_CODE)
        {                                        
            return Coupon_Model_Coupon::COUPON_TYPE_CODE_TEXT;
        }
        
        return Coupon_Model_Coupon::COUPON_TYPE_DEAL_TEXT;
    }
    
    public function getCouponTypeOptions()
    {
        return array(
             0 => "",
             Coupon_Model_Coupon::COUPON_TYPE_CODE => Coupon_Model_Coupon::COUPON_TYPE_CODE_TEXT,
             Coupon_Model_Coupon::COUPON_TYPE_DEAL => Coupon_Model_Coupon::COUPON_TYPE_DEAL_TEXT
        );
    }
}
