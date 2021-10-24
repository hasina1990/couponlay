<?php
class Tag_Model_Tag_Row extends Core_Model_Table_Row_Abstract
{
	protected $_tableClass = 'Tag_Model_Tag';
    protected $_validationClassModel = 'tag/tag_row_validation';
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
    
    public function isDuplicateURLRecord()
    {
        $select = $this->select();    
                   
        if($this->url_key)
        {
            $select->where('url_key = ?', $this->url_key);
        }
        
        if($this->tag_id)
        {
            $select->where('tag_id != ?', $this->tag_id);
        }
        
        return $this->getTable()->fetchRow($select);
    }
                   
    public function isDuplicateRecord()
    {
        $select = $this->select();    
        if($this->name)
        {
            $select->where('name = ?', $this->name);
        }
                                   
        if($this->tag_id)
        {
            $select->where('tag_id != ?', $this->tag_id);
        }
        
        return $this->getTable()->fetchRow($select);
    }
}