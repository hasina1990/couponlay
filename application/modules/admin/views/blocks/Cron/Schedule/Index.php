<?php
class Admin_View_Block_Cron_Schedule_Index extends Admin_View_Block_Abstract
{
    public function __construct()
    {
        parent::__construct();
        $this->setTemplate('cron/schedule/index.phtml');
    }
    
    public function getListUrl()
    {
        return $this->url(array('action'=>'grid'));
    }
}
