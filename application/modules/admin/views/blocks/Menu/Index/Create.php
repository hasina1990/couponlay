<?php
class Admin_View_Block_Menu_Index_Create extends Admin_View_Block_Abstract
{
    protected $_menu = null;
    public function __construct()
    {
        parent::__construct();
        $this->setTemplate('menu/index/create.phtml');
    }
    
    public function getMenu()
    {
        if(!$this->_menu)
        {
            $object = Zend_Controller_Front::getInstance();
            if($id = (int)$object->getRequest()->getParam('id', 0))
            {
                $menu = Ccc::getModel("core/menu")->find($id);
                if(!$menu->valid())
                {
                    throw new Exception($this->_getTranslate()->translate('Menu ID is not valid.'));
                }
                
                $this->_menu = $menu->current();
            }
            else
            {
                $this->_menu = Ccc::getModel("core/menu")->createRow();
            }
        }
        
        return $this->_menu;
    }
    
    public function getIsEnabledOptions()
    {
        return array(
            Core_Model_Menu::IS_ENABLED_YES => Core_Model_Menu::IS_ENABLED_YES_TEXT,
            Core_Model_Menu::IS_ENABLED_NO => Core_Model_Menu::IS_ENABLED_NO_TEXT
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
    
    public function getPages()
    {
          $pageModel = Ccc::getModel("page/page");
          
          $select =  $pageModel->select()
                               ->from(array("P"=>$pageModel->getTableName()), array("page_id", "name", "url_key"));
          
          return  $pageModel->getAdapter()->fetchAll($select);
    }
}
