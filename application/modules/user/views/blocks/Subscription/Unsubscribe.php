<?php
class User_View_Block_Subscription_Unsubscribe extends User_View_Block_Widget_Grid
{   
	public function __construct()                     
	{
        parent::__construct();
		$this->setTemplate('subscription/unsubscribe.phtml');
	}

    public function getUser()
    {
        $user = Ccc::getModel("user/session")->getSession("subscribe_user");
        if(!$user->user_id)
        {
             throw new Exception("invalid user");
        }
        
        return $user;
    }
    
    public function isStoreUnSubscription()
    {
        if(Ccc::getModel("user/session")->getSession("subscribe_website") instanceof Website_Model_Website_Row)
        {
            return true;
        }
        
        return false;
    }
    
    public function getWebsiteId()
    {
        if(Ccc::getModel("user/session")->getSession("subscribe_website") instanceof Website_Model_Website_Row)
        {
            return  Ccc::getModel("user/session")->getSession("subscribe_website")->website_id;
        }
        
        return "all";
    }
           
    public function getSubscribeUserUrl()
    {
        return $this->url(array('module'=>'user', 'controller'=>'subscription', 'action'=>'subscribe'));
    }
    
    public function getSubscribedWebsites()
    {
        $user = Ccc::getModel("user/session")->getSession("subscribe_user");
        if(!$user->user_id)
        {
             throw new Exception("invalid user");
        }
           
          if(!$user->website_id)
          {
              return null;
          }
          
          $websiteModel     = Ccc::getModel("website/website");
          
          $select = $websiteModel->select()
                                ->from(array("W"=>$websiteModel->getTableName()),array("*"))
                                ->where("is_enabled = ?", Website_Model_Website::IS_ENABLED_YES)
                                ->where("website_id IN (?)", explode(",", $user->website_id))
                                ->order('W.name ASC');
          
         return $websiteModel->fetchAll($select);                        
    }
    
    public function getAllWebsites()
    {
          $websiteModel     = Ccc::getModel("website/website");
          
          $select = $websiteModel->select()
                                ->from(array("W"=>$websiteModel->getTableName()),array("name"))
                                ->where("is_enabled = ?", Website_Model_Website::IS_ENABLED_YES)
                                ->order('W.name ASC');
          
          if($this->getUser()->website_id)
          {
             $select->where("W.website_id NOT IN (?)", explode(",", $this->getUser()->website_id)); 
          }
         
         return $websiteModel->getAdapter()->fetchCol($select);                        
    }
}
