<?php
class Admin_View_Block_Widget_Grid extends Core_View_Block_Widget_Grid
{
   protected $_template = null;
    
    public function _getSession()
    {
        return Ccc::getSingleton("admin/session");
    }
    
    public function search()
    {
        return Ccc::getModel('admin/search')->getSearch();
    }
    
    public function date()
    {
        return Ccc::getModel("admin/date");
    }      
}