<?php
class Admin_View_Block_Block_Index_Grid extends Admin_View_Block_Widget_Grid
{
    protected $_defaultSort     = 'B.block_id';
    
    public function __construct()
    {
        parent::__construct();
        $this->setTemplate('block/index/grid.phtml');
    }
    
    protected function _prepareQuery()
    {
        $blockModel          = Ccc::getModel('block/block');
        
        $select = $blockModel->select()
                                ->setIntegrityCheck(false)
                                ->from(array('B'=>$blockModel->getTableName()),array('*'));
        
        $select         = $this->setOrder($select);
        $select         = $this->_setFilter($select);
        return $select;
    }
    
    public function getCollection()
    {
        $select = $this->_prepareQuery();
        $paginator = Zend_Paginator::factory($select);
        $paginator->setCurrentPageNumber($this->getCurrentPage());
        $paginator->setDefaultItemCountPerPage($this->getRecordPerPage());
        return $paginator->setPageRange($this->getPageRange());
    }
    
    public function getAllIds()
    {
        $blockModel          = Ccc::getModel('block/block');
        $select = $this->_prepareQuery();
        return $blockModel->fetchAll($select)->getAllIds();
    }
    
    protected function _setFilter($select)
    {
		Ccc::getModel('admin/search')->setSearch();
		
        if((int)Ccc::getHelper('admin/search')->search()->block_id_from)
        {
            $select->where("B.block_id >= ?", (int)Ccc::getHelper('admin/search')->search()->block_id_from);
        }
        if((int)Ccc::getHelper('admin/search')->search()->block_id_to)
        {
            $select->where("B.block_id <= ?", (int)Ccc::getHelper('admin/search')->search()->block_id_to);
        }
                
        if(Ccc::getHelper('admin/search')->search()->name = (string)trim(Ccc::getHelper('admin/search')->search()->name))
        {
            $select->where("B.name Like ?", "%".Ccc::getHelper('admin/search')->search()->name."%");
        }
        
        if(Ccc::getHelper('admin/search')->search()->title = (string)trim(Ccc::getHelper('admin/search')->search()->title))
        {
            $select->where("B.title Like ?", "%".Ccc::getHelper('admin/search')->search()->title."%");
        }
                               
        if(Ccc::getHelper('admin/search')->search()->created_date_from)
        {
            $select->where("DATE(B.created_date) >= ?", date('Y-m-d', strtotime(Ccc::getHelper('admin/search')->search()->created_date_from)));
        }
                 
        if(Ccc::getHelper('admin/search')->search()->created_date_to)
        {
            $select->where("DATE(B.created_date) <= ?", date('Y-m-d', strtotime(Ccc::getHelper('admin/search')->search()->created_date_to)));
        }
        
        if(Ccc::getHelper('admin/search')->search()->updated_date_from)
        {
            $select->where("DATE(B.updated_date) >= ?", date('Y-m-d', strtotime(Ccc::getHelper('admin/search')->search()->updated_date_from)));
        }
        
        if(Ccc::getHelper('admin/search')->search()->updated_date_to)
        {
            $select->where("DATE(B.updated_date) <= ?", date('Y-m-d', strtotime(Ccc::getHelper('admin/search')->search()->updated_date_to)));
        }
       
        if((int)Ccc::getHelper('admin/search')->search()->is_enabled)
        {
            $select->where("B.is_enabled = ?", (int)Ccc::getHelper('admin/search')->search()->is_enabled);
        }
        
        return $select;
    }
    
    public function getIsEnabled($block)
    {
        if($block->is_enabled == Block_Model_Block::IS_ENABLED_YES)
        {
            return Block_Model_Block::IS_ENABLED_YES_TEXT;
        }
        
        return Block_Model_Block::IS_ENABLED_NO_TEXT;
    }
    
    public function getIsEnabledOptions()
    {
        return array(
            0 => "",
            Block_Model_Block::IS_ENABLED_YES => Block_Model_Block::IS_ENABLED_YES_TEXT,
            Block_Model_Block::IS_ENABLED_NO => Block_Model_Block::IS_ENABLED_NO_TEXT
        );
    }
    
    public function getResetUrl()
    {
        return $this->url(array('module'=>'admin','controller'=>'block_index','action'=>'reset','page'=>1),null,true);
    }
           
    public function getDeleteUrl()
    {
        return $this->url(array('action'=>'delete'));
    }
    
    public function getGridUrl()
    {
        return $this->url(array('action'=>'grid'));
    }
  
    public function getEditUrl($block)
    {
        return $this->url(array('action'=>'edit','id'=>$block->block_id));
    }
}
