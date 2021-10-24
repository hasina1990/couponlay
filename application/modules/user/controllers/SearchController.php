<?php
class User_SearchController extends Ccc_Controller_Action_Front
{                                                      
    public function indexAction()
    {    
        try
        {   
            session_unset();
            if(!$this->getRequest()->isPost())
            {
                throw new Ccc_Controller_Action_Front_Exception('Invalid Request.');
            }
            
            if($errors = $this->_validate())
            {
                throw new Ccc_Controller_Action_Json_Exception(json_encode($errors));
            }
            
            $search = strtolower(trim($this->getRequest()->getPost('search', null)));
            
            $urlRewriteModel = Ccc::getModel("core/url_rewrite");
            $websiteModel =  Ccc::getModel("website/website");
            $select =  $websiteModel->select()
                                    ->from($websiteModel->getTableName())
                                    ->where("name Like ?", "%".$search."%")
                                    ->limit(1);
            
            $website = $websiteModel->fetchRow($select);
            if(!$website)
            {
               $url =  $this->view->baseUrl("search/q:".$search);     
            } 
            else
            {
                $url = $this->view->baseUrl($website->getUrlKey());
            }
            
            $response = array(
                    'responseType'=>"success",
                    'message'=>"",
                    'redirectType' => 'location',
                    'redirectURL' => $url
                    );
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
    
    protected function _validate()
    {
        $errors = array();
        
        $search = $this->getRequest()->getPost('search', null);
        if(!isset($search))
        {
            $errors["search"] = "Please enter search text.";
        }
        elseif(!$search = trim($search))
        {
            $errors["search"] = "Please enter search text.";
        }
        
        return $errors;
    }
    
    public function searchAction()
    {
        $this->_setLayout('front');
       $query = $this->getRequest()->getParam("q");
       $query = explode(":", $query);
       $this->view->query = $query[1];
    }
}
