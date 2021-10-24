<?php
class Admin_View_Block_Tag_Index_Grid extends Admin_View_Block_Widget_Grid
{
    protected $_defaultSort     = 'T.tag_id';
    
    public function __construct()
    {
        parent::__construct();
        $this->setTemplate('tag/index/grid.phtml');
    }
    
    protected function _prepareQuery()
    {
        $tagModel          = Ccc::getModel('tag/tag');
        $categoryModel     = Ccc::getModel('category/category');
        $websiteModel     = Ccc::getModel('website/website');
        $couponModel     = Ccc::getModel('coupon/coupon');
        
        $select = $tagModel->select()
                                ->setIntegrityCheck(false)
                                ->from(array('T'=>$tagModel->getTableName()), array('*'))
                                ->joinLeft(array("C"=>$categoryModel->getTableName()), "T.category_id = C.category_id", array("category"=>"C.name"));
        
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
        $tagModel  = Ccc::getModel('tag/tag');
        $select = $this->_prepareQuery();
        return $tagModel->fetchAll($select)->getAllIds();
    }
    
    protected function _setFilter($select)
    {
		Ccc::getModel('admin/search')->setSearch();
		
        if((int)Ccc::getHelper('admin/search')->search()->tag_id_from)
        {
            $select->where("T.tag_id >= ?", (int)Ccc::getHelper('admin/search')->search()->tag_id_from);
        }
        if((int)Ccc::getHelper('admin/search')->search()->tag_id_to)
        {
            $select->where("T.tag_id <= ?", (int)Ccc::getHelper('admin/search')->search()->tag_id_to);
        }
        
        if((int)Ccc::getHelper('admin/search')->search()->view_count_from)
        {
            $select->where("T.view_count >= ?", (int)Ccc::getHelper('admin/search')->search()->view_count_from);
        }
        if((int)Ccc::getHelper('admin/search')->search()->view_count_to)
        {
            $select->where("T.view_count <= ?", (int)Ccc::getHelper('admin/search')->search()->view_count_to);
        }
        
        if(Ccc::getHelper('admin/search')->search()->name = (string)trim(Ccc::getHelper('admin/search')->search()->name))
        {
            $select->where("T.name Like ?", "%".Ccc::getHelper('admin/search')->search()->name."%");
        }
        
        if(Ccc::getHelper('admin/search')->search()->category = (string)trim(Ccc::getHelper('admin/search')->search()->category))
        {
            $select->where("C.name Like ?", "%".Ccc::getHelper('admin/search')->search()->category."%");
        }
                               
        if(Ccc::getHelper('admin/search')->search()->created_date_from)
        {
            $select->where("DATE(T.created_date) >= ?", date('Y-m-d', strtotime(Ccc::getHelper('admin/search')->search()->created_date_from)));
        }
                 
        if(Ccc::getHelper('admin/search')->search()->created_date_to)
        {
            $select->where("DATE(T.created_date) <= ?", date('Y-m-d', strtotime(Ccc::getHelper('admin/search')->search()->created_date_to)));
        }
        
        if(Ccc::getHelper('admin/search')->search()->updated_date_from)
        {
            $select->where("DATE(T.updated_date) >= ?", date('Y-m-d', strtotime(Ccc::getHelper('admin/search')->search()->updated_date_from)));
        }
        
        if(Ccc::getHelper('admin/search')->search()->updated_date_to)
        {
            $select->where("DATE(T.updated_date) <= ?", date('Y-m-d', strtotime(Ccc::getHelper('admin/search')->search()->updated_date_to)));
        }
       
        if((int)Ccc::getHelper('admin/search')->search()->is_enabled)
        {
            $select->where("T.is_enabled = ?", (int)Ccc::getHelper('admin/search')->search()->is_enabled);
        }
        
        return $select;
    }
    
    public function getIsEnabled($tag)
    {
        if($tag->is_enabled == Tag_Model_Tag::IS_ENABLED_YES)
        {
            return Tag_Model_Tag::IS_ENABLED_YES_TEXT;
        }
        
        return Tag_Model_Tag::IS_ENABLED_NO_TEXT;
    }
    
    public function getIsEnabledOptions()
    {
        return array(
            0 => "",
            Tag_Model_Tag::IS_ENABLED_YES => Tag_Model_Tag::IS_ENABLED_YES_TEXT,
            Tag_Model_Tag::IS_ENABLED_NO => Tag_Model_Tag::IS_ENABLED_NO_TEXT
        );
    }
    
    public function getResetUrl()
    {
        return $this->url(array('module'=>'admin','controller'=>'tag_index','action'=>'reset','page'=>1),null,true);
    }
           
    public function getDeleteUrl()
    {
        return $this->url(array('action'=>'delete'));
    }
    
    public function getGridUrl()
    {
        return $this->url(array('action'=>'grid'));
    }
  
    public function getEditUrl($tag)
    {
        return $this->url(array('action'=>'edit','id'=>$tag->tag_id));
    }
    
    public function getAssignWebsiteUrl($tag)
    {
        return $this->url(array('module'=>'admin','controller'=>'tag_website','action'=>'index-json','id'=>$tag->tag_id, 'page'=>1),null,true);
    }
    
    public function getAssignCouponUrl($tag)
    {
        return $this->url(array('module'=>'admin','controller'=>'tag_coupon','action'=>'index-json','id'=>$tag->tag_id, 'page'=>1),null,true);
    }
}
