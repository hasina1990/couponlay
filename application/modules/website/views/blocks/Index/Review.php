<?php
class Website_View_Block_Index_Review extends User_View_Block_Widget_Grid
{
   protected $_website = null;
     
	public function __construct()                     
	{
        parent::__construct();
		$this->setTemplate('index/review.phtml');
	}
    
    public function getCaptchaNumbers()
    {
        Ccc::getModel('user/session')->unsetSession('captcha');
        $captcha = array();
        $captcha[0]=rand(1,5);
        $captcha[1]=rand(6,10);
        Ccc::getModel('user/session')->setSession('captcha',$captcha);
        return $captcha;
    }
    public function getSaveReviewUrl()
    {
        return $this->url(array('module'=>'website','controller'=>'index','action'=>'save-review'));
    }
    
    public function setWebsite($website)
    {
        $this->_website = $website;
        return $this;
    }
    
    public function getWebsite()
    {
        return $this->_website;
    }
}
