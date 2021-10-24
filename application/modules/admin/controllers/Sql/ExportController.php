<?php
class Admin_Sql_ExportController extends Ccc_Controller_Action_Admin
{
    public function indexAction()
    {   
        $this->_setTitle('Export Database')->_setActiveTab('system');
		$this->getResponse()->appendBody(Ccc::getBlock('admin/sql_export_index')->toHtml());
        $this->getHelper('viewRenderer')->setNoRender(true);
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
                        'html'=>Ccc::getBlock('admin/sql_export_index')->toHtml()
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
    
    public function listAction()
    {    
        try
        { 
            $response = array(
                'responseType'=>"success",
                'content'=> array(
                    array(
                        'element'=>'grid-list',
                        'html'=>Ccc::getBlock('admin/sql_export_list')->toHtml()
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
    
    public function exportDbAction()
    {
        try
        {  
            $adapter = $this->getRequest()->getParam('adapter', null);
            if(!$adapter)
            {
                throw new Exception("invalid Database Adapter specified.");
            }
            
            $object = Zend_Registry::get($adapter); 
            if(!$object)
            {
                throw new Exception("invalid Database specified.");
            }
            
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="'.$adapter.'.sql"');
            $content = $this->_exportTables($object);
        }
        catch(Exception $e)
        {
            Ccc::getModel("admin/message")->setNamespace('error')->addMessage($this->_getTranslate()->translate($e->getMessage()));
            $this->_redirect('admin/sql_export/index');
        }
        
        $this->getResponse()->appendBody($content);
        $this->getHelper('viewRenderer')->setNoRender(true);
        $this->getHelper('layout')->disableLayout();
    }
    
    protected function _exportTables($adapter)
    {
         $sqlContent = null;
         $tables = $adapter->listTables();
         
         if(!$tables)  
         {
             throw new Exception("This Database has no table to export.");
         }
         
         $sqlContent = "SET FOREIGN_KEY_CHECKS=0;".PHP_EOL;
         $sqlContent .= "SET SQL_MODE='NO_AUTO_VALUE_ON_ZERO';".PHP_EOL;
         $sqlContent .= "SET time_zone = '+00:00';".PHP_EOL.PHP_EOL;

         foreach($tables as $_table)
         {
             $result =  $adapter->fetchPairs("SHOW CREATE TABLE ".$_table.";");
             $sqlContent .= $result[$_table].";".PHP_EOL.PHP_EOL;
         }
         
         $sqlContent .= "SET FOREIGN_KEY_CHECKS=1;";
         return $sqlContent; 
    }
}
