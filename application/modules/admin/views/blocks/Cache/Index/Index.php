<?php
class Admin_View_Block_Cache_Index_Index extends Admin_View_Block_Abstract
{
    public function __construct()
    {
        parent::__construct();
        $this->setTemplate('cache/index/index.phtml');
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
