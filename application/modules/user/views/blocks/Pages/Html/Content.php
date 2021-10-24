<?php
class User_View_Block_Pages_Html_Content extends Core_View_Block_Abstract
{
    public function __construct()
    {
        parent::__construct();
        $this->setTemplate('pages/html/content.phtml');
    }
}
