<?php

class Admin_Cron_ScheduleController extends Ccc_Controller_Action_Admin
{
    /**
    *	Index Action. This function show listing page for Profile.
	*	@return void.
    **/
    public function indexAction()
    {
		$this->_setTitle('Manage Cron Schedule')->_setActiveTab('system');
        Ccc::getModel("admin/session")->unsetSession("cron_id");
		$this->getResponse()->appendBody(Ccc::getBlock('admin/cron_schedule_index')->toHtml());
        $this->getHelper('viewRenderer')->setNoRender(true);
    }
    
    public function indexJsonAction()
    {
        $this->_setTitle('Manage Crons')->_setActiveTab('system');
        try
        {
            $response = array(
                'responseType'=>"success",
                'content'=>array(
                    'element'=>'main-container',
                    'html'=>Ccc::getBlock('admin/cron_schedule_index')->toHtml()
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
    
    public function gridAction()
    {
        try
        {
            $response = array(
                'responseType'=>"success",
                'content'=>array(
                    'element'=>'grid-list',
                    'html'=>Ccc::getBlock('admin/cron_schedule_grid')->toHtml()
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
    
    /**
    *	Reset Action. This function is used to reset Profile search data.
	*	@return void.
    **/
    public function resetAction()
    {   
        $this->_setTitle('Manage Cron Schedule')->_setActiveTab('system');
        $this->getRequest()->setActionName('grid');
        Ccc::getModel('admin/search')->unsetSearch(); 
        
        $response = array(
            'responseType'    => "success",
            'message'        => '',
            'redirectURL'    => $this->view->url(array("action"=>"grid", "page"=>1))
        );
        $this->getResponse()->appendBody(Zend_Json::encode($response));        
        $this->getHelper('viewRenderer')->setNoRender(true);
        $this->getHelper('layout')->disableLayout();
    }
    
    public function deleteAction()
    {
        $this->_setTitle('Manage Cron Schedule')->_setActiveTab('system');
        try
        {
            if(!$this->getRequest()->isPost())
            {
                throw new Ccc_Controller_Action_Admin_Exception($this->_getTranslate()->translate('Invalid Request.'));
            }
            
            $cronIds = $this->getRequest()->getPost('cron_schedule', array());
            
            if(!$cronIds)
            {
                throw new Ccc_Controller_Action_Admin_Exception($this->_getTranslate()->translate('Please select atleast one record to delete.'));
            }
            
            $cnt = 0;
            foreach($cronIds as $_id)
            {
                $cron = Ccc::getModel("cron/cron_schedule")->find($_id);
                
                if($cron->valid())
                {
                    $cron->current()->delete();
                    $cnt++;
                }
            }
            
            $message = $this->_getTranslate()->translate("%s record(s) were deleted successfully.");
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