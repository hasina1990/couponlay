<?php
class Admin_View_Block_Block_Index_Create extends Admin_View_Block_Abstract
{
    protected $_block = null;
    
    public function __construct()
    {
        parent::__construct();
        $this->setTemplate('block/index/create.phtml');
    }
    
    public function getBlock()
    {
        if(!$this->_block)
        {
            $object = Zend_Controller_Front::getInstance();
            if($id = (int)$object->getRequest()->getParam('id', 0))
            {
                $block = Ccc::getModel("block/block")->find($id);
                if(!$block->valid())
                {
                    throw new Exception($this->_getTranslate()->translate('Block ID is not valid.'));
                }
                
                $this->_block = $block->current();
            }
            else
            {
                $this->_block = Ccc::getModel("block/block")->createRow();
            }
        }
        
        return $this->_block;
    }
    
    public function getIsEnabledOptions()
    {
        return array(
            Block_Model_Block::IS_ENABLED_YES => Block_Model_Block::IS_ENABLED_YES_TEXT,
            Block_Model_Block::IS_ENABLED_NO => Block_Model_Block::IS_ENABLED_NO_TEXT
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
