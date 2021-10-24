<?php 
class Admin_Process_IndexController extends Ccc_Controller_Action_Admin
{
    public function indexAction()
    {                              
        $this->_setTitle('Process')->_setActiveTab('process');
        $this->getResponse()->appendBody(Ccc::getBlock("admin/process_index_index")->toHtml());
        $this->getHelper('viewRenderer')->setNoRender(true);
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
                        'html'=>Ccc::getBlock('admin/process_index_grid')->toHtml()
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

    public function processAction()
    {    
        $id = $this->getRequest()->getParam('id',0);
        try
        {
            if($id==5 || $id==8)
            {
                throw new Exception('Processing Error');
            }
            $response = array(
                'responseType'=>"success",
                'content'=>array(
                    array(
                        'nextElement'=>"data-ele-".(1+$id),
                        'element'=>'data-ele-'.$id,
                        'html'=>'Complete'
                    )
                ),
                'message'=>''
            );
        }
        catch(Exception $e)
        {
            $response = array(
                'responseType'=>"failure",
                'message'=>$this->_getTranslate()->translate($e->getMessage()),
                'element'=>'data-ele-'.$id,
            );
        }
        
        $this->getResponse()->appendBody(Zend_Json::encode($response));
        $this->getHelper('viewRenderer')->setNoRender(true);
        $this->getHelper('layout')->disableLayout();
    }
}