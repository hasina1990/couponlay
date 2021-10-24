<?php
class Admin_View_Block_Category_Index_Grid extends Admin_View_Block_Widget_Grid
{
    protected $_defaultSort     = 'C.category_id';
    
    public function __construct()
    {
        parent::__construct();
        $this->setTemplate('category/index/grid.phtml');
    }
    
    protected function _prepareQuery()
    {
        $categoryModel          = Ccc::getModel('category/category');
        
        $select = $categoryModel->select()
                                ->setIntegrityCheck(false)
                                ->from(array('C'=>$categoryModel->getTableName()),array('*'));
        
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
        $categoryModel  = Ccc::getModel('category/category');
        $select = $this->_prepareQuery();
        return $categoryModel->fetchAll($select)->getAllIds();
    }
    
    protected function _setFilter($select)
    {
		Ccc::getModel('admin/search')->setSearch();
		
        if((int)Ccc::getHelper('admin/search')->search()->category_id_from)
        {
            $select->where("C.category_id >= ?", (int)Ccc::getHelper('admin/search')->search()->category_id_from);
        }
        if((int)Ccc::getHelper('admin/search')->search()->category_id_to)
        {
            $select->where("C.category_id <= ?", (int)Ccc::getHelper('admin/search')->search()->category_id_to);
        }
        
        if((int)Ccc::getHelper('admin/search')->search()->view_count_from)
        {
            $select->where("C.view_count >= ?", (int)Ccc::getHelper('admin/search')->search()->view_count_from);
        }
        if((int)Ccc::getHelper('admin/search')->search()->view_count_to)
        {
            $select->where("C.view_count <= ?", (int)Ccc::getHelper('admin/search')->search()->view_count_to);
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
    
    public function getIsEnabled($category)
    {
        if($category->is_enabled == Category_Model_Category::IS_ENABLED_YES)
        {
            return Category_Model_Category::IS_ENABLED_YES_TEXT;
        }
        
        return Category_Model_Category::IS_ENABLED_NO_TEXT;
    }
    
    public function getIsEnabledOptions()
    {
        return array(
            0 => "",
            Category_Model_Category::IS_ENABLED_YES => Category_Model_Category::IS_ENABLED_YES_TEXT,
            Category_Model_Category::IS_ENABLED_NO => Category_Model_Category::IS_ENABLED_NO_TEXT
        );
    }
    
    public function getResetUrl()
    {
        return $this->url(array('module'=>'admin','controller'=>'category_index','action'=>'reset','page'=>1),null,true);
    }
           
    public function getDeleteUrl()
    {
        return $this->url(array('action'=>'delete'));
    }
    
    public function getGridUrl()
    {
        return $this->url(array('action'=>'grid'));
    }
  
    public function getEditUrl($category)
    {
        return $this->url(array('action'=>'edit','id'=>$category->category_id));
    }
    
    public function getImageUrl($category)
    {
         return Ccc::getModel("category/uploader")->setCategory($category)->getCategoryImageUrl();
    }
    
    public function getCategoryWebsites($category)
    {
        $websiteModel          = Ccc::getModel('website/website');
        
        $select = $websiteModel->select()
                                ->setIntegrityCheck(false)
                                ->from(array('W'=>$websiteModel->getTableName()),array('website_id'))
                                ->where("category_id = ?", (int)$category->category_id);
                                
        return count($websiteModel->getAdapter()->fetchCol($select));
    }
    
    public function getCategoryCoupons($category)
    {
        $websiteModel          = Ccc::getModel('website/website');
        $couponModel          = Ccc::getModel('coupon/coupon');
        
        $select = $couponModel->select()
                            ->setIntegrityCheck(false)
                            ->from(array('C'=>$couponModel->getTableName()),array('coupon_id'))
                            ->join(array('W'=>$websiteModel->getTableName()), "C.website_id = W.website_id", array())
                            ->where("FIND_IN_SET(".(int)$category->category_id.", W.category_id)");
                                          
        return count($couponModel->getAdapter()->fetchCol($select));
    }
}
