<?php
class Admin_View_Block_User_Index_Index extends Admin_View_Block_Abstract
{
    public function __construct()
    {
        parent::__construct();
        $this->setTemplate('user/index/index.phtml');
    }
              
    public function getGridUrl()
    {
        return $this->url(array('action'=>'grid'));
    }
}
