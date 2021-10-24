<?php
class Admin_View_Block_Block_Footer_Index extends Admin_View_Block_Abstract
{
    public function __construct()
    {
        parent::__construct();
        $this->setTemplate('block/footer/index.phtml');
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
