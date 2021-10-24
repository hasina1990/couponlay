<?php
class Admin_View_Block_Coupon_Index_Create extends Admin_View_Block_Abstract
{
    protected $_coupon = null;
    public function __construct()
    {
        parent::__construct();
        $this->setTemplate('coupon/index/create.phtml');
    }
    
    public function getCoupon()
    {
        if(!$this->_coupon)
        {
            $object = Zend_Controller_Front::getInstance();
            if($id = (int)$object->getRequest()->getParam('id', 0))
            {
                $coupon = Ccc::getModel("coupon/coupon")->find($id);
                if(!$coupon->valid())
                {
                    throw new Exception($this->_getTranslate()->translate('Coupon ID is not valid.'));
                }
                
                $this->_coupon = $coupon->current();
            }
            else
            {
                $this->_coupon = Ccc::getModel("coupon/coupon")->createRow();
            }
        }
        
        return $this->_coupon;
    }
    
    public function getWebsites()
    {
        $websiteModel  = Ccc::getModel('website/website');
        
        $select = $websiteModel->select()
                            ->setIntegrityCheck(false)
                            ->from(array('W'=>$websiteModel->getTableName()),array('website_id', 'name'))
                            ->order("website_id ASC");
        
        return $websiteModel->getAdapter()->fetchPairs($select);
    }
    
    public function getIsEnabledOptions()
    {
        return array(
             Coupon_Model_Coupon::IS_ENABLED_YES => Coupon_Model_Coupon::IS_ENABLED_YES_TEXT,
             Coupon_Model_Coupon::IS_ENABLED_NO => Coupon_Model_Coupon::IS_ENABLED_NO_TEXT
        );
    }
    
    public function getCouponTypeOptions()
    {
        return array(
             Coupon_Model_Coupon::COUPON_TYPE_CODE => Coupon_Model_Coupon::COUPON_TYPE_CODE_TEXT,
             Coupon_Model_Coupon::COUPON_TYPE_DEAL => Coupon_Model_Coupon::COUPON_TYPE_DEAL_TEXT
        );
    }
    
    public function getIsExpiredOptions()
    {
        return array(
             Coupon_Model_Coupon::IS_EXPIRED_NO => Coupon_Model_Coupon::IS_EXPIRED_NO_TEXT,
             Coupon_Model_Coupon::IS_EXPIRED_YES => Coupon_Model_Coupon::IS_EXPIRED_YES_TEXT
        );
    }
    
    public function getIsHotCouponOptions()
    {
        return array(
             0 => "",
             Coupon_Model_Coupon::IS_HOT_COUPON_YES => Coupon_Model_Coupon::IS_HOT_COUPON_YES_TEXT,
             Coupon_Model_Coupon::IS_HOT_COUPON_NO => Coupon_Model_Coupon::IS_HOT_COUPON_NO_TEXT
        );
    }
    
    public function getIsNewCouponOptions()
    {
        return array(
             0 => "",
             Coupon_Model_Coupon::IS_NEW_COUPON_YES => Coupon_Model_Coupon::IS_NEW_COUPON_YES_TEXT,
             Coupon_Model_Coupon::IS_NEW_COUPON_NO => Coupon_Model_Coupon::IS_NEW_COUPON_NO_TEXT
        );
    }
                 
    public function getIndexJsonUrl()
    {
        return $this->url(array('action'=>'index-json','id'=>null));
    }
    
    public function getSaveUrl()
    {
        return $this->url(array('action'=>'save'));
    }
}
