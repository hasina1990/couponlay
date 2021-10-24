<?php
class Admin_View_Block_Home_Index_Index extends Admin_View_Block_Abstract
{
    public function __construct()
    {
        parent::__construct();
        $this->setTemplate('home/index/index.phtml');
    }
    
    public function getSaveUrl()
    {
        return $this->url(array('action'=>'save'));
    }
    
    public function getGridUrl()
    {
        return $this->url(array('action'=>'grid'));
    }
}
