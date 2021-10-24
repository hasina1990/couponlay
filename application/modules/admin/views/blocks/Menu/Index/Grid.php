<?php
class Admin_View_Block_Menu_Index_Grid extends Admin_View_Block_Widget_Grid
{
    protected $_defaultSort     = 'M.id';
    
    public function __construct()
    {
        parent::__construct();
        $this->setTemplate('menu/index/grid.phtml');
    }
    
    protected function _prepareQuery()
    {
        $menuModel          = Ccc::getModel('core/menu');
        
        $select = $menuModel->select()
                                ->setIntegrityCheck(false)
                                ->from(array('M'=>$menuModel->getTableName()),array('*'))
                                ->where("is_footer_link = 2");
        
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
        $menuModel  = Ccc::getModel('core/menu');
        $select = $this->_prepareQuery();
        return $menuModel->fetchAll($select)->getAllIds();
    }
    
    protected function _setFilter($select)
    {
		Ccc::getModel('admin/search')->setSearch();
		
        if((int)Ccc::getHelper('admin/search')->search()->menu_id_from)
        {
            $select->where("M.id >= ?", (int)Ccc::getHelper('admin/search')->search()->category_id_from);
        }
        if((int)Ccc::getHelper('admin/search')->search()->menu_id_to)
        {
            $select->where("M.id <= ?", (int)Ccc::getHelper('admin/search')->search()->menu_id_to);
        }
                
        if(Ccc::getHelper('admin/search')->search()->label = (string)trim(Ccc::getHelper('admin/search')->search()->label))
        {
            $select->where("M.label Like ?", "%".Ccc::getHelper('admin/search')->search()->label."%");
        }
        
        if(Ccc::getHelper('admin/search')->search()->title = (string)trim(Ccc::getHelper('admin/search')->search()->title))
        {
            $select->where("M.title Like ?", "%".Ccc::getHelper('admin/search')->search()->title."%");
        }
           
        if(Ccc::getHelper('admin/search')->search()->created_date_from)
        {
            $select->where("DATE(M.created_date) >= ?", date('Y-m-d', strtotime(Ccc::getHelper('admin/search')->search()->created_date_from)));
        }
                 
        if(Ccc::getHelper('admin/search')->search()->created_date_to)
        {
            $select->where("DATE(M.created_date) <= ?", date('Y-m-d', strtotime(Ccc::getHelper('admin/search')->search()->created_date_to)));
        }
        
        if(Ccc::getHelper('admin/search')->search()->updated_date_from)
        {
            $select->where("DATE(M.updated_date) >= ?", date('Y-m-d', strtotime(Ccc::getHelper('admin/search')->search()->updated_date_from)));
        }
        
        if(Ccc::getHelper('admin/search')->search()->updated_date_to)
        {
            $select->where("DATE(M.updated_date) <= ?", date('Y-m-d', strtotime(Ccc::getHelper('admin/search')->search()->updated_date_to)));
        }
       
        if((int)Ccc::getHelper('admin/search')->search()->is_enabled)
        {
            $select->where("M.is_enabled = ?", (int)Ccc::getHelper('admin/search')->search()->is_enabled);
        }
        
        return $select;
    }
    
    public function getIsEnabled($menu)
    {
        if($menu->is_enabled == Core_Model_Menu::IS_ENABLED_YES)
        {
            return Core_Model_Menu::IS_ENABLED_YES_TEXT;
        }
        
        return Core_Model_Menu::IS_ENABLED_NO_TEXT;
    }
    
    public function getIsEnabledOptions()
    {
        return array(
            0 => "",
            Core_Model_Menu::IS_ENABLED_YES => Core_Model_Menu::IS_ENABLED_YES_TEXT,
            Core_Model_Menu::IS_ENABLED_NO => Core_Model_Menu::IS_ENABLED_NO_TEXT
        );
    }
    
    public function getResetUrl()
    {
        return $this->url(array('module'=>'admin','controller'=>'menu_index','action'=>'reset','page'=>1),null,true);
    }
           
    public function getDeleteUrl()
    {
        return $this->url(array('action'=>'delete'));
    }
    
    public function getGridUrl()
    {
        return $this->url(array('action'=>'grid'));
    }
  
    public function getEditUrl($menu)
    {
        return $this->url(array('action'=>'edit','id'=>$menu->id));
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
