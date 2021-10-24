<?php
/**  
*	Admin_Cache_IndexController
**/
class Admin_Cache_IndexController extends Ccc_Controller_Action_Admin
{
    public function indexAction()
    {
        $this->_setTitle('Manage Caches')->_setActiveTab('system');
        $this->getResponse()->appendBody(Ccc::getBlock("admin/cache_index_index")->toHtml());
        $this->getHelper('viewRenderer')->setNoRender(true);
    }
    
    public function indexJsonAction()
    {
        try
        {
            $response = array(
                'responseType'=>"success",
                'content'=>array(
                    array(
                        'element'=>'main-container',
                        'html'=>Ccc::getBlock("admin/cache_index_index")->toHtml(),
                    )
                ),
                'message'=>''
            );
        }
        catch(Exception $e)
        {
            $response = array(
                'responseType'=>"failure",
                'message'=>$this->_getTranslate()->translate($e->getMessage())
            );
        }
        
        $this->getResponse()->appendBody(Zend_Json::encode($response));
        $this->getHelper('viewRenderer')->setNoRender(true);
        $this->getHelper('layout')->disableLayout();
    }
    
    public function resetAction()
    {   
        $this->getRequest()->setActionName('grid');
        Ccc::getModel('admin/search')->unsetSearch(); 
        
        $response = array(
            'responseType'    => "success",
            'message'        => '',//$this->_getTranslate()->translate("Page Reset"),
            'action'=>array(
                array(
                    'element'=>'main-container',
                    'url'=>$this->view->url(array("action"=>"grid", "page"=>1))
                )),
        );
        
        $this->getResponse()->appendBody(Zend_Json::encode($response));        
        $this->getHelper('viewRenderer')->setNoRender(true);
        $this->getHelper('layout')->disableLayout();       
    }
    
    public function gridAction()
    {
        try
        { 
            $response = array(
                'responseType'=>"success",
                'content'=>array(
                    array(
                        'element'=>'grid-list',
                        'html'=>Ccc::getBlock("admin/cache_index_grid")->toHtml(),
                    )
                ),
                'message'=>''
            );
        }
        catch(Exception $e)
        {
            $response = array(
                'responseType'=>"failure",
                'message'=>$this->_getTranslate()->translate($e->getMessage())
            );
        }
        
        $this->getResponse()->appendBody(Zend_Json::encode($response));
        $this->getHelper('viewRenderer')->setNoRender(true);
        $this->getHelper('layout')->disableLayout();
    }
        
    public function createAction()
    {
        try
        { 
            $response = array(
                'responseType'=>"success",
                'content'=>array(
                    array(
                        'element'=>'main-container',
                        'html'=>Ccc::getBlock("admin/cache_index_create")->toHtml(),
                    )
                ),
                'message'=>''
            );
        }
        catch(Exception $e)
        {
            $response = array(
                'responseType'=>"failure",
                'message'=>$this->_getTranslate()->translate($e->getMessage())
            );
        }
        
        $this->getResponse()->appendBody(Zend_Json::encode($response));
        $this->getHelper('viewRenderer')->setNoRender(true);
        $this->getHelper('layout')->disableLayout();
    }
    
    public function editAction()
    {
        try
        {
            $id = (int)$this->getRequest()->getParam('id', 0);  
            $cache = Ccc::getModel("cache/cache")->find($id);
            if(!$cache->valid())
            {
                throw new Ccc_Controller_Action_Admin_Exception($this->_getTranslate()->translate('Cache ID is not valid.'));
            }
            
            $this->_forward('create');            
        }
        catch(Exception $e)
        {
            $response = array(
                'responseType'=>"failure",
                'message'=>$this->_getTranslate()->translate($e->getMessage())
            );
            
            $this->getResponse()->appendBody(Zend_Json::encode($response));
            $this->getHelper('viewRenderer')->setNoRender(true);
            $this->getHelper('layout')->disableLayout();
        }
    }
    
    public function saveAction()
    {
        try
        {
            if(!$this->getRequest()->isPost())
            {
                throw new Exception($this->_getTranslate()->translate('Invalid Request.'));
            }
            
            $postData = $this->getRequest()->getPost('cache');
            if(!$postData)            
            {
                throw new Exception($this->_getTranslate()->translate('Invalid data posted.'));
            }
            
            $cacheModel = Ccc::getModel("cache/cache");
            if($id = $this->getRequest()->getParam('id', 0))
            {         
                $cache = $cacheModel->find($id);
                if(!$cache->valid())
                {
                    throw new Exception($this->_getTranslate()->translate('Invalid data posted.'));
                }
                $cache = $cache->current();
            }
            else
            {
                $cache = $cacheModel->createRow();
            }
            
            if(!$cache)
            {
                throw new Exception($this->_getTranslate()->translate('Invalid data posted.'));
            }
                      
            $cache->setFromArray($postData);
            
            if(!$cache->validate())
            {
                throw new Ccc_Controller_Action_Json_Exception($cache->getErrorsWithColumnInJsonFormat());
            } 
            
            if($cache->isDuplicateRecord())
            {
                throw new Ccc_Controller_Action_Json_Exception($this->_getTranslate()->translate(json_encode(array('code'=>'Cache with this code already exists.'))));
            }
            
            if($id = $this->getRequest()->getParam('id', 0))
            {         
               $cache->updated_date = date("Y-m-d H:i:s");
            }
            else
            {
                $cache->created_date = date("Y-m-d H:i:s");
            }
            $cache->save();
          
            $response = array(
                'responseType'=>"success",
                'message'=>$this->_getTranslate()->translate('Cache was saved successfully.'),
                'action'=>array(
                    array(
                        'element'=>'main-container',
                        'url'=>$this->view->baseUrl('admin/cache_index/index-json')
                    )),
            );
        }
        catch(Ccc_Controller_Action_Json_Exception $e)
        {
            $response = array(
                'responseType'=>"failure",
                'message'=>json_decode($this->_getTranslate()->translate($e->getMessage()),1)
            );
        }
        catch(Exception $e)
        {
            $response = array(
                'responseType'=>"failure",
                'message'=>$this->_getTranslate()->translate($e->getMessage()),
            );
        }
        
        $this->getResponse()->appendBody(Zend_Json::encode($response));        
        $this->getHelper('viewRenderer')->setNoRender(true);
        $this->getHelper('layout')->disableLayout();    
    }
  
    /**
    *	Update Action. This function update cache data.
	*	@return void.
    **/
    public function updateAction()
    {
        try
        {
            if(!$this->getRequest()->isPost())
            {
                throw new Exception($this->_getTranslate()->translate('Invalid Request.'));
            }
            
            $isEnabled = $this->getRequest()->getParam('is_enabled', array());
            if(!$isEnabled)
            {
                throw new Exception($this->_getTranslate()->translate('Invalid data posted.'));
            }
            
            foreach($isEnabled as $_id=>$_isEnabled)
            {
                $cache = Ccc::getModel("cache/cache")->find($_id);
                
                if($cache->valid())
                {
                    $cache = $cache->current();
                    $cache->is_enabled = $_isEnabled;
                    $cache->save();
                }
            }

            $response = array(
                'responseType'=>"success",
                'message'=>$this->_getTranslate()->translate("Cache(s) were updated successfully."),
                'action'=>array(
                    array(
                        'element'=>'main-container',
                        'url'=>$this->view->url(array('action'=>'grid'))
                    )),
            );
        }
        catch(Exception $e)
        {
            $response = array(
                'responseType'=>"failure",
                'message'=>$this->_getTranslate()->translate($e->getMessage()),
            );
        }

        $this->getResponse()->appendBody(Zend_Json::encode($response));
        $this->getHelper('viewRenderer')->setNoRender(true);
        $this->getHelper('layout')->disableLayout();
    }      
    
    public function cleanAction()
    {
        try
        {
            if(!$this->getRequest()->isPost())
            {
                throw new Exception($this->_getTranslate()->translate('Invalid Request'));
            }
            $postData = $this->getRequest()->getPost('selectedIds');
            if(!$postData)
            {
                throw new Exception($this->_getTranslate()->translate('Invalid data posted.'));
            }
            
            $ids = explode(',',$postData);   
            if(!count($ids))
            {
                 throw new Exception($this->_getTranslate()->translate('Please select atleast one cache to clear.'));
            }
            
            $frontendOptions = array(                
                'cached_entity' => $this
            );
            
            $cnt = 0;
            foreach($ids as $_id)
            {
                $cache = Ccc::getModel("cache/cache")->find($_id);
                
                if($cache->valid())
                {
                    $cache = $cache->current();
                    Ccc::getModel("cache/cache")->clearCache($cache->code);
                    $cnt++;
                }
            }
                        
            $message = "%s Cache(s) were cleaned successfully.";

            $response = array(
                'responseType'=>"success",
                'message'=>$this->_getTranslate()->translate(sprintf($message, $cnt)),
                'action'=>array(
                    array(
                        'element'=>'main-container',
                        'url'=>$this->view->url(array('action'=>'grid'))
                    )),
            );
        }
        catch(Exception $e)
        {
            $response = array(
                'responseType'=>"failure",
                'message'=>$this->_getTranslate()->translate($e->getMessage()),
            );
        }

        $this->getResponse()->appendBody(Zend_Json::encode($response));
        $this->getHelper('viewRenderer')->setNoRender(true);
        $this->getHelper('layout')->disableLayout();
    }    
}