<?php
class Coupon_IndexController extends Ccc_Controller_Action_Front
{
    public function preDispatch()
    {
        parent::preDispatch();
        $this->_setLayout('front');
    }
    
    public function codeAction()
    {
        $this->_setLayout('popup');
        try
        {
            $couponId = $this->getRequest()->getParam('id',0);
            if(!$couponId)
            {
                throw new Exception("Invalid Request");
            }
            $coupon = Ccc::getModel('coupon/coupon')->fetchRow("coupon_id = ".$couponId); 
            if(!$coupon)
            {
                throw new Exception("Invalid Coupon");
            }  
            
            $response = array(
                'responseType'=>"success",
                'content'=>array(
                    'html'=>Ccc::getBlock('coupon/index_code')->toHtml()
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
        
        //$this->getResponse()->appendBody(Ccc::getBlock('coupon/index_deal')->toHtml());
        //$this->getResponse()->appendBody(Zend_Json::encode($response));
        //$this->getHelper('viewRenderer')->setNoRender(true);
        //$this->getHelper('layout')->disableLayout();
    }
    
    public function promoAction()
    {
        $this->_setLayout('popup');
        try
        {
            $couponId = $this->getRequest()->getParam('id',0);
            if(!$couponId)
            {
                throw new Exception("Invalid Request");
            }
            $coupon = Ccc::getModel('coupon/coupon')->fetchRow("coupon_id = ".$couponId); 
            if(!$coupon)
            {
                throw new Exception("Invalid Coupon");
            }  
            
            $response = array(
                'responseType'=>"success",
                'content'=>array(
                    'html'=>Ccc::getBlock('coupon/index_promo')->toHtml()
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
    }
        
    public function gridAction()
    {   
        try
        {   
            $response = array(
                'responseType'=>"success",
                'content'=>array(
                    'element'=>'grid-list',
                    'html'=>Ccc::getBlock('coupon/index_grid')->toHtml()
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
    
    public function viewImageAction()
    {
        try
        {
            $id = $this->getRequest()->getParam("id", 0);
            
            $agentModel = Ccc::getModel("agent/agent");
            $agent =  $agentModel->fetchRow("agent_id = ".(int)$id); 
            if(!$agent)
            {
                throw new Exception("Agent is not valid.");
            }           
            
            $file = $agent->getImageFile();
            if(!$file)
            {
                throw new Exception("file is not exist.");
            }
        }
        catch(Exception $e)
        {
            $file = Ccc::getModel("agent/agent")->getDefaultImage();
        }
        
        header('Content-Type: '. mime_content_type($file));
        $this->getResponse()->appendBody(file_get_contents($file));
        $this->getHelper('viewRenderer')->setNoRender(true);
        $this->getHelper('layout')->disableLayout();
    } 
      
}
