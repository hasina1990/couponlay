<?php
/**  
*	Admin_Cache_IndexController
**/
class Admin_Cron_IndexController extends Ccc_Controller_Action_Admin
{
    public function indexAction()
    {
        $this->_setTitle('Manage Crons')->_setActiveTab('system');
        $this->getResponse()->appendBody(Ccc::getBlock("admin/cron_index_index")->toHtml());
        $this->getHelper('viewRenderer')->setNoRender(true);
    }
    
    public function indexJsonAction()
    {
        $this->_setTitle('Manage Crons')->_setActiveTab('system');
        try
        {
            if($this->getRequest()->getParam('cron_id', 0))
            {
                $cronId = $this->getRequest()->getParam('cron_id', 0);
                Ccc::getModel('admin/session')->setSession('cron_id', $cronId);
            }            
            
            $response = array(
                'responseType'=>"success",
                'content'=>array(
                    'element'=>'main-container',
                    'html'=>Ccc::getBlock('admin/cron_index_index')->toHtml()
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
            'redirectURL'    => $this->view->url(array("action"=>"grid", "page"=>1))
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
                    'element'=>'grid-list',
                    'html'=>Ccc::getBlock("admin/cron_index_grid")->toHtml(),
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
                    'element'=>'main-container',
                    'html'=>Ccc::getBlock("admin/cron_index_create")->toHtml(),
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
            $id = $this->getRequest()->getParam('id', 0);
            $cron = Ccc::getModel('cron/cron')->find($id);
            if(!$cron->valid())
            {
                throw new Exception($this->_getTranslate()->translate('Invalid Record Id.'));
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
            
            $postData = $this->getRequest()->getPost('cron', array());
            if(!$postData)            
            {
                throw new Exception($this->_getTranslate()->translate('Invalid data posted.'));
            }        
            
            $cronModel = Ccc::getModel("cron/cron");
            if($id = $this->getRequest()->getParam('id', 0))
            {
                $cron = $cronModel->find($id);
                if(!$cron->valid())
                {
                    throw new Exception($this->_getTranslate()->translate('Invalid data posted.'));
                }            
                $cron = $cron->current();                
                $postData['updated_at'] = date("Y-m-d H:i:s");
            }
            else
            {
                $postData['created_at'] = date("Y-m-d H:i:s");
                $cron = $cronModel->createRow();
            }
            
            $cron->setFromArray($postData);
            if(!$cron->validate())
            {
                throw new Ccc_Controller_Action_Json_Exception(($cron->getErrorsInJsonFormat()));
            }  
           
            $cron->save();
            
            if($postData['status'] == Cron_Model_Cron_Status::STATUS_ENABLED_NO)
            {
                $cronScheduleModel = Ccc::getModel("cron/cron_schedule");
                $cronScheduleModel->delete("cron_id = '".$cron->cron_id."' and status = '".Cron_Model_Cron_Schedule::STATUS_PENDING."'");                    
            }
            
            $response = array(
                'responseType'=>"success",
                'message'=>$this->_getTranslate()->translate('Cron was saved successfully.'),
                'redirectURL'=>$this->view->url(array('action'=>'index-json','id'=>null))
            );
        }
        catch(Ccc_Controller_Action_Json_Exception $e)
        {
            $response = array(
                'responseType'=>"failure",
                'message'=>$this->_getTranslate()->translate($e->getMessage())
            );
        }
        catch(Exception $e)
        {
            $message = json_encode(array($this->_getTranslate()->translate($e->getMessage())));
            $response = array(
                'responseType'=>"failure",
                'message'=>$message
            );
        }
        $this->getResponse()->appendBody(Zend_Json::encode($response));
          
        $this->getHelper('viewRenderer')->setNoRender(true);
        $this->getHelper('layout')->disableLayout();        
    }
      
    public function deleteAction()
    {
        $this->_setTitle('Manage Crons')->_setActiveTab('system');
        try
        {
            if(!$this->getRequest()->isPost())
            {
                throw new Ccc_Controller_Action_Admin_Exception($this->_getTranslate()->translate('Invalid Request.'));
            }
            
            $cronIds = $this->getRequest()->getPost('crons', array());
            
            if(!$cronIds)
            {
                throw new Ccc_Controller_Action_Admin_Exception($this->_getTranslate()->translate('Please select atleast one cron to delete.'));
            }
            
            $cnt = 0;
            foreach($cronIds as $_id)
            {
                $cron = Ccc::getModel("cron/cron")->find($_id);
                
                if($cron->valid())
                {
                    $cron->current()->delete();
                    $cnt++;
                }
            }
            
            $message = $this->_getTranslate()->translate("%s cron(s) were deleted successfully.");
            $response = array(
                'responseType'=>"success",
                'message'=>$this->_getTranslate()->translate(sprintf($message, $cnt)),
                'redirectURL'=> $this->view->url(array('action'=>'grid'))
            );
        }
        catch(Ccc_Controller_Action_Admin_Exception $e)
        {
            $message = json_encode(array($this->_getTranslate()->translate($e->getMessage())));
            $response = array('error'=>1, 'message'=>$message);
        }
        catch(Exception $e)
        {
            $message = json_encode(array($this->_getTranslate()->translate($e->getMessage())));
            $response = array('error'=>1, 'message'=>$message);
        }

        $this->getResponse()->appendBody(Zend_Json::encode($response));
          
        $this->getHelper('viewRenderer')->setNoRender(true);
        $this->getHelper('layout')->disableLayout();
    } 
}