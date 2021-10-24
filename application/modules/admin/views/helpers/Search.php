<?php
/**
*   Admin_View_Helper_Search class
**/
class Admin_View_Helper_Search
{
    /**
    *   Search Function. Used to get search data by calling getSearch function from admin search model.
    *   @return search data.
    **/
    public function search()
    {
        return Ccc::getModel('admin/search')->getSearch();
    }        
}