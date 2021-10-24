<?php

class Core_Model_Session extends Zend_Session_Namespace
{
	public function __construct()
	{		
		parent::__construct($this->_getNamespace(), false);	
	}
	
	private function _getNamespace()
	{
		return APPNAME.$this->_namespace;
	}
	
	/**
	*   Set Session Function. This function is used to set Session variable.
	*   @param mixed $param.
    *   @param mixed $value. 
    *	@return session.
	**/
	public function set($param, $value)
	{
		$this->{$param} = $value;
		return $this;
	}
	
	/**
	*	Get Session Function. This function is used to get Session variable.
	*   @param mixed $param.
    *	return session|null.
	**/
	public function get($param)
	{
		if($this->has($param))
		{
			return $this->{$param};
		}
			
		return null;
	}
	
	/**
	*	Has Session Function. This function is used to Session is setted or not.
	*   @param mixed $param. 
    *	return true|false.
	**/
	public function has($param)
	{
		if(isset($this->{$param}))
		{
			return true;
		}
		
		return false;
	}
	
	/**
	*	Unset Session Function. This function is used to unset Session variable.
    *   @param mixed $param. 
	*	return object.
	**/
	public function remove($param)
	{
		if($this->has($param))
		{
			unset($this->{$param});
		}
		
		return $this;
	}
    
    /**
    *   Set Session Function. This function is used to set Session variable.
    *   @param mixed $param.
    *   @param mixed $value. 
    *    @return session.
    **/
    public function setSession($param, $value)
    {
        $this->{$param} = $value;
        return $this;
    }
    
    /**
    *    Get Session Function. This function is used to get Session variable.
    *   @param mixed $param.
    *    return session|null.
    **/
    public function getSession($param)
    {
        if($this->hasSession($param))
        {
            return $this->{$param};
        }
            
        return null;
    }
    
    /**
    *    Has Session Function. This function is used to Session is setted or not.
    *   @param mixed $param. 
    *    return true|false.
    **/
    public function hasSession($param)
    {
        if(isset($this->{$param}))
        {
            return true;
        }
        
        return false;
    }
    
    /**
    *    Unset Session Function. This function is used to unset Session variable.
    *   @param mixed $param. 
    *    return object.
    **/
    public function unsetSession($param)
    {
        if($this->hasSession($param))
        {
            unset($this->{$param});
        }
        
        return $this;
    }
    
    public function getSessionid()    
    {
        return session_id();
    }
}