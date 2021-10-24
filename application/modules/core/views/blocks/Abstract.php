<?php
class Core_View_Block_Abstract extends Zend_View
{
    protected $_modulePath = null;
    protected $_template = null;
    
	public function _getSession()
    {
        return Ccc::getSingleton("admin/session");
    }
    
    public function _getTimezone()
    {
        return Ccc::getSingleton("admin/timezone");
    }
    
    public function setModulePath($module)
    {
        if(!$module)
        {
            throw new Core_View_Block_Exception('"Module" must have non-empty value.');
        }
    
        $this->_modulePath = APPLICATION_PATH.DS.'modules'.DS. strtolower($module);
        return $this;
    }   
    
    public function getModulePath()
    {
        return $this->_modulePath;
    }
    
    public function setTemplate($template)
    {
        if(null === $template)
        {
            throw new Core_View_Block_Exception('"$template" should not be null or empty.');
        }
    
        $this->_template = $template;
        return $this;
    }
    
    public function getTemplate()
    {
        return $this->_template;
    }
    
    public function toHtml()
    {    //echo $this->getModulePath().DS.'views'.DS.'scripts'.DS.$this->getTemplate()."<br>";
        $this->setScriptPath($this->getModulePath().DS.'views'.DS.'scripts'.DS);
        return $this->render($this->getTemplate());
    }
    
    public function date()
    {
        return Ccc::getModel("core/date");
    }    
    
    protected function getRequest()
    {
        return Zend_Controller_Front::getInstance()->getRequest();
    }

    
    public function getObject()
    {
        return $this->getRequest()->getParam('obj');
    }
}
