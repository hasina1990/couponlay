<?php
class Block_Model_Block extends Core_Model_Table_Abstract
{
	protected $_name = 'block';
    protected $_primary = 'block_id';
    protected $_rowClass = 'Block_Model_Block_Row';
    protected $_rowsetClass = 'Block_Model_Block_Rowset';
  
    const IS_ENABLED_YES = 1;
    const IS_ENABLED_NO  = 2; 
    const IS_ENABLED_YES_TEXT = 'Yes';
    const IS_ENABLED_NO_TEXT  = 'No';
   
    public function getIsEnabledOptions()
    {
        $options = array(
            Block_Model_Block::IS_ENABLED_YES => Block_Model_Block::IS_ENABLED_YES_TEXT,
            Block_Model_Block::IS_ENABLED_NO  => Block_Model_Block::IS_ENABLED_NO_TEXT
        );
        return $options;
    }
}