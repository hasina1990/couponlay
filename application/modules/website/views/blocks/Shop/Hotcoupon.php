<?php
class Website_View_Block_Shop_Hotcoupon extends Core_View_Block_Abstract
{
	public function __construct()
	{
		parent::__construct();
		$this->setTemplate('shop/hotcoupon.phtml');
	}  
  
    public function getTopFifteenCoupons()
    {
        $couponModel = Ccc::getModel("coupon/coupon");
        $websiteModel = Ccc::getModel("website/website");
        
        $select =  $couponModel->select()
                               ->setIntegrityCheck(false) 
                               ->from(array("C"=>$couponModel->getTableName()))
                               ->join(array("W"=>$websiteModel->getTableName()), "C.website_id = W.website_id", array("icon", "website_name"=>"W.name"))
                               ->where("C.is_hot_coupon = ?", Coupon_Model_Coupon::IS_HOT_COUPON_YES)
                               ->order("C.created_date DESC")
                               ->limit(15);
                              
        return $couponModel->getAdapter()->fetchAll($select);                    
    }
}
