<?php
/**
*	Ccc_Core_Model_Message
**/
class Core_Model_Message
{
    /**
    *	$_session protected Variable. It is used to store session data.
    *	@var mixed
    **/
	protected $_session = null;	
    
    /**
    *	$_namespace protected Variable. It contains session namespace as success.
    *	@var mixed
    **/
	protected $_namespace = 'success';
	
	public function __construct()
	{
		$this->_session = Ccc::getModel("core/session");
		
		if(!isset($this->_session->message))
		{
			$this->reset();
		}							
	}
	
	/**
	*	Reset Function. This function is used to reset the message.
	*	@return object.
	**/
	public function reset()
	{
		$this->_session->message = array(
									'success' => array(),
									'notice' => array(),
									'error' => array()									
								);
		return $this;						
	}
	
	/**
	*	Set Namespace Function. This function is used to set Namespace.
    *   @param mixed $namespace.
	*	@return namespace|error.
	**/
	public function setNamespace($namespace)
	{
		if(in_array($namespace, array_keys($this->_session->message)))
		{
			$this->_namespace = $namespace;				
			return 	$this;
		}
		else
		{
			throw new Core_Model_Exception('Namespace is not valid for message.');
		}
		
	}
	
	/**
	*	Add Message Function. This function is used to add new Message.
    *   @param string $message.
	*	@return message|error.
	**/	
	public function addMessage($message)
	{
		if(!$message)
		{
			throw new Core_Model_Exception('Message should not be empty.');
		}
		
		$this->_session->message[$this->_namespace][] = $message;
		return $this;
	}
	
	/**
	*	Get Messages Function. This function is used to get added Messages.
	*	@return messages|null.
	**/
	public function getMessages()
	{
		if(isset($this->_session->message[$this->_namespace]))
		{			
			return $this->_session->message[$this->_namespace];
		}	
		
		return array();
	}
	
	/**
	*	Has Messages Function. This function is used to check there are Messages or not.
	*	@return true|false.
	**/
	public function hasMessages()
	{
		if(isset($this->_session->message[$this->_namespace]))
		{
			return true;
		}
		
		return false;
	}
	
	/**
	*	Clear Messages Function. This function is used to clear Messages.
	*	@return object.
	**/
	public function clearMessages()
	{
		if(isset($this->_session->message[$this->_namespace]))
		{			
			$this->_session->message[$this->_namespace] = array();
		}	
		
		return $this;
	}		
}