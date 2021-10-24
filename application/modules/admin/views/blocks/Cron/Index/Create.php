<?php
class Admin_View_Block_Cron_Index_Create extends Admin_View_Block_Abstract
{
	public function __construct()
    {
        parent::__construct();
        $this->setTemplate('cron/index/create.phtml');
    }
    public function getIndexUrl()
    {
        return $this->url(array('action'=>'index','id'=>null));
    }
    public function getIndexJsonUrl()
    {
        return $this->url(array('action'=>'index-json','id'=>null));
    }
    
    public function getSaveUrl($block)
    {
        return $this->url(array('action'=>'save'));
    }
    
    public function getCronData()
    {
        $object = Zend_Controller_Front::getInstance();
        if($id = (int)$object->getRequest()->getParam('id', 0))
        {
            $cron = Ccc::getModel("cron/cron")->find($id);
            if(!$cron->valid())
            {
                throw new Ccc_Controller_Action_Admin_Exception('Cron ID is not valid.');
            }
            $cron = $cron->current();
        }
        else
        {
            $cron = Ccc::getModel("cron/cron")->createRow();
        }
        
        return $cron;
    }
    
    public function getStatusOptions()
    {
        return Ccc::getModel('cron/cron_status')->getOptions();
    }
}
