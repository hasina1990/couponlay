<?php
class Admin_View_Block_Tag_Website_Index extends Admin_View_Block_Abstract
{
    protected $_tag = null;
    
    public function __construct()
    {
        parent::__construct();
        $this->setTemplate('tag/website/index.phtml');
    }
    
    public function getTag()
    {
        if(!$this->_tag)
        {
            if(!$id = (int)$this->getRequest()->getParam("id", 0))
            {
                throw new Exception("Tag is not valid.");
            }
            
            $tag   = Ccc::getModel('tag/tag')->fetchRow("tag_id = ".(int)$id);
            if(!$tag)
            {
                throw new Exception("Tag is not valid.");
            }
            
            $this->_tag = $tag;
        }
        
        return $this->_tag;
    }
    
    public function getSaveUrl()
    {
        return $this->url(array('action'=>'save', 'id'=>(int)$this->getRequest()->getParam("id", 0)));
    }
    
    public function getBackUrl()
    {
        return $this->url(array('module'=>'admin','controller'=>'tag_index','action'=>'index-json', 'page'=>1),null,true);
    }
    
    public function getGridUrl()
    {
        return $this->url(array('action'=>'grid'));
    }
}
