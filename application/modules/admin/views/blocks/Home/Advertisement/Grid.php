<?php
class Admin_View_Block_Home_Advertisement_Grid extends Admin_View_Block_Widget_Grid
{
    protected $_defaultSort     = 'B.banner_id';
    
    public function __construct()
    {
        parent::__construct();
        $this->setTemplate('home/advertisement/grid.phtml');
    }
    
    protected function _prepareQuery()
    {
        $bannerModel          = Ccc::getModel('banner/banner');
        
        $select = $bannerModel->select()
                                ->setIntegrityCheck(false)
                                ->from(array('B'=>$bannerModel->getTableName()),array('*'))
                                ->where("is_ad = ?", Banner_Model_Banner::IS_AD_YES);
        
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
        $bannerModel  = Ccc::getModel('banner/banner');
        $select = $this->_prepareQuery();
        return $bannerModel->fetchAll($select)->getAllIds();
    }
    
    protected function _setFilter($select)
    {
		Ccc::getModel('admin/search')->setSearch();
		
        if((int)Ccc::getHelper('admin/search')->search()->banner_id_from)
        {
            $select->where("B.banner_id >= ?", (int)Ccc::getHelper('admin/search')->search()->banner_id_from);
        }
        if((int)Ccc::getHelper('admin/search')->search()->banner_id_to)
        {
            $select->where("B.banner_id <= ?", (int)Ccc::getHelper('admin/search')->search()->banner_id_to);
        }
                
        if(Ccc::getHelper('admin/search')->search()->name = (string)trim(Ccc::getHelper('admin/search')->search()->name))
        {
            $select->where("B.name Like ?", "%".Ccc::getHelper('admin/search')->search()->name."%");
        }
                                    
        if(Ccc::getHelper('admin/search')->search()->created_date_from)
        {
            $select->where("DATE(B.created_date) >= ?", date('Y-m-d', strtotime(Ccc::getHelper('admin/search')->search()->created_date_from)));
        }
                 
        if(Ccc::getHelper('admin/search')->search()->created_date_to)
        {
            $select->where("DATE(B.created_date) <= ?", date('Y-m-d', strtotime(Ccc::getHelper('admin/search')->search()->created_date_to)));
        }
        
        if(Ccc::getHelper('admin/search')->search()->updated_date_from)
        {
            $select->where("DATE(B.updated_date) >= ?", date('Y-m-d', strtotime(Ccc::getHelper('admin/search')->search()->updated_date_from)));
        }
        
        if(Ccc::getHelper('admin/search')->search()->updated_date_to)
        {
            $select->where("DATE(B.updated_date) <= ?", date('Y-m-d', strtotime(Ccc::getHelper('admin/search')->search()->updated_date_to)));
        }
       
        if((int)Ccc::getHelper('admin/search')->search()->is_enabled)
        {
            $select->where("B.is_enabled = ?", (int)Ccc::getHelper('admin/search')->search()->is_enabled);
        }
        
        return $select;
    }
    
    public function getIsEnabled($banner)
    {
        if($banner->is_enabled == Banner_Model_Banner::IS_ENABLED_YES)
        {
            return Banner_Model_Banner::IS_ENABLED_YES_TEXT;
        }
        
        return Banner_Model_Banner::IS_ENABLED_NO_TEXT;
    }
    
    public function getIsEnabledOptions()
    {
        return array(
            0 => "",
            Banner_Model_Banner::IS_ENABLED_YES => Banner_Model_Banner::IS_ENABLED_YES_TEXT,
            Banner_Model_Banner::IS_ENABLED_NO  => Banner_Model_Banner::IS_ENABLED_NO_TEXT
        );
    }
    
    public function getResetUrl()
    {
        return $this->url(array('module'=>'admin','controller'=>'home_banner','action'=>'reset','page'=>1),null,true);
    }
           
    public function getDeleteUrl()
    {
        return $this->url(array('action'=>'delete'));
    }
    
    public function getGridUrl()
    {
        return $this->url(array('action'=>'grid'));
    }
  
    public function getEditUrl($banner)
    {
        return $this->url(array('action'=>'edit','id'=>$banner->banner_id));
    }
    
    public function getImageUrl($banner)
    {
         return Ccc::getModel("banner/uploader")->setBanner($banner)->getBannerImageUrl();
    }
}
