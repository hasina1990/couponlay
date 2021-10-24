<?php
class Admin_View_Block_Home_Advertisement_Create extends Admin_View_Block_Abstract
{
    protected $_banner = null;
    
    public function __construct()
    {
        parent::__construct();
        $this->setTemplate('home/advertisement/create.phtml');
    }
    
    public function getBanner()
    {
        if(!$this->_banner)
        {
            $object = Zend_Controller_Front::getInstance();
            if($id = (int)$object->getRequest()->getParam('id', 0))
            {
                $banner = Ccc::getModel("banner/banner")->find($id);
                if(!$banner->valid())
                {
                    throw new Exception($this->_getTranslate()->translate('Banner ID is not valid.'));
                }
                
                $this->_banner = $banner->current();
            }
            else
            {
                $this->_banner = Ccc::getModel("banner/banner")->createRow();
            }
        }
        
        return $this->_banner;
    }
    
    public function getIsEnabledOptions()
    {
        return array(
            Banner_Model_Banner::IS_ENABLED_YES => Banner_Model_Banner::IS_ENABLED_YES_TEXT,
            Banner_Model_Banner::IS_ENABLED_NO  => Banner_Model_Banner::IS_ENABLED_NO_TEXT
        );
    }
                 
    public function getIndexJsonUrl()
    {
        return $this->url(array('action'=>'index-json','id'=>null));
    }
    
    public function getSaveUrl()
    {
        return $this->url(array('action'=>'save'));
    }
    
    public function getWebsites()
    {
        $websiteModel  = Ccc::getModel('website/website');
        
        $select = $websiteModel->select()
                            ->setIntegrityCheck(false)
                            ->from(array('W'=>$websiteModel->getTableName()),array('website_id', 'name'))
                            ->order("website_id ASC");
        
        $websites = $websiteModel->getAdapter()->fetchPairs($select);
        return array(0=>"") +  $websites;
    }
}
