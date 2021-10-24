<?php
class Review_IndexController extends Ccc_Controller_Action_Front
{
    public function preDispatch()
    {
        echo 11;die;
        //parent::preDispatch();
        $this->_setLayout('front');
    }
    
    protected function _authenticate()
    {
        return array('save');
    }
    
    public function indexAction()
    {
        echo 11;die;
        try
        {
            Ccc::getModel("user/session")->unsetSession("search");
            $user = Ccc::getModel("user/session")->getSession("subscribe_user");
            if(!$user->user_id)
            {
                 throw new Exception("invalid reuqest");
            }         
            
            $this->setTitle('Manage User Subscription'); 
            $this->getResponse()->appendBody(Ccc::getBlock("user/subscription_index")->toHtml());
            $this->getHelper('viewRenderer')->setNoRender(true);
        }
        catch(Exception $e)
        {
            $this->_redirect("/");
        }
    }
    
    public function saveAction()
    {
        echo 11;die;
        try
        {   
            if(!$this->getRequest()->isPost())
            {
                throw new Ccc_Controller_Action_Front_Exception('Invalid Request.');
            }
            
            $review = $this->getRequest()->getPost("review");
            if(!isset($review["review"]))
            {
                throw new Exception('Please enter Review');
            }
            if(!isset($review["name"]))
            {
                throw new Exception('Please enter name');
            }
            
            if(!isset($review["website_id"]))
            {
                throw new Ccc_Controller_Action_Json_Exception(json_encode(array("website_id"=>"please select store for which you want to subscribe.")));
            }
            elseif(!$data["store"] = trim($data["store"]))
            {
               throw new Ccc_Controller_Action_Json_Exception(json_encode(array("website_id"=>"please enter valid store for which you want to subscribe."))); 
            }
            
            $website = Ccc::getModel("website/website")->fetchRow("website_id = '".$review["website_id"]."'");
            if(!$website)
            {
                throw new Exception('Please select valid website.'); 
            }
            
            $review = Ccc::getModel("review/review");
            $review->setFromArray($review);
            $review->created_date = date('Y-m-d H:i:s');
            $review->updated_date = date('Y-m-d H:i:s');
            $review->save();
            
            $response = array(
                        'responseType'=>"success",
                        'message'=>'Your review is pending for approval',
                        'error' => 0
                        );
            
        }
        catch(Ccc_Controller_Action_Json_Exception $e)
        {
            $response = array(
                        'responseType'=>"failure",  
                        'message'=>json_decode($e->getMessage()),
                        'error' => 1
                        );
        }
        catch(Exception $e)
        {
            $response = array(
                        'responseType'=>"failure", 
                        'message'=>array('common'=>array($e->getMessage())),
                        'error' => 1
                        );
        }
        
        $this->getResponse()->appendBody(Zend_Json::encode($response));
        $this->getHelper('viewRenderer')->setNoRender(true);
        $this->getHelper('layout')->disableLayout();
    }
    
    public function unsubscribeAction()
    {
        try
        {
            Ccc::getModel("user/session")->unsetSession("search");
            
            $postData = $this->getRequest()->getParams();
            if(!isset($postData["i"]))
            {
                throw new Exception("invalid request.");
            }

            $user = Ccc::getModel("user/user")->fetchRow("MD5(email) = '".(string)$postData["i"]."'");
            if(!$user)
            {
                throw new Exception("invalid user");
            }

            Ccc::getModel("user/session")->setSession("subscribe_user", $user);
            Ccc::getModel("user/session")->setSession("subscribe_website", null);
            
            if(isset($postData["s"]))
            {
                $website = Ccc::getModel("website/website")->fetchRow("MD5(website_id) = '".$postData["s"]."'");
                if($website)
                {
                    Ccc::getModel("user/session")->setSession("subscribe_website", $website); 
                }
            }
             
            $this->setTitle('Unsubscribe'); 
            $this->getResponse()->appendBody(Ccc::getBlock("user/subscription_unsubscribe")->toHtml());
            $this->getHelper('viewRenderer')->setNoRender(true);
        }
        catch(Exception $e)
        {
            echo $e->getMessage(); die;
            $this->_redirect("/");
        }
    }
}
