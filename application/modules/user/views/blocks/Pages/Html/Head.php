<?php
class User_View_Block_Pages_Html_Head extends Core_View_Block_Abstract
{
    public function __construct()
    {
        parent::__construct();
        $this->setTemplate('pages/html/head.phtml');
    }
    
    public function getDesign()
    {
        return Ccc::getModel('user/design')->getDesign();
    }
}
