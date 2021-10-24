<?php
class Admin_View_Block_Pages_Html_Footer extends Admin_View_Block_Abstract
{   
	public function __construct()
	{
		parent::__construct();
		$this->setTemplate('pages/html/footer.phtml');
	}
    public function getFooterText()
    {
        return Ccc::getModel('config/system_config')->getSystemConfig('general/website/footer_text');
    }
}
