<?php
class Admin_View_Block_Process_Index_Grid extends Admin_View_Block_Abstract
{
    public function __construct()
    {
        parent::__construct();
        $this->setTemplate('process/index/grid.phtml');
    }
        
    public function getCollection()
    {
        return array(
            $this->url(array('action'=>'process','id'=>1)),
            $this->url(array('action'=>'process','id'=>2)),
            $this->url(array('action'=>'process','id'=>3)),
            $this->url(array('action'=>'process','id'=>4)),
            $this->url(array('action'=>'process','id'=>5)),
            $this->url(array('action'=>'process','id'=>6)),
            $this->url(array('action'=>'process','id'=>7)),
            $this->url(array('action'=>'process','id'=>8)),
            $this->url(array('action'=>'process','id'=>9)),
            $this->url(array('action'=>'process','id'=>10)),
        );
    }
    
    public function getProcessImageUrl()
    {
        return $this->baseUrl('skin/admin/images/ajax-loader.gif'); 
    }

    public function getSuccessImageUrl()
    {
        return $this->baseUrl('skin/admin/images/success.gif'); 
    }
    public function getErrorImageUrl()
    {
        return $this->baseUrl('skin/admin/images/error.gif'); 
    }
    public function getObject()
    {
        $object = Zend_Controller_Front::getInstance();
        return $object->getRequest()->getParam('obj');
    }
    
    
}
