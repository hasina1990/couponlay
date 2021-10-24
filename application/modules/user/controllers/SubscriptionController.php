<?php
class User_SubscriptionController extends Ccc_Controller_Action_Front
{
    public function preDispatch()
    {
        parent::preDispatch();
        $this->_setLayout('front');
    }
    
    public function indexAction()
    {
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
    
    public function subscribeAction()
    {
        try
        {   
            if(!$this->getRequest()->isPost())
            {
                throw new Ccc_Controller_Action_Front_Exception('Invalid Request.');
            }
            
            $data = $this->getRequest()->getPost("user");
            if(!isset($data["email"]))
            {
                throw new Ccc_Controller_Action_Json_Exception(json_encode(array("email"=>"invalid email")));
            }
            
            $user = Ccc::getModel("user/session")->getSession("subscribe_user");  
            $user = Ccc::getModel("user/user")->fetchRow("user_id = ".$user->user_id);
                    
            $unsubscribe = $this->getRequest()->getPost("unsubsribefromall", null);
            if($unsubscribe)
            {
                if($unsubscribe == "all")
                {
                    $user->website_id = null;
                    $user->save();
                    
                    $message = "You've unsubscribed successfully!";
                }
                else
                {
                    $website = Ccc::getModel("website/website")->fetchRow("website_id = ".(int)$unsubscribe."");
                    if($website)
                    {
                        $websites = explode(",", $user->website_id);
                        $websites = array_flip($websites);  
                        unset($websites[$website->website_id]);
                        $websites = array_flip($websites);
                           
                        if(count($websites))
                        {
                            $user->website_id = implode(",", $websites);
                        }
                        else
                        {
                            $user->website_id = null;
                        }
                        
                        $user->save();
                        $message = "You've unsubscribed from ".ucfirst($website->name)." store successfully!";
                    }
                }
                
                Ccc::getModel("user/session")->setSession("subscribe_user", $user); 
                
                $response = array(
                        'responseType'=>"success",
                        'message'=>$message,
                        'content' => Ccc::getBlock("user/subscription_index")->toHtml(),
                        'content_element' => 'main-content'
                        );
            }
            else
            {
                if(!isset($data["store"]))
                {
                    throw new Ccc_Controller_Action_Json_Exception(json_encode(array("store"=>"please select store for which you want to subscribe.")));
                }
                elseif(!$data["store"] = trim($data["store"]))
                {
                   throw new Ccc_Controller_Action_Json_Exception(json_encode(array("store"=>"please enter valid store for which you want to subscribe."))); 
                }
                
                $website = Ccc::getModel("website/website")->fetchRow("name = '".$data["store"]."'");
                if(!$website)
                {
                    throw new Ccc_Controller_Action_Json_Exception(json_encode(array("store"=>"please enter valid store for which you want to subscribe."))); 
                }
                
                $websites = null;
                if($user->website_id == "website")
                {
                    $user->website_id = null;
                }
                else
                {
                    $websites = explode(",", $user->website_id);
                }
                
                $websites[] = $website->website_id;                      
                $user->website_id = implode(",", $websites);
                $user->save();
                Ccc::getModel("user/session")->setSession("subscribe_user", $user); 
                
                $response = array(
                        'responseType'=>"success",
                        'message'=>$this->_getTranslate()->translate('You have successfully subscribed to '.ucfirst($website->name).' store.'),
                        'content' => Ccc::getBlock("user/subscription_index")->toHtml(),
                        'content_element' => 'main-content'
                        );
            }
        }
        catch(Ccc_Controller_Action_Json_Exception $e)
        {
            $response = array(
                        'responseType'=>"failure",  
                        'message'=>json_decode($e->getMessage())
                        );
        }
        catch(Exception $e)
        {
            $response = array(
                        'responseType'=>"failure", 
                        'message'=>array('common'=>array($e->getMessage()))
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
