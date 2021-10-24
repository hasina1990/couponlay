<?php
class Website_Model_Website_Row extends Core_Model_Table_Row_Abstract
{
	protected $_tableClass = 'Website_Model_Website';
    protected $_validationClassModel = 'website/website_row_validation';
    protected $_isURLKeyUpdated = false;
    
    protected function _afterSave()
    {
        if($this->_isURLKeyUpdated)
        {
            Ccc::getModel("core/url_rewrite")->saveReWriteURL($this);
        }
        
        return parent::_afterSave();
    }
    
    public function _beforeSave()
    {
        if($this->_cleanData['url_key'] != $this->url_key)
        {
            $this->_isURLKeyUpdated = true;
        }

        return parent::_beforeSave();
    }
                   
    public function isDuplicateRecord()
    {
        $select = $this->select();    
        
        if($this->name)
        {
            $select->where('name = ?', $this->name);
        }
        
        if($this->website_url)
        {
            $select->where('website_url = ?', $this->website_url);
        }
                     
        if($this->website_id)
        {
            $select->where('website_id != ?', $this->website_id);
        }
        
        return $this->getTable()->fetchRow($select);
    }
    
    public function isDuplicateURLRecord()
    {
        $select = $this->select();    
                   
        if($this->url_key)
        {
            $select->where('url_key = ?', $this->url_key);
        }
        
        if($this->website_id)
        {
            $select->where('website_id != ?', $this->website_id);
        }
        
        return $this->getTable()->fetchRow($select);
    }
}