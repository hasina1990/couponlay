<?php
/**
*   Customer_View_Helper_Search class
**/
class User_View_Helper_Search
{
    /**
    *   Search Function. Used to get search data by calling getSearch function from user search model.
    *   @return search data.
    **/
    public function search()
    {
        return Ccc::getModel('user/search')->getSearch();
    }        
}