<?php
class Admin_View_Block_Pages_Html_Menu extends Admin_View_Block_Abstract
{
	const TAB_DASHBOARD = 'dashboard';
	
	public function __construct()
    {
        parent::__construct();
        $this->setTemplate('pages/html/menu.phtml');		
    }
	public function getActiveTab()
	{
		return $this->_getSession()->get('activeTab');
	}
    
    /*public function isMasterAdmin()
    {
        return Ccc::getModel('admin/admin')->isMasterAdmin();
    }
    
    public function isNormalAdmin()
    {
        return Ccc::getModel('admin/admin')->isNormalAdmin();
    }
    
    public function isStaffAdmin()
    {
        return Ccc::getModel('admin/admin')->isStaffAdmin();
    }
    
     public function _getAdminResources()
    {
        $login = Ccc::getModel('admin/session')->getSession('login');
        return $login->getResources();
    }
    
    public function getMenu()
    {
        $menu = $this->getResources();
        $this->_getChildMenu($menu, 0);
        return $this;
    }
    
    protected function _getChildMenu($menu, $cnt)
    {
        
        echo ($cnt == 0) ? "<ul id=\"nav\">" : "<ul>";
        foreach($menu as $_menu)
        {
            $childMenu = $this->getResources($_menu);
            
            echo ($childMenu->count()) ? "<li onmouseover=\"jQuery(this).addClass('over')\" onmouseout=\"jQuery(this).removeClass('over')\" class=\"". $_menu->class ." ".$_menu->icon_class." level". $cnt ." parent\">" : "<li class=\"". $_menu->class ." ".$_menu->icon_class." level". $cnt ."\">";
            
            echo (trim($_menu->path)) ? "<a href=\"". $this->baseUrl($_menu->path) ."\" class=\"\">" : "<a href=\"javascript:void(0);\" onclick=\"return false\" class=\"\">";
            echo "<span>". $_menu->title ."</span>";
            echo "</a>";
            
            $this->_getChildMenu($childMenu, $cnt + 1);
            
            echo "</li>";
        }
        echo "</ul>";
        return $this;
    }
    
    public function getResources($object = null)
    {
        $resources = $this->_getAdminResources();
        
        $resourceModel = Ccc::getModel('resource/resource');
        $select = $resourceModel->select()
                                ->where('is_enabled = ?', Resource_Model_Resource::IS_ENABLED_ENABLED)
                                ->where('is_valid = ?', Resource_Model_Resource::IS_VALID_YES)
                                ->where('is_menu = ?', Resource_Model_Resource::IS_MENU_YES);
                                
        if(!in_array('all', $resources))
        {
            $select->where('resource_id IN (?)', array_keys($resources));
        }
        
        if(!$object)
        {
            $select->where('parent_id = ?', Resource_Model_Resource::ROOT_ID);
        }
        else
        {
            $select->where('parent_id = ?', $object->resource_id);
        }
        $select->order('sort_order ASC');
        $resourceMenu = $resourceModel->fetchAll($select);
        return $resourceMenu;
    }*/
    
}
