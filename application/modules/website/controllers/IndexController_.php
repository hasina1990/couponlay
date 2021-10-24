<?php
class Website_IndexController extends Ccc_Controller_Action_Front
{
    public function preDispatch()
    {                             
        parent::preDispatch();
        $this->_setLayout('front');
    }
    
    public function indexAction()
    {
        if(!$id = (int)$this->getRequest()->getParam("website_id", null))
        {
            throw new Exception("invalid request.");
        }
         
        $website = Ccc::getModel("website/website")->fetchRow("website_id = ".(int)$id);
        if(!$website)
        {
            throw new Exception("invalid request.");
        }
       
        $website->view_count = (int)$website->view_count + 1;
        $website->updated_date = date("Y-m-d H:i:s");
        $website->save();
         
        Ccc::getModel("user/session")->setSession("website", $website);
       
        $block = Ccc::getBlock("website/index_index");
        
        $codeCount = $block->setCollectionCount()->getCodeCount();
        $dealCount = $block->setCollectionCount()->getDealCount();
        Ccc::getModel("user/session")->setSession("codeCount", $codeCount);
        Ccc::getModel("user/session")->setSession("dealCount", $dealCount);
        Ccc::getModel("user/session")->unsetSession("search");
        
        $this->setTitle($website->seo_title);
        $this->setMetaTag("description", $website->seo_description);
        $this->setMetaTag("keywords", $website->seo_keyword);
        $this->setMetaTag("application-name", Ccc::getSingleton('config/system_config')->getSystemConfig('general/website/seo_title'));
        $this->setMetaTag("application-url", Ccc::getSingleton('config/system_config')->getSystemConfig('website/base/url'));
        
        $this->getResponse()->appendBody($block->toHtml());
        $this->getHelper('viewRenderer')->setNoRender(true);
    }
    
    public function saveReviewAction()
    {
        try
        {   
            if(!$this->getRequest()->isPost())
            {
                throw new Ccc_Controller_Action_Front_Exception('Invalid Request.');
            }
            
            $review = $this->getRequest()->getPost("review");
            if(!count($review))
            {
                $review = $this->getRequest()->getPost("review");
            }
            
            if(!isset($review["review"]) || !trim($review["review"]))
            {
                throw new Exception('Please enter Review');
            }
            if(!isset($review["name"]) || !trim($review["name"]))
            {
                throw new Exception('Please enter name');
            }
           
            if(!isset($review["website_id"]))
            {
                throw new Ccc_Controller_Action_Json_Exception(json_encode(array("website_id"=>"please select store for which you want to subscribe.")));
            }
            elseif(!$review["website_id"] = trim($review["website_id"]))
            {
               throw new Ccc_Controller_Action_Json_Exception(json_encode(array("website_id"=>"please enter valid store for which you want to subscribe."))); 
            }
            
            $website = Ccc::getModel("website/website")->fetchRow("website_id = '".$review["website_id"]."'");
            if(!$website)
            {
                throw new Exception('Please select valid website.'); 
            }
            $reviewRow = Ccc::getModel("review/review")->createRow();
            $reviewRow->setFromArray($review);
            $reviewRow->created_date = date('Y-m-d H:i:s');
            $reviewRow->updated_date = date('Y-m-d H:i:s');
            $reviewRow->save();
            
            $response = array(
                        'responseType'=>"success",
                        'message'=>'Your review is awaiting moderation.',
                        'redirectURL'=>$this->view->baseUrl(Ccc::getModel("core/url_rewrite")->getUrlKey($review["website_id"], Core_Model_Url_Rewrite::ENTITY_STORE)),
                        'redirectType' => 'location',
                        'error' => 0
                        );
            
        }
        catch(Ccc_Controller_Action_Json_Exception $e)
        {
            $response = array(
                        'responseType'=>"failure",  
                        'message'=>json_decode($e->getMessage()),
                        'redirectURL'=>$this->view->baseUrl(Ccc::getModel("core/url_rewrite")->getUrlKey($review["website_id"], Core_Model_Url_Rewrite::ENTITY_STORE)),
                        'redirectType' => 'location',
                        'error' => 1
                        );
        }
        catch(Exception $e)
        {
            $response = array(
                        'responseType'=>"failure", 
                        'message'=>$e->getMessage(),
                        'redirectURL'=>$this->view->baseUrl(Ccc::getModel("core/url_rewrite")->getUrlKey($review["website_id"], Core_Model_Url_Rewrite::ENTITY_STORE)),
                        'redirectType' => 'location',
                        'error' => 1
                        );
        }
        
        $this->getResponse()->appendBody(Zend_Json::encode($response));
        $this->getHelper('viewRenderer')->setNoRender(true);
        $this->getHelper('layout')->disableLayout();
    }
}