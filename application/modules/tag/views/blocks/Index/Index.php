<?php
class Tag_View_Block_Index_Index extends User_View_Block_Widget_Grid
{
   protected $_recordPerPage = 10;
   protected $_collection = null;
   protected $_dealCount = null;
   protected $_codeCount = null;
   
    public function setCollectionCount()
    {
        $this->_dealCount = 0;
        $this->_codeCount = 0;
            
       $collection = $this->getCollection();
       if(!$collection->count())
       {
           return $this; 
       }
       
       $data = json_decode($collection->toJson(), 1);
       if(count($data))
       {
           foreach($data as $_data)
           {
               if($_data["type"] == Coupon_Model_Coupon::COUPON_TYPE_DEAL)
               {
                   $this->_dealCount = $this->_dealCount + 1;
               }
               elseif($_data["type"] == Coupon_Model_Coupon::COUPON_TYPE_CODE)
               {
                   $this->_codeCount = $this->_codeCount + 1;
               }
           }
       }
       
       return $this;
    }
    
    public function getDealCount()
    {
        return $this->_dealCount;
    }
    
    public function getCodeCount()
    {
        return $this->_codeCount;
    }
    
	public function __construct()                     
	{
        parent::__construct();
		$this->setTemplate('index/index.phtml');
	}
       
    public function getTag()
    {
        $tag = Ccc::getModel("user/session")->getSession("tag", null);
        if(!$tag)
        {
            throw new Exception("invalid request.");
        }
        
        return $tag;
    }
    
    public function getCategory()
    {
        $category = Ccc::getModel("user/session")->getSession("category", null);
        if(!$category)
        {
            $category = Ccc::getModel("category/category")->fetchRow("category_id = ".(int)$this->getTag()->category_id);
            if(!$category)
            {
                throw new Exception("invalid request.");
            }
        }
        elseif($category->category_id != (int)$this->getTag()->category_id)
        {
            $category = Ccc::getModel("category/category")->fetchRow("category_id = ".(int)$this->getTag()->category_id);
            if(!$category)
            {
                throw new Exception("invalid request.");
            } 
        }
        
        Ccc::getModel("user/session")->setSession("category", $category);
        return $category;
    }
    
    public function _prepareQuery()
    {
        $websites = Ccc::getModel("user/session")->getSession("tagWiseWebsites");
      
        $couponModel = Ccc::getModel("coupon/coupon");
        $websiteModel = Ccc::getModel("website/website");
        
        $select =  $couponModel->select()
                               ->setIntegrityCheck(false) 
                               ->from(array("C"=>$couponModel->getTableName()), array('*'))
                               ->join(array("W"=>$websiteModel->getTableName()), "C.website_id = W.website_id", array("icon", "website_name"=>"W.name"))
                               ->where("C.is_enabled = ?", Coupon_Model_Coupon::IS_ENABLED_YES)
                               ->where("C.is_expired = ?", Coupon_Model_Coupon::IS_EXPIRED_NO)
                               ->order("C.created_date DESC");
      
       $couponIds = array();
       if($this->getTag()->coupon_id)
       {
           $couponIds = json_decode($this->getTag()->coupon_id, 1);
       }
       
       if(count($couponIds))
       {
           $select->where("C.coupon_id IN (?)", $couponIds);
       }
       else
       {
           $select->where("W.website_id IN (?)", array_keys($websites));
       }
       
       
        return $select;
    }

    public function getCollection()
    { 
        if(!$this->_collection)
        { 
            $select = $this->_prepareQuery();
            $paginator = Zend_Paginator::factory($select);
            $paginator->setCurrentPageNumber($this->getCurrentPage());
            $paginator->setDefaultItemCountPerPage($this->getRecordPerPage());
            $this->_collection = $paginator->setPageRange($this->getPageRange());
        }
        
        return $this->_collection;
    }
     
    public function getRequestObject()
    {
        $object = Zend_Controller_Front::getInstance();
        return $object;
    }
    
    public function getCouponUrl($couponId, $type)
    {
        if($type == Coupon_Model_Coupon::COUPON_TYPE_CODE)
        {
            return $this->url(array("module"=>"coupon","controller"=>"index","action"=>"code","id"=>$couponId));
        }
        else
        {
            return $this->url(array("module"=>"coupon","controller"=>"index","action"=>"promo","id"=>$couponId));
        }
    }
    
    public function getTagWiseWebsites()
    {
        return Ccc::getModel("user/session")->getSession("tagWiseWebsites");
    }
    
    public function getWebsiteTags()
    {
        $tagModel     = Ccc::getModel("tag/tag");
       
        $select = $tagModel->select()
                                ->from(array("T"=>$tagModel->getTableName()),array("tag_id", "name"))
                                ->where("is_enabled = ?", Tag_Model_Tag::IS_ENABLED_YES)
                                ->where("category_id = ?", $this->getCategory()->category_id)
                                ->order('view_count DESC');
        
        return $tagModel->getAdapter()->fetchPairs($select);
    }
  
    public function getAllVotesByCoupon($couponId)
    {
        $votingModel = Ccc::getModel("voting/voting");
        $select = $votingModel->select()
                              ->from($votingModel->getTableName())
                              ->where("coupon_id =  ?", (int)$couponId);
                              
        $votes =  $votingModel->getAdapter()->fetchCol($select);
        return count($votes);
    }
    
    public function canOpenPopUp()
    {
        if($this->getRequestObject()->getRequest()->getParam('cpn_id',0))
        {
            return true;
        }        
        return false;
    }
    
    public function getCouponViewUrl($id)
    {
        $page = $this->getRequest()->getParam("page", 1);        
        return $this->baseUrl(Ccc::getModel("core/url_rewrite")->getUrlKey($this->getTag()->tag_id, Core_Model_Url_Rewrite::ENTITY_TAG))."/page/".$page."?cpn_id=".$id;
    }
}
