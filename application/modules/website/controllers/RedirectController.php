<?php
class Website_RedirectController extends Ccc_Controller_Action_Front
{
    public function preDispatch()
    {                             
        parent::preDispatch();
        $this->_setLayout('popup');
    }
    
    public function indexAction()
    {
        $couponId = $this->getRequest()->getParam("coupon_id", null);
        $webiteId = $this->getRequest()->getParam("s", null);
        
        if(!($couponId || $webiteId))
        {
            $this->_redirect('/');
        }
       
        $couponId = (int)$couponId;
        $webiteId = (int)$webiteId;
        
        if($couponId)
        {
            $coupon = Ccc::getModel("coupon/coupon")->fetchRow("coupon_id = ".(int)$couponId);
        }
        elseif($webiteId)
        {
            $website = Ccc::getModel("website/website")->fetchRow("website_id = ".(int)$webiteId);
        }        
        
        if($coupon)
        {
            $coupon->view_count = (int)$coupon->view_count + 1;
            $coupon->updated_date = date("Y-m-d H:i:s");
            $coupon->save();
            
            Ccc::getModel("user/session")->setSession("redirect_coupon", $coupon);
        }
        elseif($website)
        {
            Ccc::getModel("user/session")->setSession("redirect_website", $website);
        }
        
        $this->setTitle("Redirect to shop in process...");
        $this->getResponse()->appendBody(Ccc::getBlock("website/redirect_index")->toHtml());
        $this->getHelper('viewRenderer')->setNoRender(true);
    }
}