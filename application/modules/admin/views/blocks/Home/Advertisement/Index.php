<?php
class Admin_View_Block_Home_Advertisement_Index extends Admin_View_Block_Abstract
{
    public function __construct()
    {
        parent::__construct();
        $this->setTemplate('home/advertisement/index.phtml');
    }
    
    public function getCreateUrl()
    {
        return $this->url(array('action'=>'create'));
    }
    
    public function getGridUrl()
    {
        return $this->url(array('action'=>'grid'));
    }
}
