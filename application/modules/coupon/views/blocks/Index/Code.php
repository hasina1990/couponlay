<?php
class Coupon_View_Block_Index_Code extends User_View_Block_Widget_Grid
{
    protected $_recordPerPage = 10;
    protected $_couponData = null;
    
    public function __construct()                     
    {
        parent::__construct();
        $this->setTemplate('index/code.phtml');
    }
    
    public function setCouponData($couponId)
    {
        $couponModel = Ccc::getModel('coupon/coupon');
        $websiteModel = Ccc::getModel('website/website');
        $select =  $couponModel->select()
                    ->setIntegrityCheck(false)
                    ->from(array('C'=>$couponModel->getTableName()),array('name','short_description','code','end_date','coupon_id', 'terms_condition'))
                    ->join(array('W'=>$websiteModel->getTableName()), "C.website_id = W.website_id" ,array('website_icon'=>'W.icon','website_name'=>'W.name'))
                    ->where('coupon_id = ?',$couponId);
                    
        $this->_couponData = $couponModel->getAdapter()->fetchRow($select); 
        return $this;
    }
    
    public function getCouponData()
    {
        return $this->_couponData;
    }
         
    public function getRequestObject()
    {
        $object = Zend_Controller_Front::getInstance();
        return $object;
    }   
}
