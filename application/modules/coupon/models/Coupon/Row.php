<?php
class Coupon_Model_Coupon_Row extends Core_Model_Table_Row_Abstract
{
	protected $_tableClass = 'Coupon_Model_Coupon';
    protected $_validationClassModel = 'coupon/coupon_row_validation';
    protected $_isURLKeyUpdated = false;
    
    protected function _afterSave()
    {
        if($this->_isURLKeyUpdated)
        {
            Ccc::getModel("core/url_rewrite")->saveReWriteURL($this);
        }
        
        return parent::_afterSave();
    }
    
    public function _beforeSave()
    {
        if($this->_cleanData['url_key'] != $this->url_key)
        {
            $this->_isURLKeyUpdated = true;
        }

        return parent::_beforeSave();
    }
                   
    public function isDuplicateRecord()
    {
        $select = $this->select();    
        if($this->name)
        {
            $select->where('name = ?', $this->name);
        }
                                   
        if($this->coupon_id)
        {
            $select->where('coupon_id != ?', $this->coupon_id);
        }
        
        return $this->getTable()->fetchRow($select);
    }
    
    public function isDuplicateURLRecord()
    {
        $select = $this->select();    
                   
        if($this->url_key)
        {
            $select->where('url_key = ?', $this->url_key);
        }
        
        if($this->coupon_id)
        {
            $select->where('coupon_id != ?', $this->coupon_id);
        }
        
        return $this->getTable()->fetchRow($select);
    }
    
    public function sendEmailToSubscribedUser()
    {
        $userModel = Ccc::getModel("user/user");
        
        $select =  $userModel->select()
                             ->from(array("U"=>$userModel->getTableName()), array("user_id", "email"))
                             ->where("(FIND_IN_SET(".$this->website_id.", U.website_id)) OR (U.website_id = 'website')")
                             ->where("U.is_enabled = ?", User_Model_User::IS_ENABLED_YES);
                             
         $users = $userModel->getAdapter()->fetchAll($select);
         if(!count($users))
         {
             return $this;
         } 
         
         $website = Ccc::getModel("website/website")->fetchRow("website_id = ".(int)$this->website_id); 
         if(!$website)
         {
             return $this;
         } 
         
         $website_name     = Ccc::getModel("config/system_config")->getSystemConfig('general/website/name');
         $subject = "{$website_name} : ".ucfirst($website->name)." ".ucfirst($this->name);
         $sender_email     = Ccc::getModel("config/system_config")->getSystemConfig('general/contact/sender_email');
         
         foreach($users as $_user)
         {
             $value = $this->sendCouponSubscriptionMail($_user, $sender_email, $subject, $website, $website_name);
         }
         
         return $this;
    } 
    
    public function sendCouponSubscriptionMail($user, $sender_email, $subject, $website, $website_name)
    {
        try
        {
             $view = new Zend_View;
             $view->setBasePath(APPLICATION_PATH . '/modules/user/views/');
             $params = array(
                        'coupon'=>$this, 
                        'website' =>  $website,
                        'websiteName'=>$website_name,
                        'user' => $user
                        );
                          
            $content = $view->partial('template/coupon_subscription.phtml', $params);
            $mail = new Zend_Mail();              
            $mail->setFrom($sender_email, $website_name);            
            $mail->addTo($user["email"]);       
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