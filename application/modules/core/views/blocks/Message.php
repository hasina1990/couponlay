<?php
class Core_View_Block_Message extends Core_View_Block_Abstract
{
    public function getSuccess()
    {
        return Ccc::getModel("core/message")->setNamespace('success');    
    }
    
    public function getError()
    {
        return Ccc::getModel("core/message")->setNamespace('error');        
    }
    
    public function getNotice()
    {
        return Ccc::getModel("core/message")->setNamespace('notice');        
    }
    
    public function getMessages()
    {
        return Ccc::getModel("core/message")->getMessages();
    }
    
    public function clearMessages()
    {
        return Ccc::getModel("core/message")->clearMessages();
    }
}
