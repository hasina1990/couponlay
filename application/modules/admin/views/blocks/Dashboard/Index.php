<?php
class Admin_View_Block_Dashboard_Index extends Admin_View_Block_Widget_Grid
{
    public function __construct()
    {
        parent::__construct();
        $this->setTemplate('dashboard/index.phtml');
    }
    
    public function getTotalStoreCount()
    {
        $websiteModel          = Ccc::getModel('website/website');
        
        $select = $websiteModel->select()
                                ->setIntegrityCheck(false)
                                ->from(array('W'=>$websiteModel->getTableName()),array('website_id'));
                                
        return (int)count($websiteModel->getAdapter()->fetchCol($select));
    }
    
    public function getTotalActiveStoreCount()
    {
        $websiteModel          = Ccc::getModel('website/website');
        
        $select = $websiteModel->select()
                                ->setIntegrityCheck(false)
                                ->from(array('W'=>$websiteModel->getTableName()),array('website_id'))
                                ->where("W.is_enabled = ?", Website_Model_Website::IS_ENABLED_YES);
                                
        return (int)count($websiteModel->getAdapter()->fetchCol($select));
    }
    
    public function getTotalCouponsCount()
    {
        $couponModel          = Ccc::getModel('coupon/coupon');
        
        $select = $couponModel->select()
                                ->setIntegrityCheck(false)
                                ->from(array('C'=>$couponModel->getTableName()),array('coupon_id'));
                                
        return (int)count($couponModel->getAdapter()->fetchCol($select));
    }
    
    public function getTotalActiveCouponsCount()
    {
        $couponModel          = Ccc::getModel('coupon/coupon');
        
        $select = $couponModel->select()
                                ->setIntegrityCheck(false)
                                ->from(array('C'=>$couponModel->getTableName()),array('coupon_id'))
                                ->where("C.is_enabled = ?", Coupon_Model_Coupon::IS_ENABLED_YES)
                                ->where("C.is_expired = ?", Coupon_Model_Coupon::IS_EXPIRED_NO);
                                
        return (int)count($couponModel->getAdapter()->fetchCol($select));
    }
    
    public function getTotalExpiredCouponsCount()
    {
        $couponModel          = Ccc::getModel('coupon/coupon');
        
        $select = $couponModel->select()
                                ->setIntegrityCheck(false)
                                ->from(array('C'=>$couponModel->getTableName()),array('coupon_id'))
                                ->where("C.is_expired = ?", Coupon_Model_Coupon::IS_EXPIRED_YES);
                                
        return (int)count($couponModel->getAdapter()->fetchCol($select));
    }
}
