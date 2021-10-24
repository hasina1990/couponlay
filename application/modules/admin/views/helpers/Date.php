<?php
/**
*   Admin_View_Helper_Date class
**/
class Admin_View_Helper_Date
{
    /**
    *   Date Function. Used to get object of admin date model.
    *   @return object.
    **/
    public function date()
    {
        return Ccc::getModel("admin/date");
    } 
}