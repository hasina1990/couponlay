<?php
class Admin_View_Block_Website_Index_Grid extends Admin_View_Block_Widget_Grid
{
    protected $_defaultSort     = 'W.website_id';
    
    public function __construct()
    {
        parent::__construct();
        $this->setTemplate('website/index/grid.phtml');
    }
    
    protected function _prepareQuery()
    {
        $websiteModel          = Ccc::getModel('website/website');
        
        $select = $websiteModel->select()
                                ->setIntegrityCheck(false)
                                ->from(array('W'=>$websiteModel->getTableName()),array('*'));
        
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
        $websiteModel  = Ccc::getModel('website/website');
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
        
        if((int)Ccc::getHelper('admin/search')->search()->view_count_from)
        {
            $select->where("W.view_count >= ?", (int)Ccc::getHelper('admin/search')->search()->view_count_from);
        }
        if((int)Ccc::getHelper('admin/search')->search()->view_count_to)
        {
            $select->where("W.view_count <= ?", (int)Ccc::getHelper('admin/search')->search()->view_count_to);
        }
        
        if(Ccc::getHelper('admin/search')->search()->name = (string)trim(Ccc::getHelper('admin/search')->search()->name))
        {
            $select->where("W.name Like ?", "%".Ccc::getHelper('admin/search')->search()->name."%");
        }
        
        if(Ccc::getHelper('admin/search')->search()->website_url = (string)trim(Ccc::getHelper('admin/search')->search()->website_url))
        {
            $select->where("W.website_url Like ?", "%".Ccc::getHelper('admin/search')->search()->website_url."%");
        }
        
        if(Ccc::getHelper('admin/search')->search()->seo_title = (string)trim(Ccc::getHelper('admin/search')->search()->seo_title))
        {
            $select->where("W.seo_title Like ?", "%".Ccc::getHelper('admin/search')->search()->seo_title."%");
        }
        
        if(Ccc::getHelper('admin/search')->search()->seo_keyword = (string)trim(Ccc::getHelper('admin/search')->search()->seo_keyword))
        {
            $select->where("W.seo_keyword Like ?", "%".Ccc::getHelper('admin/search')->search()->seo_keyword."%");
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
        return $this->url(array('module'=>'admin','controller'=>'website_index','action'=>'reset','page'=>1),null,true);
    }
           
    public function getDeleteUrl()
    {
        return $this->url(array('action'=>'delete'));
    }
    
    public function getGridUrl()
    {
        return $this->url(array('action'=>'grid'));
    }
  
    public function getEditUrl($website)
    {
        return $this->url(array('action'=>'edit','id'=>$website->website_id));
    }
    
    public function getIconImageUrl($website)
    {
         return Ccc::getModel("website/uploader")->setWebsite($website)->getIconImageUrl();
    }
    
    public function getMainImageUrl($website)
    {
         return Ccc::getModel("website/uploader")->setWebsite($website)->getMainImageUrl();
    }
    
    public function getCouponCountByWebsite($website)
    {
        $couponModel          = Ccc::getModel('coupon/coupon');

        $select = $couponModel->select()
                            ->setIntegrityCheck(false)
                            ->from(array('C'=>$couponModel->getTableName()),array('coupon_id'))
                            ->where("website_id = ?", (int)$website->website_id);
                            
        return count($couponModel->getAdapter()->fetchCol($select));
    }
    
    public function getWebsiteWiseCouponUrl($website)
    {
        return $this->url(array('module'=>'admin','controller'=>'website_coupon','action'=>'index-json','wid'=>$website->website_id));
    }
}
