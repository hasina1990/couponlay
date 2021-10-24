<?php
class Admin_Block_FooterController extends Ccc_Controller_Action_Admin
{
    public function indexAction()
    {
        try
        {
            $this->_setTitle('Manage Footer')->_setActiveTab('system');        
            $this->getResponse()->appendBody(Ccc::getBlock("admin/block_footer_index")->toHtml());
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
                        'html'=>Ccc::getBlock("admin/block_footer_index")->toHtml(),
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
                        'html'=>Ccc::getBlock("admin/block_footer_grid")->toHtml(),
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

    public function saveAction()
    {
        try
        {
            if(!$this->getRequest()->isPost())
            {
                throw new Exception($this->_getTranslate()->translate('Invalid Request.'));
            }
            
            $postData   = $this->getRequest()->getPost('config');
            if(!$postData)            
            {
                throw new Exception($this->_getTranslate()->translate('Invalid data posted.'));
            }
            
            $configGroup = Ccc::getModel("config/system_config_group")->fetchRow("name = 'Website'");
            
            $this->_saveConfigData(Config_Model_System_Config::FOOETR_ONE_TITLE, (string)trim($postData["footer_one_title"]), $configGroup);
            $this->_saveConfigData(Config_Model_System_Config::FOOETR_ONE_DESCRIPTION, (string)trim($postData["footer_one_description"]), $configGroup);
            $this->_saveConfigData(Config_Model_System_Config::FOOETR_TWO_TITLE, (string)trim($postData["footer_two_title"]), $configGroup);
            $this->_saveConfigData(Config_Model_System_Config::FOOETR_TWO_DESCRIPTION, (string)trim($postData["footer_two_description"]), $configGroup);
            $this->_saveConfigData(Config_Model_System_Config::FOOETR_THREE_TITLE, (string)trim($postData["footer_three_title"]), $configGroup);
            $this->_saveConfigData(Config_Model_System_Config::FOOETR_THREE_DESCRIPTION, (string)trim($postData["footer_three_description"]), $configGroup);
            
            $response = array(
                'responseType'=>"success",
                'message'=>$this->_getTranslate()->translate('Footer settings were saved successfully.'),
                'redirectURL' => $this->view->baseUrl('admin/block_footer/index-json')
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
    
    protected function _saveConfigData($code, $data, $group)
    {
        $homeConfig = Ccc::getModel("config/system_config")->fetchRow("access_key = '".$code."'");
        if(!$homeConfig)
        {
            $homeConfig =  Ccc::getModel("config/system_config")->createRow();
        }

        $homeConfig->system_config_group_id = $group->system_config_group_id;
        $homeConfig->access_key = $code;
        $homeConfig->field_type = Config_Model_System_Config::FIELD_TYPE_TEXT_KEY;
        $homeConfig->value = $data;
        $homeConfig->save();
        
        return $this;
    }
}
