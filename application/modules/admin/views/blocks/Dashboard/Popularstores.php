<?php
class Admin_View_Block_Dashboard_Popularstores extends Admin_View_Block_Widget_Grid
{
    public function __construct()
    {
        parent::__construct();
        $this->setTemplate('dashboard/popularstores.phtml');
    }
    
    protected function _prepareQuery()
    {
        $websiteModel          = Ccc::getModel('website/website');
        
        $select = $websiteModel->select()
                                ->setIntegrityCheck(false)
                                ->from(array('W'=>$websiteModel->getTableName()),array('*'))
                                ->order("view_count DESC");
        
        return $select;
    }
    
    public function getCollection()
    {
        $select = $this->_prepareQuery();
        $select->limit(5);  
        return Ccc::getModel('website/website')->fetchAll($select);
    }
    
    public function getTotalStoreCount()
    {
        $select = $this->_prepareQuery();
        return count(Ccc::getModel('website/website')->getAdapter()->fetchCol($select));
    }
    
    public function getIconImageUrl($website)
    {
         return Ccc::getModel("website/uploader")->setWebsite($website)->getIconImageUrl();
    }
    
    public function getIsEnabled($store)
    {
        if($store->is_enabled == Website_Model_Website::IS_ENABLED_YES)
        {
            return Website_Model_Website::IS_ENABLED_YES_TEXT;
        }
        
        return Website_Model_Website::IS_ENABLED_NO_TEXT;
    }
    
    public function getManageWebsiteUrl()
    {
        return $this->url(array('module'=>'admin','controller'=>'website_index','action'=>'index-json','page'=>1, 'sort'=>'W.view_count', 'dir'=>'desc'),null,true);
    }
}
