<?php
class Core_Model_Exception extends Zend_Exception
{
    /**
    *	$_data protected Variable. It is used to store the data.
    *	@var mixed
    **/
	protected $_data = array();
	
	
	
    /**
    *   __set Magic Method. This function is used to set value to undefined variable.
    *   @param string $key.
    *   @param mixed $value.
    *   @return key.
    **/
	public function __set($key, $value)
	{
		$key = trim($key);
		if(!$key)
		{
			throw new Core_Model_Exception('"$key" should not be null.');
		}	
		
		$this->_data[$key] = $value;
		return $this;
	}
	
    /**
    *   __get Magic Method. This function is used to get value of undefined variable.
    *   @param string $key.
    *   @return value.
    **/
	public function __get($key)
	{
		$key = trim($key);
		if(!$key)
		{
			throw new Core_Model_Exception('"$key" should not be null.');
		}
		
		if($this->_hasParam($key))	
		{
			return $this->_data[$key];
		}
		return null;
	}
	
    /**
    *   _has Param Function. This function is used to check variable is seted or not.
    *   @param string $key.
    *   @return true | false.
    **/
	protected function _hasParam($key)
	{
		$key = trim($key);
		if(!$key)
		{
			throw new Core_Model_Exception('"$key" should not be null.');
		}
		
		if(array_key_exists($key, $this->_data))	
		{
			return true;
		}
		return false;
	}
	
    /**
    *   Get Data Function. This function is used to get data.
    *   @return data.
    **/
	public function getData()
	{
		return $this->_data;
	}
	
    /**
    *   Set Data Function. This function is used to set given data.
    *   @param mixed $data.
    *   @return data.
    **/
	public function setData($data)
	{
		$this->_data = $data;
		return $this;
	}
}