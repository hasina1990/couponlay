<?php
class User_View_Block_Widget_Grid extends Core_View_Block_Widget_Grid
{
    protected $_template = null;
    
    public function _getSession()
    {
        return Ccc::getSingleton("user/session");
    }
    
    public function date()
    {
        return Ccc::getModel("user/date");
    }
    
    public function search()
    {
        return Ccc::getModel('user/search')->getSearch();
    }
}