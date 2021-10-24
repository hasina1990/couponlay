<?php
/**
*	Config_Model_System_Config_Row
**/
class Config_Model_System_Config_Row_Validation extends Core_Model_Table_Row_Validation_Abstract
{
    public function hasValidValue($key)
    {
        $row = $this->getRowClassObject();
        
        switch($key)
        {
            case 'name':
            case 'value':
                return $this->_validateName($key, $row);
                break;
                    
            case 'access_key':
                return $this->_validateAccessKey($key, $row);
                break;
                
            default:
                break;
        }
        
        return parent::hasValidValue($key);
    }
    
    protected function _validateName($key, $row)
    {
        $validator = new Zend_Validate_NotEmpty();        
        if(!$validator->isValid($row->{$key})) 
        {
            $this->setError($key, "Please enter valid {$key}.");
            return false;
        }
        return true;
    }
              
    protected function _validateAccessKey($key, $row)
    {
        $validator = new Zend_Validate_NotEmpty();        
        if(!$validator->isValid($row->{$key})) 
        {
            $this->setError($key, 'Please enter valid access-key.');
            return false;
        }
                    
        return true;
    }
}