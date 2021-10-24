<?php
/**
*	Core_Model_Url
**/
class Core_Model_Url
{	
	/**
	*	Get Website Url Function. This function is used to get url of Website.
	*	@return url.
	**/
	public function getWebsiteUrl()
	{
        $front = Zend_Controller_Front::getInstance();
        if($front->getRequest())
        {
            $url = $front->getRequest()->getScheme().'://'.$front->getRequest()->getHttpHost().$front->getBaseUrl();        
            return $url;
        }
        else
        {
            $baseConfig = Ccc::getModel("config/system_config")->fetchRow("access_key = 'website/base/url'");
            if(!$baseConfig)
            {
                return null;
            }
            
            return $baseConfig->value;
        }
	}
}