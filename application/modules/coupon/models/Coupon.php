<?php
class Coupon_Model_Coupon extends Core_Model_Table_Abstract
{
	protected $_name = 'coupon';
    protected $_primary = 'coupon_id';
    protected $_rowClass = 'Coupon_Model_Coupon_Row';
    protected $_rowsetClass = 'Coupon_Model_Coupon_Rowset';
  
    const COUPON_TYPE_CODE = 1;
    const COUPON_TYPE_DEAL  = 2; 
    const COUPON_TYPE_CODE_TEXT = 'Code';
    const COUPON_TYPE_DEAL_TEXT  = 'Deal';
    
    const IS_ENABLED_YES = 1;
    const IS_ENABLED_NO  = 2; 
    const IS_ENABLED_YES_TEXT = 'Yes';
    const IS_ENABLED_NO_TEXT  = 'No';
    
    const IS_EXPIRED_YES = 1;
    const IS_EXPIRED_NO  = 2; 
    const IS_EXPIRED_YES_TEXT = 'Yes';
    const IS_EXPIRED_NO_TEXT  = 'No';
    
    const IS_HOT_COUPON_YES = 1;
    const IS_HOT_COUPON_NO  = 2; 
    const IS_HOT_COUPON_YES_TEXT = 'Yes';
    const IS_HOT_COUPON_NO_TEXT  = 'No';
    
    const IS_NEW_COUPON_YES = 1;
    const IS_NEW_COUPON_NO  = 2; 
    const IS_NEW_COUPON_YES_TEXT = 'Yes';
    const IS_NEW_COUPON_NO_TEXT  = 'No';
    
    public function getCouponTypeOptions()
    {
        $options = array(
            Coupon_Model_Coupon::COUPON_TYPE_CODE => Coupon_Model_Coupon::COUPON_TYPE_CODE_TEXT,
            Coupon_Model_Coupon::COUPON_TYPE_DEAL  => Coupon_Model_Coupon::COUPON_TYPE_DEAL_TEXT
        );
        
        return $options;
    }
              
    public function getIsEnabledOptions()
    {
        $options = array(
            Coupon_Model_Coupon::IS_ENABLED_YES => Coupon_Model_Coupon::IS_ENABLED_YES_TEXT,
            Coupon_Model_Coupon::IS_ENABLED_NO  => Coupon_Model_Coupon::IS_ENABLED_NO_TEXT
        );
        return $options;
    }
    
    public function getIsExpiredOptions()
    {
        $options = array(
            Coupon_Model_Coupon::IS_EXPIRED_YES => Coupon_Model_Coupon::IS_EXPIRED_YES_TEXT,
            Coupon_Model_Coupon::IS_EXPIRED_NO  => Coupon_Model_Coupon::IS_EXPIRED_NO_TEXT
        );
        
        return $options;
    }
}