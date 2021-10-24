<?php
class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{
	protected function _initDbAdaptersToRegistry()
    {
        $resource = $this->getPluginResource('multidb');
        $resource->init();
        
        if($dbs = $resource->getOptions())
        {
            foreach($dbs as $key => $_db)
            {
                Zend_Registry::set("{$key}_db", $resource->getDb($key));
            }
        }
    }

    protected function _initConfig()
    {
        $config = new Zend_Config($this->getOptions());
        Zend_Registry::set('config', $config);
        return $config;
    }
    
    protected function _initMailTransport()
    {
        $config = array(
                            'auth' => 'login',
                            'username' => 'hplancer',
                            'password' => 'zd5W1G68ho',
                            'ssl' => 'ssl',
                            'port' => 465
                    );
                    
        $transport = new Zend_Mail_Transport_Smtp("mail.hplancer.com", $config);
        if($transport)
        {
            Zend_Mail::setDefaultTransport($transport);
        }
        /*$resource = $this->getPluginResource('multidb');
        
        if($resource->getOptions())
        {
            $db = Zend_Registry::get('system_db');
            
            $select = $db->select()->from(TABLE_PREFIX."system_config", array("access_key", "value"))
                        ->where("access_key = 'system/smtp/server' OR access_key = 'system/smtp/password' OR access_key = 'system/smtp/enabled' OR access_key = 'general/contact/sender_email'");
                        
            $config_data = $db->fetchPairs($select);
            
            if(count($config_data)==4)
            {
                if($config_data['system/smtp/enabled'] == 1)
                {
                    $config = array('auth' => 'login',
                            'username' => $config_data['general/contact/sender_email'],
                            'password' => $config_data['system/smtp/password'],
                            'ssl' => 'tls'
                    );
             
                    $transport = new Zend_Mail_Transport_Smtp($config_data['system/smtp/server'], $config);
                    
                    if($transport)
                    {
                        Zend_Mail::setDefaultTransport($transport);
                    }                    
                }
            }
        }  */
    }
}

