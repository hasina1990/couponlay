<?php
class Admin_Sql_TruncateController extends Ccc_Controller_Action_Admin
{
    protected $_table = array(
            "system_db" => array(
                            "banner",
                            "category",
                            "coupon",
                            "tag",
                            "url_rewrite",
                            "user",
                            "user_website",
                            "voting",
                            "website",
                            "page",
                            "menu",
                            "review"
                            )
    );

    public function resetDbAction()
    {
        try
        {
            $adapter = "system_db";
            if(!array_key_exists($adapter, $this->_table))
            {
                throw new Exception($this->_getTranslate()->translate("selected Database is not exist."));
            }
            
            $tables =  $this->_table[$adapter];
            $query = null;
            if(!empty($tables) && is_array($tables))
            {
                $object = Zend_Registry::get($adapter); 
                $query = $this->_processResetTable($object, $tables);
            }
           
            $response = array(
                'responseType'=>"success",
                'message'=>$this->_getTranslate()->translate('Database Reset successfully.'),
                'redirectURL'=>$this->view->url(array('action'=>'index-json'))
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
    
    protected function _processResetTable($adapter, $tables)
    {
        
         for($count = 0; $count < 5; $count++)
         {   
             $query = null;
             $query = "SET FOREIGN_KEY_CHECKS=0;";
             
             foreach($tables as $_table)
             {
                $query .= "TRUNCATE TABLE ".TABLE_PREFIX.$_table."; ";                 
             }
             
             $query .= "SET FOREIGN_KEY_CHECKS=1;";
             $adapter->exec($query); 
            
             return  $query;
         }
    }
}
