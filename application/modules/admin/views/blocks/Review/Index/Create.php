<?php
class Admin_View_Block_Review_Index_Create extends Admin_View_Block_Abstract
{
    protected $_review = null;
    public function __construct()
    {
        parent::__construct();
        $this->setTemplate('review/index/create.phtml');
    }
    
    public function getReview()
    {
        if(!$this->_review)
        {
            $object = Zend_Controller_Front::getInstance();
            if($id = (int)$object->getRequest()->getParam('id', 0))
            {
                $review = Ccc::getModel("review/review")->find($id);
                if(!$review->valid())
                {
                    throw new Exception($this->_getTranslate()->translate('review ID is not valid.'));
                }
                
                $this->_review = $review->current();
            }
            else
            {
                $this->_review = Ccc::getModel("review/review")->createRow();
            }
        }
        
        return $this->_review;
    }
    
    public function getWebsites()
    {
        $websiteModel  = Ccc::getModel('website/website');
        
        $select = $websiteModel->select()
                            ->setIntegrityCheck(false)
                            ->from(array('W'=>$websiteModel->getTableName()),array('website_id', 'name'))
                            ->order("website_id ASC");
        
        return $websiteModel->getAdapter()->fetchPairs($select);
    }
    
    public function getIsApprovedOptions()
    {
        return array(
            Review_Model_Review::APPROVED => Review_Model_Review::APPROVED_TEXT,
            Review_Model_Review::PENDNG_APPROVAL => Review_Model_Review::PENDNG_APPROVAL_TEXT
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
}
