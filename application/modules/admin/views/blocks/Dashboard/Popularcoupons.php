<?php
class Admin_View_Block_Dashboard_Popularcoupons extends Admin_View_Block_Widget_Grid
{
    public function __construct()
    {
        parent::__construct();
        $this->setTemplate('dashboard/popularcoupons.phtml'); 
    }
    
    protected function _prepareQuery()
    {
        $couponModel          = Ccc::getModel('coupon/coupon');
        $websiteModel          = Ccc::getModel('website/website');
        
        $select = $couponModel->select()
                                ->setIntegrityCheck(false)
                                ->from(array('C'=>$couponModel->getTableName()),array('*'))
                                ->joinLeft(array('W'=>$websiteModel->getTableName()), "C.website_id = W.website_id", array("website_name"=>"W.name"))
                                ->where("C.type = ?", Coupon_Model_Coupon::COUPON_TYPE_CODE)
                                ->order("C.view_count DESC");
        return $select;
    }
    
    public function getCollection()
    {
        $select = $this->_prepareQuery();
        $select->limit(5);  
        return Ccc::getModel('coupon/coupon')->fetchAll($select);
    }
    
    public function getTotalCouponCount()
    {
        $select = $this->_prepareQuery();
        return count(Ccc::getModel('coupon/coupon')->getAdapter()->fetchCol($select));
    }
   
    public function getIsEnabled($coupon)
    {
        if($coupon->is_enabled == Coupon_Model_Coupon::IS_ENABLED_YES)
        {
            return Coupon_Model_Coupon::IS_ENABLED_YES_TEXT;
        }
        
        return Coupon_Model_Coupon::IS_ENABLED_NO_TEXT;
    }
    
    public function getIsExpired($coupon)
    {
        if($coupon->is_expired == Coupon_Model_Coupon::IS_EXPIRED_YES)
        {
            return Coupon_Model_Coupon::IS_EXPIRED_YES_TEXT;
        }
        
        return Coupon_Model_Coupon::IS_EXPIRED_NO_TEXT;
    }
    
    public function getManageWebsiteUrl()
    {
        return $this->url(array('module'=>'admin','controller'=>'coupon_index','action'=>'index-json','page'=>1, 'sort'=>'C.view_count', 'dir'=>'desc'),null,true);
    }
}
