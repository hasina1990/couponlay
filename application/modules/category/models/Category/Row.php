<?php
class Category_Model_Category_Row extends Core_Model_Table_Row_Abstract
{
	protected $_tableClass = 'Category_Model_Category';
    protected $_validationClassModel = 'category/category_row_validation';
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
    
    public function getImageFile()
    {
        if($this->image)
        {
            $path = Ccc::getModel("agent/uploader")->getAgentImagePath();
            $path = Ccc::getBaseDir($path."/".Agent_Model_Agent::IMAGE."/".$this->image);
            if(file_exists($path) && !is_dir($path))
            {
                return $path;
            }
        }
             
        return Ccc::getModel("agent/agent")->getDefaultImage();
    }
    
    public function isDuplicateRecord()
    {
        $select = $this->select();    
                   
        if($this->name)
        {
            $select->where('name = ?', $this->name);
        }
        
        if($this->category_id)
        {
            $select->where('category_id != ?', $this->category_id);
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
        
        if($this->category_id)
        {
            $select->where('category_id != ?', $this->category_id);
        }
        
        return $this->getTable()->fetchRow($select);
    }
}