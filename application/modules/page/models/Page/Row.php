<?php
class Page_Model_Page_Row extends Core_Model_Table_Row_Abstract
{
	protected $_tableClass = 'Page_Model_Page';
    protected $_validationClassModel = 'page/page_row_validation';
    protected $_isURLKeyUpdated = false;
    
    protected function _afterSave()
    {
        if($this->_isURLKeyUpdated)
        {
            Ccc::getModel("core/url_rewrite")->saveReWriteURL($this);
            
            $menus = Ccc::getModel("core/menu")->fetchAll("page_id = ".(int)$this->page_id);
            if(count($menus))
            {
                foreach($menus as $_menu)
                {
                     $_menu->url =  $this->url_key;
                     $_menu->updated_date =  date("Y-m-d H:i:s");
                     $_menu->save();
                }
            }
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
        
        if($this->page_id)
        {
            $select->where('page_id != ?', $this->page_id);
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
        
        if($this->page_id)
        {
            $select->where('page_id != ?', $this->page_id);
        }
        
        return $this->getTable()->fetchRow($select);
    }
}