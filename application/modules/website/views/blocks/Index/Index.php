<?php
class Website_View_Block_Index_Index extends User_View_Block_Widget_Grid
{
   protected $_similarWebsites = array();
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
       
       $data = $collection->toArray();
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
    
    public function getWebsite()
    {
        $website = Ccc::getModel("user/session")->getSession("website", null);
        if(!$website)
        {
            throw new Exception("invalid request.");
        }
        
        return $website;
    }
  
    public function _prepareQuery()
    {
        $couponModel     = Ccc::getModel("coupon/coupon");
       
        $select = $couponModel->select()
                                ->from(array("C"=>$couponModel->getTableName()),array("*"))
                                ->where("is_enabled = ?", Coupon_Model_Coupon::IS_ENABLED_YES)
                                ->where("is_expired = ?", Coupon_Model_Coupon::IS_EXPIRED_NO)
                                ->where("website_id = ?", $this->getWebsite()->website_id)
                                ->order('created_date DESC');
        
        return $select;
    }

    public function getCollection()
    {
        if(!$this->_collection)
        {  
            $select = $this->_prepareQuery();
            $this->_collection = Ccc::getModel("coupon/coupon")->fetchAll($select);
        }
        
        return $this->_collection;
    }
     
    public function getRequestObject()
    {
        $object = Zend_Controller_Front::getInstance();
        return $object;
    }
    
    public function getCouponUrl($coupon)
    {
        if(!$coupon instanceof Coupon_Model_Coupon_Row)
        {
            throw new Exception("coupon must be instance of Coupon_Model_Coupon_Row");
        }
        
        if($coupon["type"]== Coupon_Model_Coupon::COUPON_TYPE_CODE)
        {
            return $this->url(array("module"=>"coupon","controller"=>"index","action"=>"code","id"=>$coupon["coupon_id"]));
        }
        else
        {
            return $this->url(array("module"=>"coupon","controller"=>"index","action"=>"promo","id"=>$coupon["coupon_id"]));
        }
    }
    
    public function getWebsiteTags()
    {
        $tagModel     = Ccc::getModel("tag/tag");
       
        $select = $tagModel->select()
                                ->from(array("T"=>$tagModel->getTableName()),array("tag_id", "name"))
                                ->where("is_enabled = ?", Tag_Model_Tag::IS_ENABLED_YES)
                                ->where("FIND_IN_SET(".$this->getWebsite()->website_id.", T.website_id)")
                                ->order('view_count DESC');
        
        return $tagModel->getAdapter()->fetchPairs($select);
    }
    
    public function getSimilarWebsite()
    {
        if(!$this->_similarWebsites)
        {
            $categoryModel = Ccc::getModel("category/category");
            
            $select =  $categoryModel->select()
                                 ->from(array("C"=>$categoryModel->getTableName()),array("category_id"))
                                 ->where("is_enabled = ?", Category_Model_Category::IS_ENABLED_YES)
                                 ->where("FIND_IN_SET(C.category_id, '".$this->getWebsite()->category_id."')");
            
            $categories = $categoryModel->getAdapter()->fetchCol($select);
            if(!count($categories))
            {
                $this->_similarWebsites = array();
            }

            $websites = array();
            
            $websiteModel     = Ccc::getModel("website/website");
            foreach($categories as $_category)
            {
                $select = $websiteModel->select()
                                    ->from(array("W"=>$websiteModel->getTableName()),array("website_id", "name"))
                                    ->where("is_enabled = ?", Website_Model_Website::IS_ENABLED_YES)
                                    ->where("FIND_IN_SET(".$_category.", W.category_id)")
                                    ->where("website_id != ?", $this->getWebsite()->website_id)
                                    ->order('view_count DESC');
                                    
                $result = $websiteModel->getAdapter()->fetchPairs($select);
                $websites = $websites + $result;
            }
            
            $this->_similarWebsites = $websites;
        }
        
        return $this->_similarWebsites;
    }
    
    public function getSubscribeUserUrl()
    {
        return $this->url(array('module'=>'user', 'controller'=>'newslatter', 'action'=>'subscribe'));
    }
    
    public function getCouponAndPromotionalFromSimilarWebsites()
    {
        $websites = $this->getSimilarWebsite();
        $couponModel = Ccc::getModel("coupon/coupon");
        $websiteModel = Ccc::getModel("website/website");
        if(!count($websites))
        {
            $websites= array(0);
        }
        
        $select =  $couponModel->select()
                               ->setIntegrityCheck(false) 
                               ->from(array("C"=>$couponModel->getTableName()), array('*'))
                               ->join(array("W"=>$websiteModel->getTableName()), "C.website_id = W.website_id", array("icon", "website_name"=>"W.name"))
                               ->where("C.is_enabled = ?", Coupon_Model_Coupon::IS_ENABLED_YES)
                               ->where("C.is_expired = ?", Coupon_Model_Coupon::IS_EXPIRED_NO)
                               ->where("W.website_id IN (?)", array_keys($websites))
                               ->order("C.created_date DESC")
                               ->group("W.website_id");
                               
        return $couponModel->getAdapter()->fetchAll($select);
    }
    
    public function getSimilarCouponUrl($couponId, $type)
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
    
    public function getExpiredCoupons()
    {
        $couponModel     = Ccc::getModel("coupon/coupon");

        $select = $couponModel->select()
                            ->from(array("C"=>$couponModel->getTableName()),array("*"))
                            ->where("is_expired = ?", Coupon_Model_Coupon::IS_EXPIRED_YES)
                            ->where("website_id = ?", $this->getWebsite()->website_id)
                            ->order('created_date DESC')
                            ->limit(10);

        return $couponModel->getAdapter()->fetchAll($select);
    }
    
    public function isCurrentIpHasVotted()
    {
        $votingModel = Ccc::getModel("voting/voting");
        $select = $votingModel->select()
                              ->from($votingModel->getTableName())
                              ->where("ip_address = ?", $_SERVER["REMOTE_ADDR"])
                              ->where("website_id = ? ", $this->getWebsite()->website_id)
                              ->where("coupon_id =  0");
                              
        if(count($votingModel->getAdapter()->fetchCol($select)))
        {
            return true;
        }  
        
        return false;
    }
    
    public function getAllVotingByWebsite()
    {
        $votingModel = Ccc::getModel("voting/voting");
        $select = $votingModel->select()
                              ->from($votingModel->getTableName())
                              ->where("website_id = ? ", $this->getWebsite()->website_id)
                              ->where("coupon_id =  0");
                              
        $votes =  $votingModel->getAdapter()->fetchAll($select);
        $totalUsers = count($votes);
        
        $currentVote = 0;
        if(count($votes))
        {
            foreach($votes as $_vote)
            {
               $currentVote = (float)$currentVote + (float)$_vote["vote"]; 
            }
        }
        
        return array(
            "vote" => round((float)$currentVote/$totalUsers, 1),
            "totalUser" => (int)$totalUsers
        );
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
    
    public function getCouponViewUrl($id, $wId)
    {
        return $this->baseUrl(Ccc::getModel("core/url_rewrite")->getUrlKey($this->getWebsite()->website_id, Core_Model_Url_Rewrite::ENTITY_STORE))."?cpn_id=".$id;
    }
    
    public function getReviewsByWebsite()
    {
        $reviewModel = Ccc::getModel("review/review");
        $select = $reviewModel->select()
                              ->from($reviewModel->getTableName())
                              ->where("website_id = ? ", $this->getWebsite()->website_id)
                              ->where("approved = ? ", Review_Model_Review::APPROVED)
                              ->order("created_date DESC");
                              
        $reviews =  $reviewModel->getAdapter()->fetchAll($select);
        return $reviews;
    }
    
    public function getSaveReviewUrl()
    {
        return $this->url(array('module'=>'website','controller'=>'index','action'=>'save-review'));
    }
}
