<?php
/**
*	Ccc_Core_Model_Table_Row_Validation
**/
class Core_Model_Table_Row_Validation_Abstract
{
    /**
    *	$_errors protected Variable. Used to store error messages.
    *	@var array
    **/
    protected $_errors = array();	
	
	/**
    *	$_row object protected Variable. Used to store error messages.
    *	@var array
    **/
    protected $_row = null;
	
	/**
    *	$_validateFields protected Variable. Used to validate data.
	*	@var array
    **/	
	protected $_validateFields = array();
	
	/**
    *	$_modifiedFields protected Variable. Used to validate data.
	*	@var array
    **/	
	protected $_modifiedFields = array();
	
    /**
    *   Set Row Class Object.
    *   @return errors.
    **/
	public function setRowClassObject(Core_Model_Table_Row_Abstract $row)
	{
		$this->_row = $row;
		return $this;
	}
	
	/**
    *   Get Row Class Object.
    *   @return object|null.
    **/
	public function getRowClassObject()
	{
		return $this->_row;
	}
	
	/**
    *   Get Row Class modified fields.
    *   @return object|null.
    **/
	public function loadModifiedFields()
	{
		if(!$this->_modifiedFields)
		{
			$this->_modifiedFields = $this->getRowClassObject()->getModifiedFields();
		}
		return $this;
	}
	
	/**
    *   Get Row Class modified fields.
    *   @return object|null.
    **/
	public function getModifiedFields()
	{
		if(!$this->_modifiedFields)
		{
			$this->loadModifiedFields();
		}
		return $this->_modifiedFields;
	}
	
	/**
    *   Get Errors Function. Used to get all error  messages.
    *   @return errors.
    **/
	public function getErrors()
	{
		return array_values($this->_errors);
	}
	
    /**
    *   Get Errors In Json Format Function. Used to get all error  messages encoded in json format.
    *   @return errors.
    **/
	public function getErrorsInJsonFormat()
	{
		return json_encode(array_values($this->_errors));
	}
	
    /**
    *   Get Errors With Column Function. Used to get all error  messages with column.
    *   @return errors.
    **/
	public function getErrorsWithColumn()
	{
		return $this->_errors;
	}
	
    /**
    *   Get Errors With Column In Json Format Function. Used to get all error  messages with column encoded in Json format.
    *   @return errors.
    **/
	public function getErrorsWithColumnInJsonFormat()
	{
		return json_encode($this->_errors);
	}
	
    /**
    *   validate Function. Used to validate data.
    *   @param string $controller.
    *   @return true|false.
    **/
	public function validate()
	{
		$this->loadModifiedFields();
	
		$this->_errors = array();
		
		if($data = $this->getRowClassObject()->getData())
		{			
			foreach(array_keys($data) as $key)
			{
				if(!$this->hasValidValue($key))
				{
					continue;
				}
			}
		}
		
		if($this->_errors)
		{
			$this->getRowClassObject()->setModifiedFields($this->getModifiedFields())->setErrors($this->_errors);
			return false;
		}
		return true;	
	}
	
	/**
    *    Has Valid Value Function. This function is used to check the value is valid or not.
    *   @param mixed $key.
    *   @return true|false.
    **/
	public function hasValidValue($key)
	{
		return true;
	}
	
	public function setError($key, $message)
	{
		if(!$key)
		{
			throw new Core_Model_Exception('"$key" must have non-empty value.');
		}
		
		if($message)
		{
			$this->_errors[$key] = $message;
		}	
		return $this;
	}
	
	protected function canProcessIfNull($key)
	{
		if(!empty($this->getRowClassObject()->{$key}))
		{
			return true;
		}
		
		if(isset($this->_modifiedFields[$key]))
		{
			unset($this->_modifiedFields[$key]);
		}
		
		return false;
	}
	
	protected function _getDateModel()
	{
		return $this->getRowClassObject()->getDateModel();
	}
	
	protected function _getTimezoneModel()
	{
		return $this->getRowClassObject()->getTimezoneModel();
	}
}
?>