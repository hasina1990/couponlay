<?php
class User_NewslatterController extends Ccc_Controller_Action_Front
{
    public function preDispatch()
    {
        parent::preDispatch();
        $this->_setLayout('front');
    }
                                                             
    public function subscribeAction()
    {    
        try
        {   
            if(!$this->getRequest()->isPost())
            {
                throw new Ccc_Controller_Action_Front_Exception('Invalid Request.');
            }
            
            if($errors = $this->_validate())
            {
                throw new Ccc_Controller_Action_Json_Exception(json_encode($errors));
            }
            
            $email = trim($this->getRequest()->getPost('email', null)); 
            
            $user = Ccc::getModel("user/user")->createRow();
            $user->email = $email;
            $user->is_enabled = User_Model_User::IS_ENABLED_YES;
            $user->created_date = date("Y-m-d H:i:s");
            if(!$user->validate())
            {
                throw new Ccc_Controller_Action_Json_Exception(json_encode($user->getErrorsWithColumn()));
            }
            
            $select = Ccc::getModel("user/user")->select();
            $select->where("email = ?", $user->email);
            
            if($duplicateUser =  Ccc::getModel("user/user")->fetchRow($select))
            {
               $user = $duplicateUser; 
            }
            
            if($storeId = (int)$this->getRequest()->getPost("website_id", 0))
            {
                $stores = array();;
                if($user->website_id)
                {
                   $stores = explode(",",$user->website_id); 
                }
                
                $stores[] = $storeId;
                $user->website_id = implode(",", array_values($stores)); 
            }
            else
            {
                $user->website_id = "website";
            }
            
            $user->save();
             
            Ccc::getModel("user/session")->setSession("subscribe_user", $user);
             
            $response = array(
                    'responseType'=>"success",
                    'message'=>$this->_getTranslate()->translate('You have successfully subscribed to our newslatter. Thank you for subscribing us.'),
                    'redirectURL' => $this->view->baseUrl("subscription"),
                    'redirectType' => 'location'
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
        
        $email = $this->getRequest()->getPost('email', null);
        if(!isset($email))
        {
            $errors["email"] = "Please enter your Email.";
        }
        elseif(!$email = trim($email))
        {
            $errors["email"] = "Please enter your Email.";
        }
        else
        {
            $validator = new Zend_Validate_EmailAddress();
            if(!$validator->isValid($email))
            {
                $errors['email'] = $this->_getTranslate()->translate("Please enter valid email.");
            }
        }
        
        return $errors;
    }
}
