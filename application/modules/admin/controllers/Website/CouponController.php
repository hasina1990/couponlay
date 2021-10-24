<?php
class Admin_Website_CouponController extends Ccc_Controller_Action_Admin
{
    public function indexAction()
    {
        try
        {
            $this->_setTitle('Manage Store Coupons')->_setActiveTab('wesbite');        
            $this->getResponse()->appendBody(Ccc::getBlock("admin/website_coupon_index")->toHtml());
            $this->getHelper('viewRenderer')->setNoRender(true);
        }
        catch(Exception $e)
        {
            
        }
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
                        'html'=>Ccc::getBlock("admin/website_coupon_index")->toHtml(),
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
            'message'        => '',
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
                        'html'=>Ccc::getBlock("admin/website_coupon_grid")->toHtml(),
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
                        'html'=>Ccc::getBlock("admin/website_coupon_create")->toHtml(),
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
            $coupon = Ccc::getModel("coupon/coupon")->find($id);
            if(!$coupon->valid())
            {
                throw new Ccc_Controller_Action_Admin_Exception($this->_getTranslate()->translate('Coupon ID is not valid.'));
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
            
            $postData = $this->getRequest()->getPost('coupon',array());
            if(!$postData)            
            {
                throw new Exception($this->_getTranslate()->translate('Invalid data posted.'));
            }
            
            $couponModel = Ccc::getModel("coupon/coupon");
            if($id = $this->getRequest()->getParam('id', 0))
            {         
                $coupon = $couponModel->find($id);
                if(!$coupon->valid())
                {
                    throw new Exception($this->_getTranslate()->translate('Invalid data posted.'));
                }
                
                $coupon = $coupon->current();
                $postData["updated_date"]  = date('Y-m-d H:i:s');
            }
            else
            {
                $coupon = $couponModel->createRow();
                $postData["created_date"]  = date('Y-m-d H:i:s');
            }
            
            $coupon->setFromArray($postData);
            if(!$coupon->validate())
            {
                $errors = $coupon->getErrorsWithColumn();
            } 
            
            if(count($errors))
            {
                throw new Ccc_Controller_Action_Json_Exception(json_encode($errors));
            }
            
            if($coupon->isDuplicateRecord())
            {
                throw new Ccc_Controller_Action_Json_Exception($this->_getTranslate()->translate(json_encode(array('name'=>'coupon with this name already exists.'))));
            }
            
            $coupon->save();
                          
            $response = array(
                'responseType'=>"success",
                'message'=>$this->_getTranslate()->translate('Coupon was saved successfully.'),
                'action'=>array(
                    array(
                        'element'=>'main-container',
                        'url'=>$this->view->baseUrl('admin/website_coupon/index-json/wid/'.(int)$coupon->website_id)
                    )),
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
        try
        {
            if(!$this->getRequest()->isPost())
            {
                throw new Exception($this->_getTranslate()->translate('Invalid Request.'));
            }
            
            $postData = $this->getRequest()->getPost('selectedIds');
            if(!$postData)
            {
                throw new Exception($this->_getTranslate()->translate('Invalid data posted.'));
            }
            
            $ids = explode(',', $postData);
            if(!count($ids))
            {
                throw new Exception($this->_getTranslate()->translate('Please select atleast one record to delete.'));
            }
            
            $cnt = 0;
            foreach($ids as $_id)
            {
                $coupon = Ccc::getModel("coupon/coupon")->find($_id);
                if($coupon->valid())
                {
                    $coupon = $coupon->current();
                    $coupon->delete();
                    $cnt++;
                }
            }
            
            $message  = $this->_getTranslate()->translate("%s Coupon(s) were deleted successfully.");
            
            $response = array(
                'responseType' => "success",
                'message' => $this->_getTranslate()->translate(sprintf($message, $cnt)),
                'action'=>array(
                    array(
                        'element'=>'main-container',
                        'url'=>$this->view->url(array("action"=>"grid"))
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
