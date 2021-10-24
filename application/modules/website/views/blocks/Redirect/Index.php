<?php
class Website_View_Block_Redirect_Index extends User_View_Block_Widget_Grid
{
   protected $_couponData = array();
    
	public function __construct()                     
	{
        parent::__construct();
		$this->setTemplate('redirect/index.phtml');
	}
    
    public function getCoupon()
    {
        $couponModel = Ccc::getModel('coupon/coupon');
        $websiteModel = Ccc::getModel('website/website');
        
        $coupon = Ccc::getModel("user/session")->getSession("redirect_coupon", null);
        if($coupon)
        {
            $select =  $couponModel->select()
                        ->setIntegrityCheck(false)
                        ->from(array('C'=>$couponModel->getTableName()),array('url'))
                        ->join(array('W'=>$websiteModel->getTableName()), "C.website_id = W.website_id" ,array('website_name'=>'W.name','destination_url','website_url'=>'website_url'))
                        ->where('coupon_id = ?',$coupon->coupon_id);
                        
            $this->_couponData = $couponModel->getAdapter()->fetchRow($select);
        }
        elseif($website = Ccc::getModel("user/session")->getSession("redirect_website", null))
        {
             $this->_couponData["url"] = "";
             $this->_couponData["website_name"] = $website->name;
             $this->_couponData["destination_url"] = $website->destination_url;
             $this->_couponData["website_url"] = $website->website_url;
        }
        
        return $this->_couponData;
    }
    
    public function getRedirectionUrl()
    {
        if($this->_couponData["url"] = trim($this->_couponData["url"]))
        {
            return $this->_couponData["url"];
        }
        elseif($this->_couponData["destination_url"] = trim($this->_couponData["destination_url"]))
        {
            return $this->_couponData["destination_url"];
        }
        elseif($this->_couponData["website_url"] = trim($this->_couponData["website_url"]))
        {
            return $this->_couponData["website_url"];
        }
        else
        {
            return $this->baseUrl();
        }
    }
   
    public function getRequestObject()
    {
        $object = Zend_Controller_Front::getInstance();
        return $object;
    }
}
