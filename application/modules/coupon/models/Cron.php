<?php
class Coupon_Model_Cron extends Core_Model_Table_Abstract
{
    protected $_coupons = null;
    protected $_user = null;
    protected $_store = null;
    protected $_urlRewrite = array();
    
    public function setCouponAsExpired()
    {
        try
        {
            $couponModel = Ccc::getModel("coupon/coupon");
            $select =  $couponModel->select()
                                   ->from(array("C"=>$couponModel->getTableName()), array("coupon_id"))
                                   ->where("C.is_enabled = ?", Coupon_Model_Coupon::IS_ENABLED_YES) 
                                   ->where("C.is_expired = ?", Coupon_Model_Coupon::IS_EXPIRED_NO)
                                   ->where("DATE(end_date) <= DATE('".date("Y-m-d H:i:s")."')")
                                   ->limit(100);
           
           $coupons = $couponModel->getAdapter()->fetchCol($select);       
           if(!count($coupons))
           {             
                return $this;  
           }
           
           $query = "UPDATE `".$couponModel->getTableName()."` SET `is_expired` = ".Coupon_Model_Coupon::IS_EXPIRED_YES.", `is_enabled` = ".Coupon_Model_Coupon::IS_ENABLED_NO.", `updated_date` = '".date("Y-m-d H:i:s")."' WHERE `coupon_id` IN (".implode(",", $coupons).")";
           
           $couponModel->getAdapter()->query($query);
           
           return $this;
        }
        catch(Exception $e)
        {
        }
    }
    
    public function sendEmailToHomeSubscribedUsers()
    {
        try
        {
            $userModel =  Ccc::getModel("user/user");
            
            $select =  $userModel->select()
                                ->from(array("U"=>$userModel->getTableName()), array("user_id", "email"))
                                ->where("website_id = 'website'")
                                ->where("U.is_enabled = ?", User_Model_User::IS_ENABLED_YES);
            
            $users =  $userModel->getAdapter()->fetchPairs($select); 
            if(!count($users))
            {       
                return $this;
            }
            
            $couponModel = Ccc::getModel("coupon/coupon");
            $select =  $couponModel->select()
                                   ->from(array("C"=>$couponModel->getTableName()), array("website_id"))
                                   ->where("C.is_enabled = ?", Coupon_Model_Coupon::IS_ENABLED_YES) 
                                   ->where("C.is_expired = ?", Coupon_Model_Coupon::IS_EXPIRED_NO)
                                   ->where("DATE(created_date) = DATE('".date("Y-m-d H:i:s")."')")
                                   ->order("created_date DESC")
                                   ->group("website_id") 
                                   ->limit(10);
           
           $stores = $couponModel->getAdapter()->fetchCol($select);       
           if(!count($stores))
           {             
                return $this;  
           }
           
           $websiteModel = Ccc::getModel("website/website");
           
           $select =  $couponModel->select()
                                   ->setIntegrityCheck(false)
                                   ->from(array("C"=>$couponModel->getTableName()), array("*"))
                                   ->join(array("W"=>$websiteModel->getTableName()), "C.website_id = W.website_id", array("icon", "website_name"=>"W.name"))
                                   ->where("C.is_enabled = ?", Coupon_Model_Coupon::IS_ENABLED_YES) 
                                   ->where("C.is_expired = ?", Coupon_Model_Coupon::IS_EXPIRED_NO)
                                   ->where("DATE(C.created_date) = DATE('".date("Y-m-d H:i:s")."')")
                                   ->order("C.created_date DESC")
                                   ->where("C.website_id IN (?)", $stores)
                                   ->limit(10);
           
           $this->_coupons =  $couponModel->fetchAll($select); 
           if(!count($this->_coupons))
           {             
                return $this;  
           }
           
           $website_name     = Ccc::getModel("config/system_config")->getSystemConfig('general/website/name');
           $subject = "{$website_name} : Top Coupons of This Week";
           $sender_email     = Ccc::getModel("config/system_config")->getSystemConfig('general/contact/sender_email');
             
           foreach($users as $_user)
           {
               try
               {
                    $this->setUser($_user)->sendHomeSubscriptionEmail($website_name, $subject, $sender_email);
               }
               catch(Exception $e)
               {
                   continue;
               }
           }
           
           return $this;
        }
        catch(Exception $e)
        {
        }
    }
    
    public function getUrlKey($id)
    {
        if(!isset($this->_urlRewrite[$id]))
        {
            $urlRewriteModel = Ccc::getModel("core/url_rewrite");
            $select = $urlRewriteModel->select()
                           ->from(array($urlRewriteModel->getTableName()))
                           ->where("id_path = ?", $id);
            
            $urlRewrite = $urlRewriteModel->fetchRow($select);
            if(!$urlRewrite)
            {
                $this->_urlRewrite[$id] = $id;
            }
            
            $this->_urlRewrite[$id] = $urlRewrite->request_path;
        }
        
        return $this->_urlRewrite[$id];
    }
    
    public function getWebsiteViewUrl($websiteId)
    {
        return Ccc::getModel("core/url")->getWebsiteUrl()."/".$this->getUrlKey("store/".$websiteId)."/i/".md5($this->_user)."/t/".strtotime(date("Y-m-d H:i:s"));
    }
    
    public function getCouponViewUrl($couponId, $storeId)
    {
        return Ccc::getModel("core/url")->getWebsiteUrl()."/".$this->getUrlKey("store/".$storeId)."/c/".md5($couponId)."/i/".md5($this->_user)."/t/".strtotime(date("Y-m-d H:i:s"));
    }
    
    public function getHomeUnsubscriptionUrl()
    {
        return Ccc::getModel("core/url")->getWebsiteUrl()."/subscription/unsubscribe/i/".md5($this->_user)."/t/".strtotime(date("Y-m-d H:i:s"));
    }
    
    public function getStoreUnsubscriptionUrl($id)
    {
        return Ccc::getModel("core/url")->getWebsiteUrl()."/subscription/unsubscribe/s/".md5($id)."/i/".md5($this->_user)."/t/".strtotime(date("Y-m-d H:i:s"));
    }
    
    public function getHomeUrl()
    {
        return Ccc::getModel("core/url")->getWebsiteUrl()."/user/index/index/i/".md5($this->_user)."/t/".strtotime(date("Y-m-d H:i:s"));
    }
    
    public function sendHomeSubscriptionEmail($website_name, $subject, $sender_email)
    {
        try
        {
            $view = new Zend_View;
            $view->setBasePath(APPLICATION_PATH . '/modules/user/views/');
            $params = array(
                'coupons'=>$this->_coupons, 
                'websiteName'=>$website_name,
                'cron' => $this
            );

            $content = $view->partial('template/coupon_home_subscription.phtml', $params);      
            $mail = new Zend_Mail();              
            $mail->setFrom($sender_email, $website_name);            
            $mail->addTo($this->_user);       
            $mail->setSubject($subject);
            $mail->setBodyHtml($content);
            if($mail->send())
            {
                return true;
            }

            return false;
        }
        catch(Exception $e)
        {
            return false;
        }
    }
    
    public function setUser($user)
    {
        $this->_user = $user;
        return $this;
    }
    
    public function setStore($store)
    {
        $this->_store = $store;
        return $this;
    }
    
     public function sendCouponsEmailToSubscribedUser()
     {
        try
        {
            $couponModel = Ccc::getModel("coupon/coupon");
            $websiteModel = Ccc::getModel("website/website");
            
            $select =  $couponModel->select()
                                   ->setIntegrityCheck(false)
                                   ->from(array("C"=>$couponModel->getTableName()), array())
                                   ->join(array("W"=>$websiteModel->getTableName()), "C.website_id = W.website_id", array("website_id", "name"=>"W.name", "icon"))
                                   ->where("C.is_enabled = ?", Coupon_Model_Coupon::IS_ENABLED_YES) 
                                   ->where("C.is_expired = ?", Coupon_Model_Coupon::IS_EXPIRED_NO)
                                   ->where("DATE(C.created_date) = DATE('".date("Y-m-d H:i:s")."')")
                                   ->order("C.created_date DESC")
                                   ->group("website_id");
           
           $stores = $couponModel->getAdapter()->fetchAll($select);    
           if(!count($stores))
           {             
                return $this;  
           }
           
           $website_name     = Ccc::getModel("config/system_config")->getSystemConfig('general/website/name');
           $sender_email     = Ccc::getModel("config/system_config")->getSystemConfig('general/contact/sender_email');
           
           $userModel =  Ccc::getModel("user/user");
           foreach($stores as $_store)
           {
                $select =  $userModel->select()
                                    ->from(array("U"=>$userModel->getTableName()), array("user_id", "email"))
                                    ->where("FIND_IN_SET('".(int)$_store["website_id"]."', U.website_id)")
                                    ->where("U.is_enabled = ?", User_Model_User::IS_ENABLED_YES);
                
                $users =  $userModel->getAdapter()->fetchPairs($select); 
                if(!count($users))
                {       
                    continue;
                }
                
                $select =  $couponModel->select()
                                   ->setIntegrityCheck(false)
                                   ->from(array("C"=>$couponModel->getTableName()), array("*"))
                                   ->join(array("W"=>$websiteModel->getTableName()), "C.website_id = W.website_id", array("icon", "website_name"=>"W.name"))
                                   ->where("C.is_enabled = ?", Coupon_Model_Coupon::IS_ENABLED_YES) 
                                   ->where("C.is_expired = ?", Coupon_Model_Coupon::IS_EXPIRED_NO)
                                   ->where("DATE(C.created_date) = DATE('".date("Y-m-d H:i:s")."')")
                                   ->order("C.created_date DESC")
                                   ->where("C.website_id = ?", (int)$_store["website_id"])
                                   ->limit(10);
           
                $this->_coupons =  $couponModel->fetchAll($select);
                if(!count($this->_coupons))
                {             
                    continue;
                }
                
                $subject = "{$website_name} : ".ucfirst($_store["name"])."'s Popular Coupons";
                foreach($users as $_user)
                {
                   try
                   {
                        $this->setUser($_user)->setStore($_store)->sendEmailToSubscribedStoreUsers($website_name, $subject, $sender_email);
                   }
                   catch(Exception $e)
                   {
                       continue;
                   }
                }
           }
           
           return $this;
        }
        catch(Exception $e)
        {
        }
     }
     
    public function sendEmailToSubscribedStoreUsers($website_name, $subject, $sender_email)
    {
        try
        {
            $view = new Zend_View;
            $view->setBasePath(APPLICATION_PATH . '/modules/user/views/');
            $params = array(
                'coupons'=>$this->_coupons, 
                'websiteName'=>$website_name,
                'cron' => $this,
                'store' => $this->_store
            );

            $content = $view->partial('template/coupon_store_subscription.phtml', $params);     
            $mail = new Zend_Mail();              
            $mail->setFrom($sender_email, $website_name);            
            $mail->addTo($this->_user);       
            $mail->setSubject($subject);
            $mail->setBodyHtml($content);
            if($mail->send())
            {
                return true;
            }

            return false;
        }
        catch(Exception $e)
        {
            return false;
        }
    }
}