<?php
class Admin_View_Block_Review_Index_Grid extends Admin_View_Block_Widget_Grid
{
    protected $_defaultSort     = 'R.review_id';
    
    public function __construct()
    {
        parent::__construct();
        $this->setTemplate('review/index/grid.phtml');
    }
    
    protected function _prepareQuery()
    {
        $reviewModel          = Ccc::getModel('review/review');
        $websiteModel          = Ccc::getModel('website/website');
        $select = $reviewModel->select()
                                ->setIntegrityCheck(false)
                                ->from(array('R'=>$reviewModel->getTableName()),array('*'))
                                ->joinLeft(array('W'=>$websiteModel->getTableName()), "R.website_id = W.website_id", array("website_name"=>"W.name"));
        
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
        $reviewModel = Ccc::getModel('review/review');
        $select = $this->_prepareQuery();
        return $reviewModel->fetchAll($select)->getAllIds();
    }
    
    protected function _setFilter($select)
    {
		Ccc::getModel('admin/search')->setSearch();
		
        if((int)Ccc::getHelper('admin/search')->search()->coupon_id_from)
        {
            $select->where("R.review_id >= ?", (int)Ccc::getHelper('admin/search')->search()->review_id_from);
        }
        if((int)Ccc::getHelper('admin/search')->search()->coupon_id_to)
        {
            $select->where("R.review_id <= ?", (int)Ccc::getHelper('admin/search')->search()->review_id_to);
        }
                
        if(Ccc::getHelper('admin/search')->search()->name = (string)trim(Ccc::getHelper('admin/search')->search()->name))
        {
            $select->where("R.name Like ?", "%".Ccc::getHelper('admin/search')->search()->name."%");
        }
                                
        if(Ccc::getHelper('admin/search')->search()->review = (string)trim(Ccc::getHelper('admin/search')->search()->review))
        {
            $select->where("R.review Like ?", "%".Ccc::getHelper('admin/search')->search()->review."%");
        }
        
        if(Ccc::getHelper('admin/search')->search()->store = (int)(Ccc::getHelper('admin/search')->search()->store))
        {
            $select->where("R.website_id = ?", Ccc::getHelper('admin/search')->search()->store);
        }
            
        if(Ccc::getHelper('admin/search')->search()->created_date_from)
        {
            $select->where("DATE(R.created_date) >= ?", date('Y-m-d', strtotime(Ccc::getHelper('admin/search')->search()->created_date_from)));
        }
                 
        if(Ccc::getHelper('admin/search')->search()->created_date_to)
        {
            $select->where("DATE(R.created_date) <= ?", date('Y-m-d', strtotime(Ccc::getHelper('admin/search')->search()->created_date_to)));
        }
        
        if(Ccc::getHelper('admin/search')->search()->updated_date_from)
        {
            $select->where("DATE(R.updated_date) >= ?", date('Y-m-d', strtotime(Ccc::getHelper('admin/search')->search()->updated_date_from)));
        }
        
        if(Ccc::getHelper('admin/search')->search()->updated_date_to)
        {
            $select->where("DATE(R.updated_date) <= ?", date('Y-m-d', strtotime(Ccc::getHelper('admin/search')->search()->updated_date_to)));
        }
       
        if((int)Ccc::getHelper('admin/search')->search()->approved)
        {
            $select->where("R.approved = ?", (int)Ccc::getHelper('admin/search')->search()->approved);
        }
        
        return $select;
    }
     
    public function getResetUrl()
    {
        return $this->url(array('module'=>'admin','controller'=>'review_index','action'=>'reset','page'=>1),null,true);
    }
           
    public function getDeleteUrl()
    {
        return $this->url(array('action'=>'delete'));
    }
    
    public function getGridUrl()
    {
        return $this->url(array('action'=>'grid'));
    }
  
    public function getEditUrl($review)
    {
        return $this->url(array('action'=>'edit','id'=>$review->review_id));
    }
    
    public function getIsApproved($review)
    {
        if($review->approved == Review_Model_Review::APPROVED)
        {
            return Review_Model_Review::APPROVED_TEXT;
        }
        
        return Review_Model_Review::PENDNG_APPROVAL_TEXT;
    }
    
    public function getIsApprovedOptions()
    {
        return array(
            0 => "",
            Review_Model_Review::APPROVED => Review_Model_Review::APPROVED_TEXT,
            Review_Model_Review::PENDNG_APPROVAL => Review_Model_Review::PENDNG_APPROVAL_TEXT
        );
    }
    
    public function getWebsites()
    {
        $websiteModel  = Ccc::getModel('website/website');
        $websites = array();
        $select = $websiteModel->select()
                            ->setIntegrityCheck(false)
                            ->from(array('W'=>$websiteModel->getTableName()),array('website_id', 'name'))
                            ->order("website_id ASC");
        $websites = $websiteModel->getAdapter()->fetchPairs($select);
        $websites[0]='';
        ksort($websites);
        return $websites;
    }
}
