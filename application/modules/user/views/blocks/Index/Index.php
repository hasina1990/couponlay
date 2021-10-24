<?php
class User_View_Block_Index_Index extends Core_View_Block_Abstract
{
	public function __construct()
	{
		parent::__construct();
		$this->setTemplate('index/index.phtml');
	}  
    
    public function getBaseHomeWebsiteUrl()
    {
         return Ccc::getModel("config/system_config")->getSystemConfig("website/base/url");
    }
    
    public function getWebsiteName()
    {
        return Ccc::getModel("config/system_config")->getSystemConfig("general/website/name");
    }
    
    public function getFbPageUrl()
    {
        return Ccc::getModel("config/system_config")->getSystemConfig("general/website/facebook");
    }
    
    public function getGooglePageUrl()
    {
        return Ccc::getModel("config/system_config")->getSystemConfig("general/website/google");
    }
    
    public function getTwitterPageUrl()
    {
        return Ccc::getModel("config/system_config")->getSystemConfig("general/website/twitter");
    }
    
    public function getSearchUrl()
    {
        return $this->url(array('module'=>'user', 'controller'=>'search', 'action'=>'index'));
    }
    
    public function getSubscribeUserUrl()
    {
        return $this->url(array('module'=>'user', 'controller'=>'newslatter', 'action'=>'subscribe'));
    }
        
    public function getBanners()
    {
        $bannerModel = Ccc::getModel("banner/banner");
        
        $select =  $bannerModel->select()
                               ->from($bannerModel->getTableName())
                               ->where("is_ad = ?", Banner_Model_Banner::IS_AD_NO)
                               ->order("created_date ASC");

        return $bannerModel->fetchAll($select);                    
    }
    
    public function getNewslatterBlock()
    {
        return Ccc::getModel("block/block")->fetchRow("name = 'news_latter_block' AND is_enabled = ".Block_Model_Block::IS_ENABLED_YES);
    }
    
    public function getHomePageFirstBlock()
    {
        return Ccc::getModel("block/block")->fetchRow("name = 'home_page_block_1'");
    }
    
    public function getHomePageSecondBlock()
    {
        return Ccc::getModel("block/block")->fetchRow("name = 'home_page_block_2'");
    }
    
    public function getHomePageThirdBlock()
    {
        return Ccc::getModel("block/block")->fetchRow("name = 'home_page_block_3'");
    }
    
    public function getRecentlyAddedCouponAndDeals()
    {
        $couponModel = Ccc::getModel("coupon/coupon");
        $websiteModel = Ccc::getModel("website/website");
        
        $select =  $couponModel->select()
                               ->setIntegrityCheck(false) 
                               ->from(array("C"=>$couponModel->getTableName()))
                               ->join(array("W"=>$websiteModel->getTableName()), "C.website_id = W.website_id", array("icon", "website_name"=>"W.name"))
                               ->order("C.created_date DESC")
                               ->where("C.is_enabled = ?", Coupon_Model_Coupon::IS_ENABLED_YES)
                               ->where("C.is_expired = ?", Coupon_Model_Coupon::IS_EXPIRED_NO)
                               ->limit(15);
                              
        return $couponModel->getAdapter()->fetchAll($select);                    
    }
    
    public function getTopFifteenDeals()
    {
        $couponModel = Ccc::getModel("coupon/coupon");
        $websiteModel = Ccc::getModel("website/website");
        
        $select =  $couponModel->select()
                               ->setIntegrityCheck(false) 
                               ->from(array("C"=>$couponModel->getTableName()))
                               ->join(array("W"=>$websiteModel->getTableName()), "C.website_id = W.website_id", array("icon", "website_name"=>"W.name"))
                               ->where("type = ?", Coupon_Model_Coupon::COUPON_TYPE_DEAL)
                               ->order("C.view_count DESC")
                               ->where("C.is_enabled = ?", Coupon_Model_Coupon::IS_ENABLED_YES)
                               ->where("C.is_expired = ?", Coupon_Model_Coupon::IS_EXPIRED_NO)
                               ->limit(15);
                              
        return $couponModel->getAdapter()->fetchAll($select);                    
    }
    
    public function getTopFifteenCoupons()
    {
        $couponModel = Ccc::getModel("coupon/coupon");
        $websiteModel = Ccc::getModel("website/website");
        
        $select =  $couponModel->select()
                               ->setIntegrityCheck(false) 
                               ->from(array("C"=>$couponModel->getTableName()))
                               ->join(array("W"=>$websiteModel->getTableName()), "C.website_id = W.website_id", array("icon", "website_name"=>"W.name"))
                               ->where("type = ?", Coupon_Model_Coupon::COUPON_TYPE_CODE)
                               ->order("C.view_count DESC")
                               ->where("C.is_enabled = ?", Coupon_Model_Coupon::IS_ENABLED_YES)
                               ->where("C.is_expired = ?", Coupon_Model_Coupon::IS_EXPIRED_NO)
                               ->limit(15);
                              
        return $couponModel->getAdapter()->fetchAll($select);                    
    }
    
    public function getWebsiteUrl($id)
    {
         $website = Ccc::getModel("website/website")->fetchRow("website_id = ".(int)$id);
         if(!$website)
         {
             return $this->baseUrl();
         }
         
         return $this->baseUrl($website->getURLKey());
    }
    
    public function getAdvetisements()
    {
        $bannerModel = Ccc::getModel("banner/banner");
        
        $select =  $bannerModel->select()
                               ->from($bannerModel->getTableName())
                               ->where("is_ad = ?", Banner_Model_Banner::IS_AD_YES)
                               ->order("created_date ASC");

        return $bannerModel->fetchAll($select);                    
    }
    
    public function getWebsites()
    { 
        $websiteModel     = Ccc::getModel("website/website");
            
        $select = $websiteModel->select()
                                ->from(array("W"=>$websiteModel->getTableName()),array("*"))
                                ->where("is_enabled = ?", Website_Model_Website::IS_ENABLED_YES)
                                ->order('W.name DESC')
                                ->limit(16);
       
        return $websiteModel->fetchAll($select);
    }
    
    public function getStoreHomePageBlock()
    {
        return Ccc::getModel("block/block")->fetchRow("name = 'stores-at-home-page'");
    }
    
    public function getBannerAndAdvertiseHomePageBlock()
    {
        return Ccc::getModel("block/block")->fetchRow("name = 'banner-and-advertise-at-home'");
    }
    
    public function getAllWebsites()
    { 
        $websiteModel     = Ccc::getModel("website/website");
            
        $select = $websiteModel->select()
                                ->from(array("W"=>$websiteModel->getTableName()),array("*"))
                                ->where("is_enabled = ?", Website_Model_Website::IS_ENABLED_YES)
                                ->order('W.name Asc');
       
        return $websiteModel->fetchAll($select);
    }
    
    public function getAllWebsitesForAutoComplete()
    {
          $websiteModel     = Ccc::getModel("website/website");
          
          $select = $websiteModel->select()
                                ->from(array("W"=>$websiteModel->getTableName()),array("name"))
                                ->where("is_enabled = ?", Website_Model_Website::IS_ENABLED_YES)
                                ->order('W.name ASC');
         
         return $websiteModel->getAdapter()->fetchCol($select);                        
    }
    
    public function getCouponViewUrl($id, $wId)
    {
        return $this->baseUrl(Ccc::getModel("core/url_rewrite")->getUrlKey($wId, Core_Model_Url_Rewrite::ENTITY_STORE))."?cpn_id=".$id;
    }
}
