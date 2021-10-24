<?php
class Admin_Home_IndexController extends Ccc_Controller_Action_Admin
{
    public function indexAction()
    {
        try
        {
            $this->_setTitle('Manage General Settings')->_setActiveTab('home');        
            $this->getResponse()->appendBody(Ccc::getBlock("admin/home_index_index")->toHtml());
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
                        'html'=>Ccc::getBlock("admin/home_index_index")->toHtml(),
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
                 
    public function gridAction()
    {
        try
        { 
            $response = array(
                'responseType'=>"success",
                'content'=>array(
                    array(
                        'element'=>'grid-list',
                        'html'=>Ccc::getBlock("admin/home_index_grid")->toHtml(),
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
            
            $this->_saveConfigData(Config_Model_System_Config::WEBSITE_NAME, (string)trim($postData["website_name"]), $configGroup);
            $this->_saveConfigData(Config_Model_System_Config::WEBSITE_SEO_TITLE, (string)trim($postData["website_seo_title"]), $configGroup);
            $this->_saveConfigData(Config_Model_System_Config::WEBSITE_SEO_DESCRIPTION, (string)trim($postData["website_seo_description"]), $configGroup);
            $this->_saveConfigData(Config_Model_System_Config::WEBSITE_SEO_KEYWORD, (string)trim($postData["website_seo_keyword"]), $configGroup);
            $this->_saveConfigData(Config_Model_System_Config::WEBSITE_FACEBOOK, (string)trim($postData["website_fb_page"]), $configGroup);
            $this->_saveConfigData(Config_Model_System_Config::WEBSITE_TWITTER, (string)trim($postData["website_twitter_page"]), $configGroup);
            $this->_saveConfigData(Config_Model_System_Config::WEBSITE_GOOGLE, (string)trim($postData["website_google_page"]), $configGroup);
            
            if(count($_FILES))
            {
                $homeConfig = Ccc::getModel("config/system_config")->fetchRow("access_key = '".Config_Model_System_Config::WEBSITE_LOGO."'");
                if(!$homeConfig)
                {
                    $homeConfig =  Ccc::getModel("config/system_config")->createRow();
                }

                $homeConfig->system_config_group_id = $configGroup->system_config_group_id;
                $homeConfig->access_key = Config_Model_System_Config::WEBSITE_LOGO;
                $homeConfig->field_type = Config_Model_System_Config::FIELD_TYPE_TEXT_KEY;
                $homeConfig->save();
        
                Ccc::getModel('config/uploader')->setConfig($homeConfig)->setFile($_FILES['website_logo'])->saveConfigImage();
            }
             
            $response = array(
                'responseType'=>"success",
                'message'=>$this->_getTranslate()->translate('General settings were saved successfully.'),
                'redirectURL' => $this->view->baseUrl('admin/home_index/index-json')
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
